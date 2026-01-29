<?php

require __DIR__ . "/vendor/autoload.php";
require __DIR__ . "/config/database.php";

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

# MSSQL Connection
$conn = openConnectionMSSQL();

function openConnectionMSSQL(): mixed
{
    $serverName = "10.4.1.8, 1433";
    $connectionOptions = [
        "Database" => "DB_ATT",
        "Uid"      => "att",
        "PWD"      => "P@ss!N@cT"
    ];

    $conn = sqlsrv_connect($serverName, $connectionOptions);

    if ($conn === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    return $conn;
}

function importExcel(
    string $table,
    array $columnMap,
    string $fileInputName = 'file_excel',
    bool $skipHeader = true
) {
    global $conn;

    if (!isset($_FILES[$fileInputName])) {
        throw new Exception("File tidak ditemukan");
    }

    $file = $_FILES[$fileInputName]['tmp_name'];

    $spreadsheet = IOFactory::load($file);
    $sheetData   = $spreadsheet->getActiveSheet()->toArray();

    if (empty($sheetData)) {
        throw new Exception("File Excel kosong");
    }

    $columns      = array_keys($columnMap);
    $placeholders = implode(',', array_fill(0, count($columns), '?'));

    $sql = "INSERT INTO {$table} (" . implode(',', $columns) . ")
            VALUES ({$placeholders})";

    if (!sqlsrv_begin_transaction($conn)) {
        throw new Exception(print_r(sqlsrv_errors(), true));
    }

    try {
        foreach ($sheetData as $index => $row) {
            if ($skipHeader && $index === 0) continue;

            if (!isset($row[1]) || trim((string)$row[1]) === '') {
                continue;
            }

            $values = [];

            foreach ($columnMap as $column => $excelIndex) {
                $value = $row[$excelIndex] ?? null;

                if ($column === 'account_number' && empty($value)) {
                    throw new Exception(
                        "Employee ID kosong di baris Excel ke " . ($index + 1)
                    );
                }

                if (in_array($column, [
                    'join_date',
                    'effective_date',
                    'end_effective_date',
                ])) {
                    if (empty($value)) {
                        $value = null;
                    } elseif (is_numeric($value)) {
                        $value = Date::excelToDateTimeObject($value)
                            ->format('Y-m-d');
                    } else {
                        $dt = DateTime::createFromFormat('d-m-Y', $value);
                        $value = $dt ? $dt->format('Y-m-d') : null;
                    }
                }

                $values[] = ($value === '' ? null : $value);
            }

            $params = [];
            foreach ($values as &$v) {
                $params[] = [&$v, SQLSRV_PARAM_IN];
            }

            $stmt = sqlsrv_prepare($conn, $sql, $params);

            if ($stmt === false) {
                throw new Exception(print_r(sqlsrv_errors(), true));
            }

            if (!sqlsrv_execute($stmt)) {
                throw new Exception(print_r(sqlsrv_errors(), true));
            }
        }

        sqlsrv_commit($conn);
        return "Import ke tabel {$table} berhasil";
    } catch (Exception $e) {
        sqlsrv_rollback($conn);
        throw $e;
    }
}

# Employee Functions
function getEmployees(): array
{
    global $conn;
    $tsql   = "SELECT * FROM dbo.karyawan WHERE employee_status IN ('Contract', 'Permanent', 'Probationary') ORDER BY employee_name ASC";
    $result = sqlsrv_query($conn, $tsql);

    $employees = [];
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $employees[] = $row;
    }

    return $employees;
}

