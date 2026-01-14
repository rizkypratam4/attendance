<?php

require __DIR__ . "/vendor/autoload.php";
require __DIR__ . "/config/database.php";

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;


function importExcel(
    mysqli $conn,
    string $table,
    array $columnMap,
    string $fileInputName = 'file_excel',
    bool $skipHeader = true
) {
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

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare($sql);

        foreach ($sheetData as $index => $row) {
            if ($skipHeader && $index === 0) continue;

            if (!isset($row[1]) || trim((string)$row[1]) === '') {
                continue;
            }

            $values = [];
            $types  = '';

            foreach ($columnMap as $column => $excelIndex) {
                $value = $row[$excelIndex] ?? null;

                if ($column === 'employee_id' && empty($value)) {
                    throw new Exception(
                        "Employee ID kosong di baris Excel ke " . ($index + 1)
                    );
                }

                if (
                    $column === 'join_date' ||
                    $column === 'effective_date' ||
                    $column === 'end_effective_date' ||
                    $column === 'date_of_birth'
                ) {
                    if (empty($value)) {
                        $value = null;
                    } elseif (is_numeric($value)) {
                        $value = Date::excelToDateTimeObject($value)->format('Y-m-d');
                    } else {
                        $dt = DateTime::createFromFormat('d-m-Y', $value);
                        $value = $dt ? $dt->format('Y-m-d') : null;
                    }
                }

                if ($column === 'bpjs_tk' || $column === 'bpjs_health' || $column === 'ktp_number') {
                    $value = ltrim((string)$value, "'");
                }

                if ($value === '' || $value === null) {
                    $values[] = null;
                    $types   .= 's';
                } elseif (is_numeric($value)) {
                    $values[] = (int) $value;
                    $types   .= 'i';
                } else {
                    $values[] = trim((string) $value);
                    $types   .= 's';
                }
            }

            $stmt->bind_param($types, ...$values);
            $stmt->execute();
        }

        $conn->commit();
        $stmt->close();

        return "Import ke tabel {$table} berhasil";
    } catch (Exception $e) {
        $conn->rollback();
        throw $e;
    }
}

function getEmployees(mysqli $conn): array
{
    $sql    = "SELECT id, name, branch, position, employee_status FROM employees ORDER BY name ASC";
    $result = $conn->query($sql);

    $employees = [];
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }

    return $employees;
}

function getAttendances(mysqli $conn): array
{
    $sql    = "SELECT a.id, e.name, a.attendance_date, a.attendance_time, a.attendance_type
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
