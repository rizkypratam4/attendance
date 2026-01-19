<?php

require __DIR__ . "/vendor/autoload.php";
require __DIR__ . "/config/database.php";

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;


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

    /* BEGIN TRANSACTION */
    if (!sqlsrv_begin_transaction($conn)) {
        throw new Exception(print_r(sqlsrv_errors(), true));
    }

    try {
        foreach ($sheetData as $index => $row) {
            if ($skipHeader && $index === 0) continue;

            // Skip baris kosong
            if (!isset($row[1]) || trim((string)$row[1]) === '') {
                continue;
            }

            $values = [];

            foreach ($columnMap as $column => $excelIndex) {
                $value = $row[$excelIndex] ?? null;

                // Mandatory field
                if ($column === 'account_number' && empty($value)) {
                    throw new Exception(
                        "Employee ID kosong di baris Excel ke " . ($index + 1)
                    );
                }

                // Date handling
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

                // Remove leading quote for numbers stored as text
                if (in_array($column, ['bpjs_tk', 'bpjs_health', 'ktp_number'])) {
                    $value = ltrim((string)$value, "'");
                }

                $values[] = ($value === '' ? null : $value);
            }

            // SQL Server parameters (BY REFERENCE!)
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

function getEmployees(): array
{
    $conn = openConnectionMSSQL();
    $tsql    = "SELECT account_number, employee_name, branch, position, employee_status FROM dbo.karyawan ORDER BY employee_name ASC";
    $result = sqlsrv_query($conn, $tsql);

    $employees = [];
    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $employees[] = $row;
    }

    return $employees;
}

function getAttendances(mysqli $conn): array
{
    $sql    ="SELECT a.id, e.name, a.attendance_date, a.attendance_time, a.attendance_type
            FROM attendances a
            JOIN employees e ON a.employee_id = e.id
            ORDER BY a.employee_id DESC, a.attendance_date DESC, a.attendance_time DESC";
    $result = $conn->query($sql);

    $attendances = [];
    while ($row = $result->fetch_assoc()) {
        $attendances[] = $row;
    }

    return $attendances;
}


function countEmployees(mysqli $conn): int
{
    $result = $conn->query("SELECT COUNT(*) AS total FROM employees");
    $data   = $result->fetch_assoc();
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