# Attendance Functions
function getAttendances($offset = 0, $limit = 1000): array
{
    global $conn;
    $tsql = "WITH Base AS (
                SELECT DISTINCT
                    barcode,
                    AttendanceDate,
                    AttendanceTime
                FROM dbo.AttendanceMachinePolling
                WHERE AttendanceDate >= DATEADD(DAY, -7, CAST(GETDATE() AS DATE))
            ),
            CalculatedIO AS (
                SELECT 
                    barcode,
                    AttendanceDate,
                    AttendanceTime,
                    MIN(AttendanceTime) OVER(PARTITION BY barcode, AttendanceDate) as MinTime,
                    MAX(AttendanceTime) OVER(PARTITION BY barcode, AttendanceDate) as MaxTime
                FROM Base
            ),
            IO AS (
                SELECT
                    barcode,
                    AttendanceDate,
                    AttendanceTime,
                    CASE 
                        WHEN AttendanceTime = MinTime THEN 'IN'
                        WHEN AttendanceTime = MaxTime THEN 'OUT'
                        ELSE NULL
                    END AS AttendanceType
                FROM CalculatedIO
            )
            SELECT
                io.barcode,
                ISNULL(k.employee_name, 'TIDAK TERDAFTAR') AS employee_name,
                io.AttendanceDate,
                io.AttendanceTime,
                io.AttendanceType
            FROM IO io
            LEFT JOIN dbo.karyawan k ON k.barcode = io.barcode
            ORDER BY io.AttendanceDate DESC, io.AttendanceTime DESC
            OFFSET ? ROWS FETCH NEXT ? ROWS ONLY;";

    $stmt = sqlsrv_query($conn, $tsql, [$offset, $limit]);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $data = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }

    return $data;
}

# Dashboard Functions
function determineShift(?string $location, string $attendanceTime): ?string
{
    if (empty($location)) {
        return null;
    }
    
    $time = date('H:i:s', strtotime($attendanceTime));
    $hour = (int)date('H', strtotime($attendanceTime));
    $minute = (int)date('i', strtotime($attendanceTime));
    $totalMinutes = ($hour * 60) + $minute;
    
    if ($location === 'KIP') {
        // Shift 1: 07:30 - 15:30 (masuk antara 05:00 - 13:00)
        if ($totalMinutes >= 300 && $totalMinutes < 780) { // 05:00 - 13:00
            return 'Shift 1';
        }
        // Shift 2: 15:30 - 23:30 (masuk antara 13:00 - 21:00)
        elseif ($totalMinutes >= 780 && $totalMinutes < 1260) {
            return 'Shift 2';
        }
        else {
            return 'Shift 3';
        }
    }
    elseif ($location === 'CKG' || $location === 'Cakung') {
        return 'Shift 1';
    }
    
    return null;
}

function getJamMasuk($location, $shift, $date = null): string
{
    $defaultJamMasuk = '07:30:00';

    if ($date instanceof DateTime) {
        $date = $date->format('Y-m-d');
    }

    if (empty($location) || empty($shift)) {
        return $defaultJamMasuk;
    }

    $isSaturday = false;
    if (!empty($date)) {
        $dayOfWeek = date('N', strtotime($date));
        $isSaturday = ($dayOfWeek == 6);
    }

    if ($location === 'KIP') {
        if ($isSaturday) {
            return [
                'Shift 1' => '07:30:00',
                'Shift 2' => '12:40:00',
                'Shift 3' => '17:50:00',
            ][$shift] ?? $defaultJamMasuk;
        }

        return [
            'Shift 1' => '07:30:00',
            'Shift 2' => '15:30:00',
            'Shift 3' => '22:30:00',
        ][$shift] ?? $defaultJamMasuk;
    }

    if (in_array($location, ['CKG', 'Cakung'])) {
        return '07:30:00';
    }

    return $defaultJamMasuk;
}


