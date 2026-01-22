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

# Import Excel Function
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
                    'date_of_birth'
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

                if (in_array($column, ['bpjs_tk', 'bpjs_health', 'ktp_number'])) {
                    $value = ltrim((string)$value, "'");
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
    $tsql    = "SELECT * FROM dbo.karyawan ORDER BY employee_name ASC";
    $result = sqlsrv_query($conn, $tsql);

    $employees = [];
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $employees[] = $row;
    }

    return $employees;
}

# Attendance Functions
function getAttendances($offset = 0, $limit = 500): array
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
function getTotalHadirHariIni(): int
{
    global $conn;
    $sql = "WITH Base AS (
                SELECT DISTINCT
                    barcode,
                    AttendanceDate,
                    AttendanceTime
                FROM dbo.AttendanceMachinePolling
                WHERE AttendanceDate = CAST(GETDATE() AS DATE)
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
            SELECT COUNT(*) AS TotalMasuk
            FROM IO
            WHERE AttendanceType = 'IN'";

    $stmt = sqlsrv_query($conn, $sql);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmt);

    return (int)($row['TotalMasuk'] ?? 0);
}


function getTotalHadirByDateRange($startDate = null, $endDate = null): int
{
    global $conn;
    
    if (is_null($startDate) && is_null($endDate)) {
        $dateCondition = "AttendanceDate = CAST(GETDATE() AS DATE)";
        $params = [];
    }

    elseif (!is_null($startDate) && is_null($endDate)) {
        $dateCondition = "AttendanceDate = ?";
        $params = [$startDate];
    }

    else {
        $dateCondition = "AttendanceDate BETWEEN ? AND ?";
        $params = [$startDate, $endDate];
    }
    
    $sql = "WITH Base AS (
                SELECT DISTINCT
                    barcode,
                    AttendanceDate,
                    AttendanceTime
                FROM dbo.AttendanceMachinePolling
                WHERE $dateCondition
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
            SELECT COUNT(*) AS TotalMasuk
            FROM IO
            WHERE AttendanceType = 'IN'";

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmt);

    return (int)($row['TotalMasuk'] ?? 0);
}

function getTotalTerlambatHariIni(string $jamMasuk = '07:30:00'): int
{
    global $conn;
    $sql = "WITH Base AS (
                SELECT DISTINCT
                    barcode,
                    AttendanceDate,
                    AttendanceTime
                FROM dbo.AttendanceMachinePolling
                WHERE AttendanceDate = CAST(GETDATE() AS DATE)
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
            SELECT COUNT(*) AS TotalTerlambat
            FROM IO
            WHERE AttendanceType = 'IN'
            AND CAST(AttendanceTime AS TIME) > ?";  

    $stmt = sqlsrv_query($conn, $sql, [$jamMasuk]);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmt);

    return (int)($row['TotalTerlambat'] ?? 0);
}

function getTotalTerlambatByDateRange($startDate = null, $endDate = null, string $jamMasuk = '07:30:00'): int
{
    global $conn;
    
    if (is_null($startDate) && is_null($endDate)) {
        $dateCondition = "AttendanceDate = CAST(GETDATE() AS DATE)";
        $params = [$jamMasuk];
    } 

    elseif (!is_null($startDate) && is_null($endDate)) {
        $dateCondition = "AttendanceDate = ?";
        $params = [$startDate, $jamMasuk];
    }

    else {
        $dateCondition = "AttendanceDate BETWEEN ? AND ?";
        $params = [$startDate, $endDate, $jamMasuk];
    }
    
    $sql = "WITH Base AS (
                SELECT DISTINCT
                    barcode,
                    AttendanceDate,
                    AttendanceTime
                FROM dbo.AttendanceMachinePolling
                WHERE $dateCondition
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
            SELECT COUNT(*) AS TotalTerlambat
            FROM IO
            WHERE AttendanceType = 'IN'
            AND CAST(AttendanceTime AS TIME) > ?";

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmt);

    return (int)($row['TotalTerlambat'] ?? 0);
}

function getTotalTidakHadirByDateRange($startDate = null, $endDate = null): int
{
    global $conn;
    
    if (is_null($startDate) && is_null($endDate)) {
        $dateCondition = "amp2.AttendanceDate = CAST(GETDATE() AS DATE)";
        $params = [];
    } 

    elseif (!is_null($startDate) && is_null($endDate)) {
        $dateCondition = "amp2.AttendanceDate = ?";
        $params = [$startDate];
    }

    else {
        $dateCondition = "amp2.AttendanceDate = ?";
        $params = [$startDate];
    }
    
    $sql = "WITH SemuaKaryawan2026 AS (
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
            KaryawanHadirHariIni AS (
                SELECT DISTINCT amp2.barcode
                FROM dbo.AttendanceMachinePolling amp2
                WHERE $dateCondition
            )
            SELECT COUNT(*) AS TotalTidakHadir
            FROM SemuaKaryawan2026 sk
            WHERE sk.barcode NOT IN (SELECT barcode FROM KaryawanHadirHariIni)";

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($stmt);

    return (int)($row['TotalTidakHadir'] ?? 0);
}

function countEmployees(): int
{
    global $conn;

    $tsql = "SELECT COUNT(DISTINCT barcode) AS total
             FROM (
                SELECT k.barcode
                FROM dbo.karyawan k
                INNER JOIN dbo.AttendanceMachinePolling amp ON k.barcode = amp.barcode
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
