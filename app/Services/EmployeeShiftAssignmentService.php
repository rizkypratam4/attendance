<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\EmployeeShiftAssignment;
use App\Models\ShiftCode;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class EmployeeShiftAssignmentService
{
    public function getAll(int $perPage = 20)
    {
        return EmployeeShiftAssignment::with(['employee', 'shiftCode.shift'])
            ->paginate($perPage);
    }

    public function createAssignment(array $data): EmployeeShiftAssignment
    {
        $data['created_by'] ??= auth()->id();
        return EmployeeShiftAssignment::create($data);
    }

    public function updateAssignment(EmployeeShiftAssignment $assignment, array $data): EmployeeShiftAssignment
    {
        $assignment->update($data);
        return $assignment->fresh();
    }

    public function deleteAssignment(EmployeeShiftAssignment $assignment): void
    {
        $assignment->delete();
    }

    public function deleteAll(): int
    {
        return EmployeeShiftAssignment::query()->delete();
    }

    /**
     * Import assignments dari Excel.
     * Kolom wajib: Employee Name, Shift Code, Date
     * Kolom opsional: No (diabaikan)
     */
    public function import(UploadedFile $file): array
    {
        $rows = $this->readFile($file);

        if (empty($rows)) {
            return ['success' => 0, 'errors' => ['File kosong.']];
        }

        // Cari baris header secara otomatis
        $headerRowIndex = null;
        foreach ($rows as $index => $row) {
            $normalized = array_map(
                fn($h) => strtolower(trim(str_replace(' ', '_', $h ?? ''))),
                $row
            );
            if (in_array('employee_name', $normalized) && in_array('shift_code', $normalized)) {
                $headerRowIndex = $index;
                break;
            }
        }

        if ($headerRowIndex === null) {
            return ['success' => 0, 'errors' => ['Header kolom tidak ditemukan. Pastikan ada kolom: Employee Name, Shift Code, Date']];
        }

        $header = array_map(
            fn($h) => strtolower(trim(str_replace(' ', '_', $h ?? ''))),
            $rows[$headerRowIndex]
        );

        $dataRows = array_slice($rows, $headerRowIndex + 1);

        if (empty($dataRows)) {
            return ['success' => 0, 'errors' => ['File tidak memiliki data.']];
        }

        // Cari index kolom berdasarkan nama — aman meski ada kolom 'No' di awal
        $empIndex  = array_search('employee_name', $header);
        $scIndex   = array_search('shift_code', $header);
        $dateIndex = array_search('date', $header);

        if ($empIndex === false || $scIndex === false || $dateIndex === false) {
            return ['success' => 0, 'errors' => [
                'Kolom tidak ditemukan. Pastikan header Excel memiliki: Employee Name, Shift Code, Date'
            ]];
        }

        $success        = 0;
        $errors         = [];
        $shiftCodeCache = ShiftCode::pluck('id', 'code')->toArray();

        foreach ($dataRows as $i => $row) {
            $row = array_map(fn($v) => is_null($v) ? '' : trim((string) $v), $row);

            // Ambil nilai berdasarkan index kolom
            $employeeName = $row[$empIndex]  ?? '';
            $shiftCode    = $row[$scIndex]   ?? '';
            $dateRaw      = $row[$dateIndex] ?? '';

            // Skip baris kosong
            if (empty($employeeName) && empty($shiftCode)) {
                continue;
            }

            try {
                DB::beginTransaction();

                // Cari employee by name
                $employee = Employee::where('name', $employeeName)->first();
                if (!$employee) {
                    throw new \Exception("Karyawan '{$employeeName}' tidak ditemukan.");
                }

                // Cari shift code
                $shiftCodeId = $shiftCodeCache[$shiftCode] ?? null;
                if (!$shiftCodeId) {
                    throw new \Exception("Shift code '{$shiftCode}' tidak ditemukan.");
                }

                // Parse tanggal
                $parsedDate = $this->parseDate($dateRaw);
                if (!$parsedDate) {
                    throw new \Exception("Format tanggal tidak valid: '{$dateRaw}'");
                }

                EmployeeShiftAssignment::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'date'        => $parsedDate,
                    ],
                    [
                        'shift_code_id' => $shiftCodeId,
                        'created_by'    => auth()->id(),
                    ]
                );

                DB::commit();
                $success++;

            } catch (\Throwable $e) {
                DB::rollBack();
                $errors[] = "Baris " . ($i + $headerRowIndex + 2) . ": " . $e->getMessage();
                Log::warning('ShiftAssignmentImport: gagal import baris', [
                    'row'   => $i + $headerRowIndex + 2,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return ['success' => $success, 'errors' => $errors];
    }

    // ==========================================
    // PRIVATE HELPERS
    // ==========================================

    private function readFile(UploadedFile $file): array
    {
        $ext = strtolower($file->getClientOriginalExtension());

        if (in_array($ext, ['xls', 'xlsx'])) {
            if (!class_exists('\\PhpOffice\\PhpSpreadsheet\\IOFactory')) {
                throw new \Exception('PhpSpreadsheet belum terinstall. Jalankan: composer require phpoffice/phpspreadsheet');
            }

            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = [];

            foreach ($sheet->getRowIterator() as $row) {
                $rowData  = [];
                $cellIter = $row->getCellIterator();
                $cellIter->setIterateOnlyExistingCells(false);

                foreach ($cellIter as $cell) {
                    $value = $cell->getValue();
                    if (ExcelDate::isDateTime($cell) && is_numeric($value)) {
                        $rowData[] = ExcelDate::excelToDateTimeObject($value)->format('d/m/Y');
                    } else {
                        $rowData[] = $cell->getFormattedValue();
                    }
                }

                $rows[] = $rowData;
            }

            return $rows;
        }

        if ($ext === 'csv') {
            $rows = [];
            if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
                while (($row = fgetcsv($handle)) !== false) {
                    $rows[] = $row;
                }
                fclose($handle);
            }
            return $rows;
        }

        throw new \Exception('Tipe file tidak didukung: ' . $ext . '. Gunakan .xlsx, .xls, atau .csv');
    }

    private function parseDate(?string $value): ?string
    {
        if (empty($value)) return null;

        try {
            // Excel serial number
            if (is_numeric($value)) {
                return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
            }
            // Format DD/MM/YYYY
            if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $value)) {
                return Carbon::createFromFormat('d/m/Y', $value)->toDateString();
            }
            // Format YYYY-MM-DD
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                return Carbon::parse($value)->toDateString();
            }
            // Format DD-MM-YYYY
            if (preg_match('/^\d{1,2}-\d{1,2}-\d{4}$/', $value)) {
                return Carbon::createFromFormat('d-m-Y', $value)->toDateString();
            }
            // Fallback
            return Carbon::parse($value)->toDateString();
        } catch (\Exception $e) {
            return null;
        }
    }
}