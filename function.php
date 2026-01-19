<?php

require __DIR__ . "/vendor/autoload.php";
require __DIR__ . "/config/database.php";

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

# MSSQL Connection
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
    $conn = openConnectionMSSQL();

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
    $conn = openConnectionMSSQL();
    $tsql    = "SELECT * FROM dbo.karyawan ORDER BY employee_name ASC";
    $result = sqlsrv_query($conn, $tsql);

    $employees = [];
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $employees[] = $row;
    }

    return $employees;
}


# Attendance Functions
function getAttendances($offset = 0, $limit = 5000)
{
    $conn = openConnectionMSSQL();
    $tsql = "WITH Dedup AS (
            SELECT
                a.barcode,
                a.AttendanceDate,
                a.AttendanceTime,
                ROW_NUMBER() OVER (
                    PARTITION BY a.barcode, a.AttendanceDate, a.AttendanceTime
                    ORDER BY a.barcode
                ) AS rn
            FROM dbo.AttendanceMachinePolling a
        )
        SELECT
            d.barcode,
            ISNULL(e.employee_name, 'TIDAK TERDAFTAR') AS employee_name,
            d.AttendanceDate,
            d.AttendanceTime,
            CASE
                WHEN d.AttendanceTime = MIN(d.AttendanceTime)
                     OVER (PARTITION BY d.barcode, d.AttendanceDate)
                    THEN 'IN'
                WHEN d.AttendanceTime = MAX(d.AttendanceTime)
                     OVER (PARTITION BY d.barcode, d.AttendanceDate)
                    THEN 'OUT'
                ELSE NULL
            END AS AttendanceType
        FROM Dedup d
        LEFT JOIN dbo.karyawan e
            ON d.barcode = e.barcode
        WHERE d.rn = 1
        ORDER BY
            d.AttendanceDate DESC,
            d.AttendanceTime DESC,
            e.employee_name DESC
        OFFSET ? ROWS FETCH NEXT ? ROWS ONLY
    ";

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
function countEmployees(): int
{
    $conn = openConnectionMSSQL();
    $tsql   = "SELECT COUNT(*) AS total FROM dbo.karyawan";
    $result = sqlsrv_query($conn, $tsql);
    if ($result === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    $data   = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    
    return (int)$data['total'];
}

function countPresentEmployeesToday(): int
{
    $conn = openConnectionMSSQL();

    $today = date('Y-m-d');
    $tsql  = "SELECT COUNT(DISTINCT employee_id) AS total_present
            FROM attendances
            WHERE CAST(attendance_date AS DATE) = ?";

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
    $conn = openConnectionMSSQL();

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
    $conn = openConnectionMSSQL();

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