function getAttendanceCTE(string $dateCondition, ?string $locationCondition = null): string
{
    $whereClause = $dateCondition;
    
    $joinKaryawan = "";
    $additionalWhere = "";
    
    if ($locationCondition) {
        $joinKaryawan = "INNER JOIN dbo.karyawan k ON Base_Raw.barcode = k.barcode";
        $additionalWhere = "WHERE $locationCondition";
    }
    
    return "WITH Base_Raw AS (
                SELECT DISTINCT 
                    barcode,
                    AttendanceDate,
                    AttendanceTime
                FROM dbo.AttendanceMachinePolling
                WHERE $dateCondition
            ),
            Base AS (
                SELECT DISTINCT
                    Base_Raw.barcode,
                    Base_Raw.AttendanceDate,
                    Base_Raw.AttendanceTime
                FROM Base_Raw
                $joinKaryawan
                $additionalWhere
            ),
            CalculatedIO AS (
                SELECT 
                    barcode,
                    AttendanceDate,
                    AttendanceTime,
                    MIN(AttendanceTime) OVER(PARTITION BY barcode, AttendanceDate) as MinTime,
                    MAX(AttendanceTime) OVER(PARTITION BY barcode, AttendanceDate) as MaxTime
                FROM Base
            ),
            IO AS (
                SELECT
                    barcode,
                    AttendanceDate,
                    AttendanceTime,
                    CASE 
                        WHEN AttendanceTime = MinTime THEN 'IN'
                        WHEN AttendanceTime = MaxTime THEN 'OUT'
                        ELSE NULL
                    END AS AttendanceType
                FROM CalculatedIO
            )";
}


function buildDateCondition($startDate, $endDate, string $tableAlias = ''): array
{
    $prefix = $tableAlias ? "$tableAlias." : "";
    
    if (empty($startDate)) $startDate = null;
    if (empty($endDate)) $endDate = null;
    
    if (!is_null($startDate)) {
        $startDate = date('Y-m-d', strtotime($startDate));
    }
    if (!is_null($endDate)) {
        $endDate = date('Y-m-d', strtotime($endDate));
    }
    
    if (is_null($startDate) && is_null($endDate)) {
        return [
            'condition' => "{$prefix}AttendanceDate = CAST(GETDATE() AS DATE)",
            'params' => []
        ];
    }
    
    if (!is_null($startDate) && is_null($endDate)) {
        return [
            'condition' => "{$prefix}AttendanceDate = ?",
            'params' => [$startDate]
        ];
    }
    
    return [
        'condition' => "{$prefix}AttendanceDate BETWEEN ? AND ?",
        'params' => [$startDate, $endDate]
    ];
}

function buildLocationCondition(?string $location, string $tableAlias = 'k', string $columnName = 'location'): array
{
    if (empty($location)) {
        return [
            'condition' => null,
            'params' => []
        ];
    }
    
    $prefix = $tableAlias ? "$tableAlias." : "";
    
    return [
        'condition' => "{$prefix}{$columnName} = ?",
        'params' => [$location]
    ];
}

function buildDateConditionWithYear($startDate, $endDate, int $defaultYear = 2026, string $tableAlias = ''): array
{
    $prefix = $tableAlias ? "$tableAlias." : "";
    
    if (empty($startDate)) $startDate = null;
    if (empty($endDate)) $endDate = null;
    
    if (!is_null($startDate)) {
        $startDate = date('Y-m-d', strtotime($startDate));
    }
    if (!is_null($endDate)) {
        $endDate = date('Y-m-d', strtotime($endDate));
    }
    
    if (is_null($startDate) && is_null($endDate)) {
        return [
            'condition' => "YEAR({$prefix}AttendanceDate) = $defaultYear",
            'params' => []
        ];
    }
    
    if (!is_null($startDate) && is_null($endDate)) {
        return [
            'condition' => "{$prefix}AttendanceDate = ?",
            'params' => [$startDate]
        ];
    }
    
    return [
        'condition' => "{$prefix}AttendanceDate BETWEEN ? AND ?",
        'params' => [$startDate, $endDate]
    ];
}


function executeQuery(string $sql, array $params = []): array
{
    global $conn;
    
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmt);
    
    return $row ?: [];
}

function fetchAllRows(string $sql, array $params = []): array
{
    global $conn;
    
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    
    $data = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }
    
    sqlsrv_free_stmt($stmt);
    
    return $data;
}


function getTotalHadirHariIni(?string $location = null, ?string $shift = null): int
{
    return getTotalHadirByDateRange(null, null, $location, $shift);
}

