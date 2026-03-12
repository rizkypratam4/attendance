<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\EmployeeShiftAssignment;
use App\Models\ShiftCode;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeeShiftAssignmentService
{
    /**
     * Retrieve all assignments with related data.
     */
    public function getAll()
    {
        return EmployeeShiftAssignment::with(['employee.department', 'shiftCode.shift'])->get();
    }

    /**
     * Create a new assignment using validated request data.
     */
    public function createAssignment(array $data): EmployeeShiftAssignment
    {
        // always record who created the assignment
        if (!array_key_exists('created_by', $data)) {
            $data['created_by'] = auth()->id();
        }
        $assignment = EmployeeShiftAssignment::create($data);
        Alert::success('Created!', 'Assignment has been added.');
        return $assignment;
    }

    /**
     * Update an existing assignment.
     */
    public function updateAssignment(EmployeeShiftAssignment $assignment, array $data): EmployeeShiftAssignment
    {
        $assignment->update($data);
        Alert::success('Updated!', 'Assignment has been updated.');
        return $assignment->fresh();
    }

    /**
     * Delete an assignment.
     */
    public function deleteAssignment(EmployeeShiftAssignment $assignment): void
    {
        $assignment->delete();
        Alert::success('Deleted!', 'Assignment has been removed.');
    }

    /**
     * Import assignments from spreadsheet/csv file.
     */
    public function import(UploadedFile $file): array
    {
        $ext = strtolower($file->getClientOriginalExtension());

        if (in_array($ext, ['xls', 'xlsx'])) {
            if (!class_exists('\\PhpOffice\\PhpSpreadsheet\\IOFactory')) {
                throw new \Exception('PhpSpreadsheet is required to import Excel files. Run: composer require phpoffice/phpspreadsheet');
            }

            $spreadsheet = IOFactory::load($file->getRealPath());
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
        $required = ['nik', 'shift_code'];
        $missing = array_diff($required, $header);
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
                $emp = null;
                if (!empty($assoc['nik'])) {
                    $emp = Employee::where('nik', trim($assoc['nik']))->first();
                }
                if (!$emp && !empty($assoc['employee_id'])) {
                    $emp = Employee::find($assoc['employee_id']);
                }
                if (!$emp) {
                    throw new \Exception('Employee not found (' . ($assoc['nik'] ?? $assoc['employee_id'] ?? '').')');
                }

                $shiftCode = null;
                if (!empty($assoc['shift_code'])) {
                    $shiftCode = ShiftCode::where('code', trim($assoc['shift_code']))->first();
                }
                if (!$shiftCode && !empty($assoc['shift_name'])) {
                    $shiftCode = ShiftCode::where('name', trim($assoc['shift_name']))->first();
                }
                if (!$shiftCode) {
                    throw new \Exception('Shift code not found (' . ($assoc['shift_code'] ?? $assoc['shift_name'] ?? '') . ')');
                }

                $assignmentData = [
                    'employee_id'   => $emp->id,
                    'shift_code_id' => $shiftCode->id,
                    'effective_date'=> $assoc['effective_date'] ?? null,
                    'end_date'      => $assoc['end_date'] ?? null,
                    'created_by'    => auth()->id(),
                ];

                EmployeeShiftAssignment::create($assignmentData);
                DB::commit();
                $success++;
            } catch (\Throwable $e) {
                DB::rollBack();
                $errors[] = "Row " . ($i + 1) . ": " . $e->getMessage();
            }
        }

        return ['success' => $success, 'errors' => $errors];
    }
}
