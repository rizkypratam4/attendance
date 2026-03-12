<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Location;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class EmployeeImportService
{
    public function import(UploadedFile $file): array
    {
        $ext = strtolower($file->getClientOriginalExtension());

        if (in_array($ext, ['xls', 'xlsx'])) {
            return $this->importExcel($file);
        } elseif ($ext === 'csv') {
            return $this->importCsv($file);
        }

        throw new \Exception('Unsupported file type: ' . $ext);
    }

    private function importExcel(UploadedFile $file): array
    {
        $success = 0;
        $errors = [];
        $rowIndex = 1;

        (new FastExcel)->import($file->getRealPath(), function ($row) use (&$success, &$errors, &$rowIndex) {
            $rowIndex++;

            $assoc = array_change_key_case($row, CASE_LOWER);

            $result = $this->processRow($rowIndex, $assoc);
            if ($result === true) {
                $success++;
            } elseif ($result !== null) {
                $errors[] = $result;
            }
        });

        return ['success' => $success, 'errors' => $errors];
    }

    private function importCsv(UploadedFile $file): array
    {
        $success = 0;
        $errors = [];
        $header = null;
        $rowIndex = 0;

        $handle = fopen($file->getRealPath(), 'r');
        if ($handle === false) {
            throw new \Exception('Cannot open file.');
        }

        while (($row = fgetcsv($handle)) !== false) {
            $rowIndex++;

            if ($rowIndex === 1) {
                $header = array_map(fn($h) => strtolower(trim($h)), $row);
                continue;
            }

            $result = $this->processRow($row, $header, $rowIndex);
            if ($result === true) {
                $success++;
            } elseif ($result !== null) {
                $errors[] = $result;
            }
        }

        fclose($handle);

        return ['success' => $success, 'errors' => $errors];
    }

    private function processRow(int $rowIndex, array $assoc): true|string|null
    {
        if (empty(trim($assoc['nik'] ?? '')) || empty(trim($assoc['name'] ?? ''))) {
            return null;
        }

        try {
            DB::beginTransaction();

            $branchId = null;
            if (!empty($assoc['branch'])) {
                $branchId = Branch::firstOrCreate(
                    ['name' => trim($assoc['branch'])],
                    ['is_active' => true]
                )->id;
            }

            $departmentId = null;
            if (!empty($assoc['department'])) {
                $departmentId = Department::firstOrCreate(
                    ['name' => trim($assoc['department'])]
                )->id;
            }

            $locationId = null;
            if (!empty($assoc['location'])) {
                $locationId = Location::firstOrCreate(
                    ['name' => trim($assoc['location'])]
                )->id;
            }

            Employee::updateOrCreate(
                ['nik' => trim($assoc['nik'])],
                [
                    'name'            => trim($assoc['name']),
                    'machine_barcode' => $assoc['machine_barcode'] ?? null,
                    'branch_id'       => $branchId,
                    'department_id'   => $departmentId,
                    'position'        => $assoc['position'] ?? null,
                    'location_id'     => $locationId,
                    'employee_status' => $assoc['employee_status'] ?? null,
                    'is_active'       => $this->toBool($assoc['is_active'] ?? '1'),
                ]
            );

            DB::commit();
            return true;

        } catch (\Throwable $e) {
            DB::rollBack();
            return "Row {$rowIndex}: " . $e->getMessage();
        }
    }

    private function toBool($val): int
    {
        if (is_numeric($val)) return intval($val) ? 1 : 0;
        $v = strtolower(trim((string)$val));
        return in_array($v, ['1', 'true', 'yes', 'y', 'active']) ? 1 : 0;
    }
}