function getTotalHadirByDateRange($startDate = null, $endDate = null, ?string $location = null, ?string $shift = null): int
{
    $dateConfig = buildDateCondition($startDate, $endDate);
    $locationConfig = buildLocationCondition($location);
    $cte = getAttendanceCTE($dateConfig['condition'], $locationConfig['condition']);
    
    $params = array_merge($dateConfig['params'], $locationConfig['params']);
    
    $shiftFilter = "";
    if (!empty($shift) && !empty($location)) {
        $shiftFilter = getShiftTimeFilter($location, $shift);
        if (!empty($shiftFilter)) {
            $shiftFilter = "AND " . $shiftFilter;
        }
    }
    
    $sql = "$cte
            SELECT COUNT(*) AS TotalMasuk
            FROM IO
            WHERE AttendanceType = 'IN'
            $shiftFilter";
    
    $row = executeQuery($sql, $params);
    return (int)($row['TotalMasuk'] ?? 0);
}

function getTotalTerlambatHariIni(?string $jamMasuk = null, ?string $location = null, ?string $shift = null): int
{
    return getTotalTerlambatByDateRange(null, null, $jamMasuk, $location, $shift);
}

function getTotalTerlambatByDateRange($startDate = null, $endDate = null, ?string $jamMasuk = null, ?string $location = null, ?string $shift = null): int
{
    if (is_null($jamMasuk)) {
        $jamMasuk = getJamMasuk($location, $shift, $startDate);
    }
    
    $dateConfig = buildDateCondition($startDate, $endDate);
    $locationConfig = buildLocationCondition($location);
    $cte = getAttendanceCTE($dateConfig['condition'], $locationConfig['condition']);
    
    $params = array_merge($dateConfig['params'], $locationConfig['params'], [$jamMasuk]);
    
    $shiftFilter = "";
    if (!empty($shift) && !empty($location)) {
        $shiftFilter = getShiftTimeFilter($location, $shift);
        if (!empty($shiftFilter)) {
            $shiftFilter = "AND " . $shiftFilter;
        }
    }
    
    $sql = "$cte
            SELECT COUNT(*) AS TotalTerlambat
            FROM IO
            WHERE AttendanceType = 'IN'
            AND CAST(AttendanceTime AS TIME) > ?
            $shiftFilter";
    
    $row = executeQuery($sql, $params);
    return (int)($row['TotalTerlambat'] ?? 0);
}

function getTotalTidakHadirByDateRange($startDate = null, $endDate = null, ?string $location = null, ?string $shift = null): int
{
    $dateConfig = buildDateCondition($startDate, $endDate, 'amp2');
    $locationConfig = buildLocationCondition($location, 'k');
    
    $locationWhere = $locationConfig['condition'] ? "AND {$locationConfig['condition']}" : "";
    
    $joinKaryawanHadir = "";
    $locationWhereHadir = "";
    if ($locationConfig['condition']) {
        $joinKaryawanHadir = "INNER JOIN dbo.karyawan k2 ON amp2.barcode = k2.barcode";
        $locationConfigHadir = buildLocationCondition($location, 'k2');
        $locationWhereHadir = "AND {$locationConfigHadir['condition']}";
    }
    
    $params = [];
    
    $params = array_merge($params, $locationConfig['params']);

    $params = array_merge($params, $dateConfig['params']);
    
    if ($locationConfig['condition']) {
        $params = array_merge($params, $locationConfig['params']);
    }
    
    $sql = "WITH SemuaKaryawan2026 AS (
                SELECT DISTINCT k.barcode
                FROM dbo.karyawan k
                INNER JOIN dbo.AttendanceMachinePolling amp ON k.barcode = amp.barcode
                WHERE k.employee_status IN ('Permanent', 'Contract', 'Probationary')
                AND YEAR(amp.AttendanceDate) = 2026
                $locationWhere
                
                UNION
                
                SELECT DISTINCT amp.barcode
                FROM dbo.AttendanceMachinePolling amp
                LEFT JOIN dbo.karyawan k ON amp.barcode = k.barcode
                WHERE k.barcode IS NULL
                AND YEAR(amp.AttendanceDate) = 2026
            ),
            KaryawanHadirHariIni AS (
                SELECT DISTINCT amp2.barcode
                FROM dbo.AttendanceMachinePolling amp2
                $joinKaryawanHadir
                WHERE {$dateConfig['condition']}
                $locationWhereHadir
            )
            SELECT COUNT(*) AS TotalTidakHadir
            FROM SemuaKaryawan2026 sk
            WHERE sk.barcode NOT IN (SELECT barcode FROM KaryawanHadirHariIni)";
    
    $row = executeQuery($sql, $params);
    return (int)($row['TotalTidakHadir'] ?? 0);
}

