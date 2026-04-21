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
        // Filter minggu ini (Senin - Minggu)
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek   = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $query = EmployeeShiftAssignment::with(['employee', 'shiftCode.shift', 'newWorkingShift'])
            ->whereBetween('date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()]);

        // Search
        if (request('search')) {
            $search = '%' . request('search') . '%';
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('nik', 'like', $search);
            });
        }

        // Filter by department
        if (request('department')) {
            $query->whereHas('employee', function ($q) {
                $q->where('department_id', request('department'));
            });
        }

        // Filter by shift code
        if (request('shift_code')) {
            $query->where('shift_code_id', request('shift_code'));
        }

        $query->join('employees', 'employees.id', '=', 'employee_shift_assignments.employee_id')
              ->orderBy('employees.name', 'asc')
              ->orderBy('employee_shift_assignments.date', 'asc')
              ->select('employee_shift_assignments.*');

        $all = $query->get();

        // Group by employee_id
        $grouped = $all->groupBy('employee_id')->map(function ($rows) {
            $emp     = $rows->first()->employee;
            $minDate = $rows->min('date');
            $maxDate = $rows->max('date');

            // Shift Codes: shift default per hari (shift_code_id), tampilkan semua yang unik
            $shiftCodes = $rows->map(fn($r) => $r->shiftCode?->code)
                ->filter()->unique()->sort()->values();

            // New Shifts: tampilkan semua new_working_shift yang ada (tidak peduli sama/beda dengan shift_code)
            $newShifts = $rows->map(fn($r) => $r->newWorkingShift?->code)
                ->filter()->unique()->sort()->values();

            // Jam masuk & pulang: dari new_working_shift jika ada, fallback ke shift_code, skip day off
            $onTimes = $rows->map(function ($r) {
                $shift = $r->newWorkingShift ?? $r->shiftCode;
                if (!$shift || $shift->is_day_off) return null;
                return $shift->on_time;
            })->filter()->unique()->sort()->values();

            $offTimes = $rows->map(function ($r) {
                $shift = $r->newWorkingShift ?? $r->shiftCode;
                if (!$shift || $shift->is_day_off) return null;
                return $shift->off_time;
            })->filter()->unique()->sort()->values();

            return (object) [
                'employee'    => $emp,
                'min_date'    => $minDate,
                'max_date'    => $maxDate,
                'shift_codes' => $shiftCodes,
                'new_shifts'  => $newShifts,
                'on_times'    => $onTimes,
                'off_times'   => $offTimes,
                'total_days'  => $rows->count(),
                'rows'        => $rows,
            ];
        })->values();

        // Manual pagination
        $page    = request('page', 1);
        $perPage = 15;
        $total   = $grouped->count();
        $items   = $grouped->forPage($page, $perPage);

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    public function createAssignment(array $data): EmployeeShiftAssignment
    {
        $data['created_by'] ??= auth()->id();

        if (isset($data['employee_id'])) {
            $date = $data['date'] ?? now()->toDateString();

            // Ambil shift_code_id dari assignment terakhir SEBELUM tanggal ini
            $prev = EmployeeShiftAssignment::where('employee_id', $data['employee_id'])
                ->where('date', '<', $date)
                ->orderBy('date', 'desc')
                ->first();

            if ($prev) {
                $data['shift_code_id'] = $prev->shift_code_id;
            } elseif (empty($data['shift_code_id']) && isset($data['new_working_shift_id'])) {
                $data['shift_code_id'] = $data['new_working_shift_id'];
            }
        }

        return EmployeeShiftAssignment::create($data);
    }

    public function updateAssignment(EmployeeShiftAssignment $assignment, array $data): EmployeeShiftAssignment
    {
        // shift_code_id TIDAK BOLEH diubah
        unset($data['shift_code_id']);

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

    public function import(UploadedFile $file): array
    {
        $rows = $this->readFile($file);

        if (empty($rows)) {
            return ['success' => 0, 'errors' => ['File kosong.']];
        }

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

        $empIndex      = array_search('employee_name', $header);
        $scIndex       = array_search('shift_code', $header);
        $dateIndex     = array_search('date', $header);
        $newShiftIndex = array_search('new_working_shift', $header);

        if ($empIndex === false || $scIndex === false || $dateIndex === false) {
            return ['success' => 0, 'errors' => [
                'Kolom tidak ditemukan. Pastikan header Excel memiliki: Employee Name, Shift Code, Date',
            ]];
        }

        $success        = 0;
        $errors         = [];
        $shiftCodeCache = ShiftCode::pluck('id', 'code')->toArray();

        Log::info('ShiftAssignmentImport: header detected', [
            'header'        => $header,
            'empIndex'      => $empIndex,
            'scIndex'       => $scIndex,
            'dateIndex'     => $dateIndex,
            'newShiftIndex' => $newShiftIndex,
            'totalDataRows' => count($dataRows),
            'firstDataRow'  => $dataRows[0] ?? [],
        ]);

        foreach ($dataRows as $i => $row) {
            $row = array_map(fn($v) => is_null($v) ? '' : trim((string) $v), $row);

            $employeeName = $row[$empIndex]  ?? '';
            $shiftCode    = $row[$scIndex]   ?? '';
            $dateRaw      = $row[$dateIndex] ?? '';
            $newShift     = ($newShiftIndex !== false) ? ($row[$newShiftIndex] ?? '') : '';

            if (empty($employeeName) && empty($shiftCode)) {
                $errors[] = "Baris " . ($i + $headerRowIndex + 2) . ": dilewati (baris kosong)";
                continue;
            }

            try {
                DB::beginTransaction();

                $cleanName = trim(preg_replace('/\s+/', ' ', $employeeName));
                $employee  = Employee::whereRaw('TRIM(LOWER(name)) = ?', [strtolower($cleanName)])->first()
                    ?? Employee::where('nik', $cleanName)->first();

                if (!$employee) {
                    $similar = Employee::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($cleanName) . '%'])
                        ->limit(3)->pluck('name')->implode(', ');
                    $hint = $similar ? " (nama mirip: {$similar})" : '';
                    throw new \Exception("Karyawan '{$employeeName}' tidak ditemukan.{$hint}");
                }

                // Kolom shift_code di Excel → shift_code_id (shift default per hari)
                $shiftCodeId = $shiftCodeCache[$shiftCode] ?? null;
                if (!$shiftCodeId) {
                    throw new \Exception("Shift code '{$shiftCode}' tidak ditemukan.");
                }

                $parsedDate = $this->parseDate($dateRaw);
                if (!$parsedDate) {
                    throw new \Exception("Format tanggal tidak valid: '{$dateRaw}'");
                }

                // Kolom new_working_shift di Excel → new_working_shift_id
                // Jika tidak ada kolom new_working_shift, gunakan shift_code sebagai new_working_shift_id juga
                $newShiftCodeId = $shiftCodeId;
                if (!empty($newShift)) {
                    $override = $shiftCodeCache[$newShift] ?? null;
                    if (!$override) {
                        throw new \Exception("New working shift '{$newShift}' tidak ditemukan.");
                    }
                    $newShiftCodeId = $override;
                }

                EmployeeShiftAssignment::updateOrCreate(
                    ['employee_id' => $employee->id, 'date' => $parsedDate],
                    [
                        'shift_code_id'        => $shiftCodeId,
                        'new_working_shift_id' => $newShiftCodeId,
                        'created_by'           => auth()->id(),
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
            if (is_numeric($value)) {
                return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
            }
            if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $value)) {
                return Carbon::createFromFormat('d/m/Y', $value)->toDateString();
            }
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                return Carbon::parse($value)->toDateString();
            }
            if (preg_match('/^\d{1,2}-\d{1,2}-\d{4}$/', $value)) {
                return Carbon::createFromFormat('d-m-Y', $value)->toDateString();
            }
            return Carbon::parse($value)->toDateString();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Bulk assign shifts to employees, departments, or operators
     */
    public function bulkAssign(array $data): array
    {
        try {
            DB::beginTransaction();

            $assignType = $data['assign_type'];
            $shiftCodes = $data['shift_codes'];
            $startDate  = Carbon::parse($data['start_date']);
            $endDate    = Carbon::parse($data['end_date']);

            $employeeIds = [];
            if ($assignType === 'employee') {
                $employeeIds = $data['employee_ids'] ?? [];
            } elseif ($assignType === 'department') {
                $employeeIds = Employee::whereIn('department_id', $data['department_ids'] ?? [])
                    ->pluck('id')->toArray();
            } elseif ($assignType === 'operator') {
                $employeeIds = $data['operator_ids'] ?? [];
            }

            if (empty($employeeIds)) {
                return ['success' => false, 'message' => 'Tidak ada karyawan yang dipilih'];
            }
            if (empty($shiftCodes)) {
                return ['success' => false, 'message' => 'Tidak ada shift yang dipilih'];
            }

            // Generate date range
            $dates       = [];
            $currentDate = $startDate->copy();
            while ($currentDate->lte($endDate)) {
                $dates[] = $currentDate->format('Y-m-d');
                $currentDate->addDay();
            }

            $insertData   = [];
            $totalRecords = 0;

            // Ambil shift code "Day Off" untuk hari Minggu
            $dayOffCode = ShiftCode::whereRaw('LOWER(code) = ?', ['day off'])->first()
                ?? ShiftCode::where('is_day_off', true)->first();

            foreach ($employeeIds as $employeeId) {
                // Ambil shift_code_id default dari assignment terakhir sebelum start_date
                $prevAssignment = EmployeeShiftAssignment::where('employee_id', $employeeId)
                    ->where('date', '<', $startDate->toDateString())
                    ->orderBy('date', 'desc')
                    ->first();

                foreach ($dates as $date) {
                    $dayOfWeek = Carbon::parse($date)->dayOfWeek;

                    // Hari Minggu (0) → selalu Day Off
                    if ($dayOfWeek === 0) {
                        $newShiftId = $dayOffCode?->id;
                    } else {
                        $newShiftId = $this->getShiftCodeForDay($shiftCodes, $dayOfWeek);
                    }

                    if (!$newShiftId) continue;

                    $existing = EmployeeShiftAssignment::where('employee_id', $employeeId)
                        ->where('date', $date)
                        ->first();

                    if ($existing) {
                        // Update: shift_code_id TETAP, hanya new_working_shift_id yang berubah
                        $existing->update([
                            'new_working_shift_id' => $newShiftId,
                            'created_by'           => auth()->id(),
                        ]);
                    } else {
                        // Insert baru: cari shift_code_id dari tanggal yang sama minggu lalu
                        // agar pola shift default per hari tetap konsisten
                        $sameDayLastWeek = Carbon::parse($date)->subWeek()->toDateString();
                        $sameDayAssignment = EmployeeShiftAssignment::where('employee_id', $employeeId)
                            ->where('date', $sameDayLastWeek)
                            ->first();

                        $defaultShiftCodeId = $sameDayAssignment?->shift_code_id
                            ?? $prevAssignment?->shift_code_id
                            ?? $newShiftId;

                        $insertData[] = [
                            'employee_id'          => $employeeId,
                            'shift_code_id'        => $defaultShiftCodeId,
                            'new_working_shift_id' => $newShiftId,
                            'date'                 => $date,
                            'created_by'           => auth()->id(),
                            'created_at'           => now(),
                            'updated_at'           => now(),
                        ];
                    }

                    $totalRecords++;
                }
            }

            if (!empty($insertData)) {
                foreach (array_chunk($insertData, 500) as $chunk) {
                    EmployeeShiftAssignment::insert($chunk);
                }
            }

            DB::commit();

            return [
                'success' => true,
                'message' => "Berhasil assign shift ke " . count($employeeIds) . " karyawan untuk " . count($dates) . " hari ({$totalRecords} records)",
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk assign error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
        }
    }

    /**
     * Get appropriate shift code for a specific day (Senin–Sabtu).
     * Hari Minggu (0) ditangani langsung di bulkAssign().
     */
    private function getShiftCodeForDay(array $shiftCodeIds, int $dayOfWeek): ?int
    {
        $shiftCodes = ShiftCode::whereIn('id', $shiftCodeIds)->get();

        $seninKamisCodes = ['1AA', '1PR', '1PQ', '1ZA'];
        $jumatCodes      = ['1AB', '1PRB', '1PQB', '1ZAB'];
        $seninJumatCodes = ['2ZB', '3ZZ', '3ZC'];
        $sabtuCodes      = ['Day Off', '1PQBN', '1SSN', '2SSN', '3SSN'];

        if ($dayOfWeek === 0) {
            return null; // Minggu ditangani di bulkAssign
        } elseif ($dayOfWeek === 6) {
            return $shiftCodes->first(fn($sc) => in_array($sc->code, $sabtuCodes))?->id;
        } elseif ($dayOfWeek === 5) {
            $code = $shiftCodes->first(fn($sc) => in_array($sc->code, $jumatCodes));
            if ($code) return $code->id;
            return $shiftCodes->first(fn($sc) => in_array($sc->code, $seninJumatCodes))?->id;
        } elseif ($dayOfWeek >= 1 && $dayOfWeek <= 4) {
            $code = $shiftCodes->first(fn($sc) => in_array($sc->code, $seninKamisCodes));
            if ($code) return $code->id;
            return $shiftCodes->first(fn($sc) => in_array($sc->code, $seninJumatCodes))?->id;
        }

        return null;
    }
}
