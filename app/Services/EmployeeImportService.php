<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Location;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class EmployeeImportService
{
    public function import(UploadedFile $file): array
    {
        $ext = strtolower($file->getClientOriginalExtension());

        if (in_array($ext, ['xls', 'xlsx'])) {
            if (!class_exists('\\PhpOffice\\PhpSpreadsheet\\IOFactory')) {
                throw new \Exception('PhpSpreadsheet is required to import Excel files. Run: composer require phpoffice/phpspreadsheet');
            }

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $dataRows = [];
            foreach ($rows as $r) {
                $dataRows[] = array_values($r);
            }
        } elseif ($ext === 'csv') {
            $dataRows = [];
            if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
                while (($row = fgetcsv($handle)) !== false) {
                    $dataRows[] = $row;
                }
                fclose($handle);
            }
        } else {
            throw new \Exception('Unsupported file type: ' . $ext);
        }

        if (count($dataRows) < 2) {
            return ['success' => 0, 'errors' => ['File contains no data']];
        }

        $header = array_map(fn($h) => strtolower(trim($h)), $dataRows[0]);
        $allowed = ['name','nik','machine_barcode','branch','department','position','location','title','employee_status','is_active'];

        $missing = array_diff(['name','nik'], $header);
        if (!empty($missing)) {
            return ['success' => 0, 'errors' => ['Missing required columns: ' . implode(', ', $missing)]];
        }

        $success = 0;
        $errors = [];

        for ($i = 1; $i < count($dataRows); $i++) {
            $row = $dataRows[$i];
            $row = array_map(fn($v) => is_null($v) ? '' : $v, $row);
            $assoc = [];
            for ($c = 0; $c < count($header); $c++) {
                $assoc[$header[$c]] = $row[$c] ?? null;
            }

            try {
                DB::beginTransaction();
                $branchId = null;
                if (!empty($assoc['branch'])) {
                    $branch = Branch::firstOrCreate(['name' => trim($assoc['branch'])], ['is_active' => true]);
                    $branchId = $branch->id;
                }

                $departmentId = null;
                if (!empty($assoc['department'])) {
                    $dept = Department::firstOrCreate(['name' => trim($assoc['department'])]);
                    $departmentId = $dept->id;
                }

                $locationId = null;
                if (!empty($assoc['location'])) {
                    $loc = Location::firstOrCreate(['name' => trim($assoc['location'])]);
                    $locationId = $loc->id;
                }

                $employeeData = [
                    'name' => $assoc['name'] ?? null,
                    'nik' => $assoc['nik'] ?? null,
                    'machine_barcode' => $assoc['machine_barcode'] ?? null,
                    'branch_id' => $branchId,
                    'department_id' => $departmentId,
                    'position' => $assoc['position'] ?? null,
                    'location_id' => $locationId,
                    'title' => $assoc['title'] ?? null,
                    'employee_status' => $assoc['employee_status'] ?? null,
                    'is_active' => $this->toBool($assoc['is_active'] ?? '1'),
                ];

                Employee::create($employeeData);

                DB::commit();
                $success++;
            } catch (\Throwable $e) {
                DB::rollBack();
                $errors[] = "Row " . ($i + 1) . ": " . $e->getMessage();
            }
        }

        return ['success' => $success, 'errors' => $errors];
    }

    private function toBool($val): int
    {
        if (is_numeric($val)) return intval($val) ? 1 : 0;
        $v = strtolower(trim((string)$val));
        return in_array($v, ['1','true','yes','y','active']) ? 1 : 0;
    }
}