function getKaryawanPalingBanyakTerlambat(int $limit = 5, ?string $startDate = null, ?string $endDate = null, ?string $jamMasuk = null, ?string $location = null, ?string $shift = null): array
{
    // Jika jamMasuk tidak ditentukan, gunakan berdasarkan location dan shift
    if (is_null($jamMasuk)) {
        $jamMasuk = getJamMasuk($location, $shift, $startDate);
    }
    
    $dateConfig = buildDateConditionWithYear($startDate, $endDate);
    $locationConfig = buildLocationCondition($location);
    $cte = getAttendanceCTE($dateConfig['condition'], $locationConfig['condition']);
    
    $params = array_merge(
        $dateConfig['params'],      // date params
        $locationConfig['params'],  // location params
        [$jamMasuk],               // jam masuk
        [$limit]                   // limit
    );
    
    // Tambahkan filter shift jika dipilih
    $shiftFilter = "";
    if (!empty($shift) && !empty($location)) {
        $shiftFilter = getShiftTimeFilter($location, $shift);
        if (!empty($shiftFilter)) {
            $shiftFilter = "AND " . $shiftFilter;
        }
    }
    
    $sql = "$cte,
            Terlambat AS (
                SELECT 
                    barcode,
                    COUNT(*) AS total_terlambat
                FROM IO
                WHERE AttendanceType = 'IN'
                AND CAST(AttendanceTime AS TIME) > ?
                $shiftFilter
                GROUP BY barcode
            )
            SELECT TOP (?)
                t.barcode,
                ISNULL(k.employee_name, 'TIDAK TERDAFTAR') AS employee_name,
                ISNULL(k.departement, '-') AS departement,
                t.total_terlambat
            FROM Terlambat t
            LEFT JOIN dbo.karyawan k ON t.barcode = k.barcode
            ORDER BY t.total_terlambat DESC";
    
    return fetchAllRows($sql, $params);
}

function getDepartemenPalingBanyakTerlambat(int $limit = 10, ?string $startDate = null, ?string $endDate = null, ?string $jamMasuk = null, ?string $location = null, ?string $shift = null): array
{
    if (is_null($jamMasuk)) {
        $jamMasuk = getJamMasuk($location, $shift, $startDate);
    }
    
    $dateConfig = buildDateConditionWithYear($startDate, $endDate);
    $locationConfig = buildLocationCondition($location);
    $cte = getAttendanceCTE($dateConfig['condition'], $locationConfig['condition']);
    
    $params = array_merge(
        $dateConfig['params'],  
        $locationConfig['params'],  
        [$jamMasuk],               
        [$limit]                  
    );
    
    $shiftFilter = "";
    if (!empty($shift) && !empty($location)) {
        $shiftFilter = getShiftTimeFilter($location, $shift);
        if (!empty($shiftFilter)) {
            $shiftFilter = "AND " . $shiftFilter;
        }
    }
    
    $sql = "$cte,
            Terlambat AS (
                SELECT 
                    barcode,
                    COUNT(*) AS total_terlambat
                FROM IO
                WHERE AttendanceType = 'IN'
                AND CAST(AttendanceTime AS TIME) > ?
                $shiftFilter
                GROUP BY barcode
            )
            SELECT TOP (?)
                ISNULL(k.departement, 'TIDAK TERDAFTAR') AS departement,
                COUNT(*) AS jumlah_karyawan,
                SUM(t.total_terlambat) AS total_keterlambatan
            FROM Terlambat t
            LEFT JOIN dbo.karyawan k ON t.barcode = k.barcode
            GROUP BY k.departement
            ORDER BY total_keterlambatan DESC";
    
    return fetchAllRows($sql, $params);
}


function getShiftTimeFilter(string $location, string $shift): string
{
    if ($location === 'KIP') {
        switch ($shift) {
            case 'Shift 1':
                return "CAST(AttendanceTime AS TIME) >= '05:00:00' AND CAST(AttendanceTime AS TIME) < '13:00:00'";
            case 'Shift 2':
                return "CAST(AttendanceTime AS TIME) >= '13:00:00' AND CAST(AttendanceTime AS TIME) < '21:00:00'";
            case 'Shift 3':
                return "(CAST(AttendanceTime AS TIME) >= '21:00:00' OR CAST(AttendanceTime AS TIME) < '05:00:00')";
            default:
                return "";
        }
    } elseif ($location === 'CKG' || $location === 'Cakung') {
        if ($shift === 'Shift 1') {
            return "CAST(AttendanceTime AS TIME) >= '05:00:00' AND CAST(AttendanceTime AS TIME) < '13:00:00'";
        }
    }
    
    return "";
}


function countEmployees(?string $startDate = null, ?string $endDate = null, ?string $location = null): int
{
    global $conn;

    if (is_null($startDate) && is_null($endDate) && is_null($location)) {
        $tsql = "SELECT COUNT(DISTINCT barcode) AS total
                 FROM (
                    SELECT k.barcode
                    FROM dbo.karyawan k
                    LEFT JOIN dbo.AttendanceMachinePolling amp ON k.barcode = amp.barcode
                    WHERE k.employee_status IN ('Permanent', 'Contract', 'Probationary')
                    AND YEAR(amp.AttendanceDate) = 2026
                    
                    UNION
                    
                    SELECT amp.barcode
                    FROM dbo.AttendanceMachinePolling amp
                    LEFT JOIN dbo.karyawan k ON amp.barcode = k.barcode
                    WHERE k.barcode IS NULL
                    AND YEAR(amp.AttendanceDate) = 2026
                 ) AS AllEmployees";

        $result = sqlsrv_query($conn, $tsql);
        if ($result === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        $data = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
        return (int)$data['total'];
    }

    $dateConfig = buildDateConditionWithYear($startDate, $endDate, 2026, 'amp');
    $locationConfig = buildLocationCondition($location, 'k');

    $locationWhereKaryawan = $locationConfig['condition'] ? "AND {$locationConfig['condition']}" : "";

    $params = array_merge(
        $dateConfig['params'],
        $locationConfig['params'],
        $dateConfig['params']
    );

    $sql = "SELECT COUNT(DISTINCT barcode) AS total
            FROM (
                SELECT k.barcode
                FROM dbo.karyawan k
                INNER JOIN dbo.AttendanceMachinePolling amp ON k.barcode = amp.barcode
                WHERE k.employee_status IN ('Permanent', 'Contract', 'Probationary')
                AND {$dateConfig['condition']}
                $locationWhereKaryawan
                
                UNION
                
                SELECT amp.barcode
                FROM dbo.AttendanceMachinePolling amp
                LEFT JOIN dbo.karyawan k ON amp.barcode = k.barcode
                WHERE k.barcode IS NULL
                AND {$dateConfig['condition']}
            ) AS AllEmployees";

    $row = executeQuery($sql, $params);
    return (int)($row['total'] ?? 0);
}

function countPresentEmployeesToday(): int
{
    global $conn;

    $today = date('Y-m-d');
    $tsql  = "SELECT COUNT(DISTINCT barcode) AS total_present
            FROM dbo.AttendanceMachinePolling
            WHERE CAST(AttendanceDate AS DATE) = ?";

    $params = [$today];
    $stmt   = sqlsrv_query($conn, $tsql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);

    return (int)$row['total_present'];
}

function countPresentEmployeesThisWeek(): int
{
    global $conn;

    $startOfWeek = date('Y-m-d', strtotime('monday this week'));
    $endOfWeek   = date('Y-m-d', strtotime('sunday this week'));

    $tsql  = "SELECT COUNT(DISTINCT employee_id) AS total_present
            FROM attendances
            WHERE CAST(attendance_date AS DATE) BETWEEN ? AND ?";

    $params = [$startOfWeek, $endOfWeek];
    $stmt   = sqlsrv_query($conn, $tsql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);

    return (int)$row['total_present'];
}

function countPresentEmployeesThisMonth(): int
{
    global $conn;

    $startOfMonth = date('Y-m-01');
    $endOfMonth   = date('Y-m-t');

    $tsql  = "SELECT COUNT(DISTINCT employee_id) AS total_present
            FROM attendances
            WHERE CAST(attendance_date AS DATE) BETWEEN ? AND ?";

    $params = [$startOfMonth, $endOfMonth];
    $stmt   = sqlsrv_query($conn, $tsql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);

    return (int)$row['total_present'];
}

function getTrendKehadiran7Hari(): array
{
    global $conn;

    $sql = "WITH Last7Days AS (
                SELECT CAST(DATEADD(DAY, -6, GETDATE()) AS DATE) AS Date
                UNION ALL SELECT CAST(DATEADD(DAY, -5, GETDATE()) AS DATE)
                UNION ALL SELECT CAST(DATEADD(DAY, -4, GETDATE()) AS DATE)
                UNION ALL SELECT CAST(DATEADD(DAY, -3, GETDATE()) AS DATE)
                UNION ALL SELECT CAST(DATEADD(DAY, -2, GETDATE()) AS DATE)
                UNION ALL SELECT CAST(DATEADD(DAY, -1, GETDATE()) AS DATE)
                UNION ALL SELECT CAST(GETDATE() AS DATE)
            ),
            SemuaKaryawan2026 AS (
                SELECT DISTINCT k.barcode
                FROM dbo.karyawan k
                INNER JOIN dbo.AttendanceMachinePolling amp ON k.barcode = amp.barcode
                WHERE k.employee_status IN ('Permanent', 'Contract', 'Probationary')
                AND YEAR(amp.AttendanceDate) = 2026
                
                UNION
                
                SELECT DISTINCT amp.barcode
                FROM dbo.AttendanceMachinePolling amp
                LEFT JOIN dbo.karyawan k ON amp.barcode = k.barcode
                WHERE k.barcode IS NULL
                AND YEAR(amp.AttendanceDate) = 2026
            ),
            KehadiranPerHari AS (
                SELECT 
                    l7.Date,
                    COUNT(DISTINCT amp.barcode) AS TotalHadir
                FROM Last7Days l7
                LEFT JOIN dbo.AttendanceMachinePolling amp ON CAST(amp.AttendanceDate AS DATE) = l7.Date
                GROUP BY l7.Date
            ),
            TotalKaryawan AS (
                SELECT COUNT(*) AS Total FROM SemuaKaryawan2026
            )
            SELECT 
                kph.Date,
                FORMAT(kph.Date, 'dd MMM', 'id-ID') AS Tanggal,
                ISNULL(kph.TotalHadir, 0) AS Hadir,
                (SELECT Total FROM TotalKaryawan) - ISNULL(kph.TotalHadir, 0) AS TidakHadir
            FROM KehadiranPerHari kph
            ORDER BY kph.Date";

    $stmt = sqlsrv_query($conn, $sql);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $data = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $tanggal = $row['Date'];
        if ($tanggal instanceof DateTime) {
            $tanggal = $tanggal->format('Y-m-d');
        }

        $data[] = [
            'tanggal' => $row['Tanggal'],
            'hadir' => (int)$row['Hadir'],
            'tidak_hadir' => (int)$row['TidakHadir']
        ];
    }

    sqlsrv_free_stmt($stmt);

    return $data;
}

function getKaryawanHadir($startDate = null, $endDate = null, ?string $location = null, ?string $shift = null): array
{
    $dateConfig = buildDateCondition($startDate, $endDate);
    $locationConfig = buildLocationCondition($location);
    $cte = getAttendanceCTE($dateConfig['condition'], $locationConfig['condition']);
    
    $params = array_merge($dateConfig['params'], $locationConfig['params']);
    
    $shiftFilter = "";
    if (!empty($shift) && !empty($location)) {
        $shiftFilter = getShiftTimeFilter($location, $shift);
        if (!empty($shiftFilter)) {
            $shiftFilter = "AND " . $shiftFilter;
        }
    }
    
    $sql = "$cte,
            AttendanceData AS (
                SELECT 
                    barcode,
                    AttendanceDate,
                    MAX(CASE WHEN AttendanceType = 'IN' THEN AttendanceTime END) AS TimeIn,
                    MAX(CASE WHEN AttendanceType = 'OUT' THEN AttendanceTime END) AS TimeOut
                FROM IO
                WHERE AttendanceType IN ('IN', 'OUT')
                $shiftFilter
                GROUP BY barcode, AttendanceDate
            )
            SELECT 
                a.barcode,
                a.AttendanceDate,
                a.TimeIn,
                a.TimeOut,
                ISNULL(k.employee_name, 'TIDAK TERDAFTAR') AS employee_name,
                ISNULL(k.departement, '-') AS departement,
                ISNULL(k.location, '-') AS location
            FROM AttendanceData a
            LEFT JOIN dbo.karyawan k ON a.barcode = k.barcode
            WHERE a.TimeIn IS NOT NULL
            ORDER BY a.AttendanceDate DESC, a.TimeIn ASC";
    
    return fetchAllRows($sql, $params);
}

function getKaryawanTerlambat($startDate = null, $endDate = null, ?string $location = null, ?string $shift = null): array
{
    $jamMasuk = getJamMasuk($location, $shift, $startDate);
    
    $dateConfig = buildDateCondition($startDate, $endDate);
    $locationConfig = buildLocationCondition($location);
    $cte = getAttendanceCTE($dateConfig['condition'], $locationConfig['condition']);
    
    $params = array_merge($dateConfig['params'], $locationConfig['params'], [$jamMasuk]);
    
    $shiftFilter = "";
    if (!empty($shift) && !empty($location)) {
        $shiftFilter = getShiftTimeFilter($location, $shift);
        if (!empty($shiftFilter)) {
            $shiftFilter = "AND " . $shiftFilter;
        }
    }
    
    $sql = "$cte
            SELECT 
                io.barcode,
                io.AttendanceDate,
                io.AttendanceTime AS TimeIn,
                ISNULL(k.employee_name, 'TIDAK TERDAFTAR') AS employee_name,
                ISNULL(k.departement, '-') AS departement,
                ISNULL(k.location, '-') AS location
            FROM IO io
            LEFT JOIN dbo.karyawan k ON io.barcode = k.barcode
            WHERE io.AttendanceType = 'IN'
            AND CAST(io.AttendanceTime AS TIME) > ?
            $shiftFilter
            ORDER BY io.AttendanceDate DESC, io.AttendanceTime DESC";
    
    return fetchAllRows($sql, $params);
}

function countEmployeesDetail($startDate = null, $endDate = null, ?string $location = null, ?string $shift = null): int
{
    global $conn;
    
    $dateConfig = buildDateCondition($startDate, $endDate, 'amp');
    $locationConfig = buildLocationCondition($location, 'k');
    
    $whereConditions = [];
    $params = [];
    
    $whereConditions[] = "k.employee_status IN ('Permanent', 'Contract', 'Probationary')";
    
    if ($locationConfig['condition']) {
        $whereConditions[] = $locationConfig['condition'];
        $params = array_merge($params, $locationConfig['params']);
    }
    
    $whereConditions[] = $dateConfig['condition'];
    $params = array_merge($params, $dateConfig['params']);
    
    $whereClause = implode(' AND ', $whereConditions);
    
    $sql = "SELECT COUNT(DISTINCT k.barcode) AS total
            FROM dbo.karyawan k
            INNER JOIN dbo.AttendanceMachinePolling amp ON k.barcode = amp.barcode
            WHERE $whereClause";
    
    $row = executeQuery($sql, $params);
    return (int)($row['total'] ?? 0);
}