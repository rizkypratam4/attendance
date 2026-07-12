<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\EmployeeShiftAssignment;
use App\Models\ShiftCode;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class EmployeeShiftAssignmentService
{
    private const SENIN_KAMIS_CODES = ['1AA', '1PR', '1PQ', '1ZA'];
    private const JUMAT_CODES = ['1AB', '1PRB', '1PQB', '1ZAB'];
    private const SENIN_JUMAT_CODES = ['2ZB', '3ZZ', '3ZC'];
    private const SABTU_CODES = ['Day Off', '1PQBN', '1SSN', '2SSN', '3SSN'];
   
    //-- QUERY & LISTING --//
    // Ambil daftar assignment minggu ini per karyawan (dengan histori shift code), dalam bentuk paginated.
    public function getAll(int $perPage = 20)
    {
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek   = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $weekRows = $this->fetchCurrentWeekAssignments($startOfWeek, $endOfWeek);

        $activeEmployeeIds = $weekRows->pluck('employee_id')->unique()->values();

        if ($activeEmployeeIds->isEmpty()) {
            return $this->emptyPaginator();
        }

        $historicalShiftRows = $this->fetchHistoricalShiftRows($activeEmployeeIds);

        $grouped = $this->groupWeekRowsByEmployee($weekRows, $historicalShiftRows);

        return $this->paginateCollection($grouped);
    }

    // Ambil raw assignment rows minggu ini dari DB, dengan filter search/department/shift_code diterapkan.
    private function fetchCurrentWeekAssignments(Carbon $startOfWeek, Carbon $endOfWeek)
    {
        $query = EmployeeShiftAssignment::with(['employee', 'shiftCode', 'newWorkingShift'])
            ->whereBetween('date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()]);

        $this->applySearchFilter($query);
        $this->applyDepartmentFilter($query);
        $this->applyShiftCodeFilter($query);

        $query->join('employees', 'employees.id', '=', 'employee_shift_assignments.employee_id')
            ->orderBy('employees.name', 'asc')
            ->orderBy('employee_shift_assignments.date', 'asc')
            ->select('employee_shift_assignments.*');

        return $query->get();
    }

    // Tambahkan filter pencarian nama/NIK karyawan ke query jika parameter 'search' ada.
    private function applySearchFilter($query): void
    {
        if (!request('search')) {
            return;
        }

        $search = '%' . request('search') . '%';
        $query->whereHas(
            'employee',
            fn($q) => $q->where('name', 'like', $search)->orWhere('nik', 'like', $search)
        );
    }

    // Tambahkan filter department ke query jika parameter 'department' ada.
    private function applyDepartmentFilter($query): void
    {
        if (!request('department')) {
            return;
        }

        $query->whereHas(
            'employee',
            fn($q) => $q->where('department_id', request('department'))
        );
    }

    // Tambahkan filter shift code (shift_code_id atau new_working_shift_id) ke query jika parameter ada.
    private function applyShiftCodeFilter($query): void
    {
        if (!request('shift_code')) {
            return;
        }

        $query->where(
            fn($q) => $q->where('shift_code_id', request('shift_code'))
                ->orWhere('new_working_shift_id', request('shift_code'))
        );
    }

    // Ambil seluruh histori shift code karyawan (tanpa batasan tanggal), dikelompokkan per employee_id.
    private function fetchHistoricalShiftRows($employeeIds)
    {
        return EmployeeShiftAssignment::with(['shiftCode'])
            ->whereIn('employee_id', $employeeIds)
            ->whereNotNull('shift_code_id')
            ->get()
            ->groupBy('employee_id');
    }

    // Kelompokkan assignment minggu ini per karyawan lalu ringkas jadi satu objek summary per karyawan.
    private function groupWeekRowsByEmployee($weekRows, $historicalShiftRows)
    {
        return $weekRows
            ->groupBy('employee_id')
            ->map(fn($rows, $employeeId) => $this->summarizeEmployeeWeek($rows, $employeeId, $historicalShiftRows))
            ->values();
    }

    // Bangun objek ringkasan (shift codes histori, new shifts, on/off time, dsb) untuk satu karyawan.
    private function summarizeEmployeeWeek($rows, $employeeId, $historicalShiftRows)
    {
        $historicalRows = $historicalShiftRows->get($employeeId, collect());
        $shiftCodes = $historicalRows
            ->map(fn($r) => $r->shiftCode?->code)
            ->filter()->unique()->sort()->values();

        $newShifts = $rows
            ->map(fn($r) => $r->newWorkingShift?->code)
            ->filter()->unique()->sort()->values();

        return (object) [
            'employee'    => $rows->first()->employee,
            'min_date'    => $rows->min('date'),
            'max_date'    => $rows->max('date'),
            'shift_codes' => $shiftCodes,
            'new_shifts'  => $newShifts,
            'on_times'    => $this->extractUniqueShiftTimes($rows, 'on_time'),
            'off_times'   => $this->extractUniqueShiftTimes($rows, 'off_time'),
            'total_days'  => $rows->count(),
            'rows'        => $rows,
        ];
    }

    // Kumpulkan nilai unik on_time/off_time dari shift aktif per baris, mengabaikan shift day-off.
    private function extractUniqueShiftTimes($rows, string $timeField)
    {
        return $rows->map(function ($r) use ($timeField) {
            $shift = $r->newWorkingShift ?? $r->shiftCode;
            if (!$shift || $shift->is_day_off) {
                return null;
            }
            return $shift->{$timeField};
        })->filter()->unique()->sort()->values();
    }

    // Buat paginator kosong untuk kasus tidak ada assignment sama sekali minggu ini.
    private function emptyPaginator(): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            collect(),
            0,
            15,
            1,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    // Lakukan pagination manual atas collection hasil grouping per karyawan.
    private function paginateCollection($grouped): LengthAwarePaginator
    {
        $page    = request('page', 1);
        $perPage = 15;
        $total   = $grouped->count();
        $items   = $grouped->forPage($page, $perPage);

        return new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    //-- CRUD --//
    // Buat assignment baru, otomatis menentukan shift_code_id berdasarkan assignment sebelumnya.
    public function createAssignment(array $data): EmployeeShiftAssignment
    {
        $data['created_by'] ??= auth()->id();

        if (isset($data['employee_id'])) {
            $data = $this->applyShiftCodeForNewAssignment($data);
        }

        return EmployeeShiftAssignment::create($data);
    }

    // Tentukan shift_code_id assignment baru: pakai shift terakhir sebelum tanggal ini, atau fallback new_working_shift_id.
    private function applyShiftCodeForNewAssignment(array $data): array
    {
        $date = $data['date'] ?? now()->toDateString();

        $previous = EmployeeShiftAssignment::where('employee_id', $data['employee_id'])
            ->where('date', '<', $date)
            ->orderBy('date', 'desc')
            ->first();

        if ($previous) {
            $data['shift_code_id'] = $previous->shift_code_id;
        } elseif (empty($data['shift_code_id']) && isset($data['new_working_shift_id'])) {
            $data['shift_code_id'] = $data['new_working_shift_id'];
        }

        return $data;
    }

    // Update sebuah assignment (shift_code_id tidak boleh diubah lewat method ini).
    public function updateAssignment(EmployeeShiftAssignment $assignment, array $data): EmployeeShiftAssignment
    {
        unset($data['shift_code_id']);

        $assignment->update($data);
        return $assignment->fresh();
    }

    // Hapus satu assignment.
    public function deleteAssignment(EmployeeShiftAssignment $assignment): void
    {
        $assignment->delete();
    }

    // Hapus seluruh data assignment.
    public function deleteAll(): int
    {
        return EmployeeShiftAssignment::query()->delete();
    }

    //-- IMPORT EXCEL & CSV --//
    // Import assignment dari file Excel/CSV yang diupload, mengembalikan jumlah sukses & daftar error per baris.
    public function import(UploadedFile $file): array
    {
        $rows = $this->readFile($file);

        if (empty($rows)) {
            return ['success' => 0, 'errors' => ['File kosong.']];
        }

        $headerRowIndex = $this->findHeaderRowIndex($rows);

        if ($headerRowIndex === null) {
            return ['success' => 0, 'errors' => ['Header kolom tidak ditemukan. Pastikan ada kolom: Employee Name, Shift Code, Date']];
        }

        $header   = $this->normalizeHeaderRow($rows[$headerRowIndex]);
        $dataRows = array_slice($rows, $headerRowIndex + 1);

        if (empty($dataRows)) {
            return ['success' => 0, 'errors' => ['File tidak memiliki data.']];
        }

        $columns = $this->resolveImportColumnIndexes($header);

        if ($columns === null) {
            return ['success' => 0, 'errors' => [
                'Kolom tidak ditemukan. Pastikan header Excel memiliki: Employee Name, Shift Code, Date',
            ]];
        }

        return $this->processImportRows($dataRows, $header, $columns, $headerRowIndex);
    }

    // Baca file upload menjadi array baris mentah, sesuai tipe file (xlsx/xls/csv).
    private function readFile(UploadedFile $file): array
    {
        $ext = strtolower($file->getClientOriginalExtension());

        if (in_array($ext, ['xls', 'xlsx'])) {
            return $this->readExcelFile($file);
        }

        if ($ext === 'csv') {
            return $this->readCsvFile($file);
        }

        throw new \Exception('Tipe file tidak didukung: ' . $ext . '. Gunakan .xlsx, .xls, atau .csv');
    }

    // Baca file Excel (xls/xlsx) menjadi array baris menggunakan PhpSpreadsheet.
    private function readExcelFile(UploadedFile $file): array
    {
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

    // Baca file CSV menjadi array baris.
    private function readCsvFile(UploadedFile $file): array
    {
        $rows = [];

        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            while (($row = fgetcsv($handle)) !== false) {
                $rows[] = $row;
            }
            fclose($handle);
        }

        return $rows;
    }

    // Cari index baris yang merupakan baris header (mengandung kolom employee_name & shift_code).
    private function findHeaderRowIndex(array $rows): ?int
    {
        foreach ($rows as $index => $row) {
            $normalized = $this->normalizeHeaderRow($row);
            if (in_array('employee_name', $normalized) && in_array('shift_code', $normalized)) {
                return $index;
            }
        }

        return null;
    }

    // Normalisasi nama kolom header (lowercase, trim, spasi jadi underscore).
    private function normalizeHeaderRow(array $row): array
    {
        return array_map(
            fn($h) => strtolower(trim(str_replace(' ', '_', $h ?? ''))),
            $row
        );
    }

    // Cari index kolom employee_name, shift_code, date, dan new_working_shift dari header.
    private function resolveImportColumnIndexes(array $header): ?array
    {
        $empIndex      = array_search('employee_name', $header);
        $scIndex       = array_search('shift_code', $header);
        $dateIndex     = array_search('date', $header);
        $newShiftIndex = array_search('new_working_shift', $header);

        if ($empIndex === false || $scIndex === false || $dateIndex === false) {
            return null;
        }

        return [
            'employee'   => $empIndex,
            'shift_code' => $scIndex,
            'date'       => $dateIndex,
            'new_shift'  => $newShiftIndex,
        ];
    }

    // Proses semua baris data import satu per satu, kumpulkan jumlah sukses & daftar error.
    private function processImportRows(array $dataRows, array $header, array $columns, int $headerRowIndex): array
    {
        $success        = 0;
        $errors         = [];
        $shiftCodeCache = ShiftCode::pluck('id', 'code')->toArray();

        Log::info('ShiftAssignmentImport: header detected', [
            'header'        => $header,
            'empIndex'      => $columns['employee'],
            'scIndex'       => $columns['shift_code'],
            'dateIndex'     => $columns['date'],
            'newShiftIndex' => $columns['new_shift'],
            'totalDataRows' => count($dataRows),
            'firstDataRow'  => $dataRows[0] ?? [],
        ]);

        foreach ($dataRows as $i => $row) {
            $rowNumber = $i + $headerRowIndex + 2;
            $row       = array_map(fn($v) => is_null($v) ? '' : trim((string) $v), $row);

            $employeeName = $row[$columns['employee']]   ?? '';
            $shiftCode    = $row[$columns['shift_code']] ?? '';
            $dateRaw      = $row[$columns['date']]       ?? '';
            $newShift     = ($columns['new_shift'] !== false) ? ($row[$columns['new_shift']] ?? '') : '';

            if (empty($employeeName) && empty($shiftCode)) {
                $errors[] = "Baris {$rowNumber}: dilewati (baris kosong)";
                continue;
            }

            try {
                $this->importSingleRow($employeeName, $shiftCode, $dateRaw, $newShift, $shiftCodeCache);
                $success++;
            } catch (\Throwable $e) {
                $errors[] = "Baris {$rowNumber}: " . $e->getMessage();
                Log::warning('ShiftAssignmentImport: gagal import baris', [
                    'row'   => $rowNumber,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return ['success' => $success, 'errors' => $errors];
    }

    // Validasi & simpan satu baris data import sebagai assignment (dalam transaksi tersendiri).
    private function importSingleRow(
        string $employeeName,
        string $shiftCode,
        string $dateRaw,
        string $newShift,
        array $shiftCodeCache
    ): void {
        DB::beginTransaction();

        try {
            $employee = $this->findEmployeeByNameOrNik($employeeName);
            if (!$employee) {
                throw new \Exception($this->buildEmployeeNotFoundMessage($employeeName));
            }

            $shiftCodeId = $shiftCodeCache[$shiftCode] ?? null;
            if (!$shiftCodeId) {
                throw new \Exception("Shift code '{$shiftCode}' tidak ditemukan.");
            }

            $parsedDate = $this->parseDate($dateRaw);
            if (!$parsedDate) {
                throw new \Exception("Format tanggal tidak valid: '{$dateRaw}'");
            }

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
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // Cari karyawan berdasarkan nama (case-insensitive, trim spasi) atau NIK.
    private function findEmployeeByNameOrNik(string $employeeName): ?Employee
    {
        $cleanName = trim(preg_replace('/\s+/', ' ', $employeeName));

        return Employee::whereRaw('TRIM(LOWER(name)) = ?', [strtolower($cleanName)])->first()
            ?? Employee::where('nik', $cleanName)->first();
    }

    // Bangun pesan error "karyawan tidak ditemukan" lengkap dengan hint nama yang mirip.
    private function buildEmployeeNotFoundMessage(string $employeeName): string
    {
        $cleanName = trim(preg_replace('/\s+/', ' ', $employeeName));
        $similar   = Employee::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($cleanName) . '%'])
            ->limit(3)->pluck('name')->implode(', ');
        $hint = $similar ? " (nama mirip: {$similar})" : '';

        return "Karyawan '{$employeeName}' tidak ditemukan.{$hint}";
    }

    // Parse berbagai format tanggal (Excel serial, d/m/Y, Y-m-d, d-m-Y, dll) menjadi format Y-m-d.
    private function parseDate(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

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

    //-- BULK ASSIGN --//
    // Assign shift secara massal ke banyak karyawan (per employee/department/operator) untuk rentang tanggal.
    public function bulkAssign(array $data): array
    {
        try {
            DB::beginTransaction();

            $shiftCodes = $data['shift_codes'];
            $startDate  = Carbon::parse($data['start_date']);
            $endDate    = Carbon::parse($data['end_date']);

            $employeeIds = $this->resolveBulkAssignEmployeeIds($data);

            if (empty($employeeIds)) {
                return ['success' => false, 'message' => 'Tidak ada karyawan yang dipilih'];
            }
            if (empty($shiftCodes)) {
                return ['success' => false, 'message' => 'Tidak ada shift yang dipilih'];
            }

            $dates = $this->buildDateRange($startDate, $endDate);

            [$insertData, $totalRecords] = $this->buildBulkAssignmentRecords($employeeIds, $dates, $shiftCodes, $startDate);

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

    // Resolusi daftar employee_id target bulk assign berdasarkan assign_type (employee/department/operator).
    private function resolveBulkAssignEmployeeIds(array $data): array
    {
        $assignType = $data['assign_type'];

        if ($assignType === 'employee') {
            return $data['employee_ids'] ?? [];
        }

        if ($assignType === 'department') {
            return Employee::whereIn('department_id', $data['department_ids'] ?? [])
                ->pluck('id')->toArray();
        }

        if ($assignType === 'operator') {
            return $data['operator_ids'] ?? [];
        }

        return [];
    }

    // Bangun array tanggal (Y-m-d) dari start_date sampai end_date inklusif.
    private function buildDateRange(Carbon $startDate, Carbon $endDate): array
    {
        $dates       = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        return $dates;
    }

    // Bangun data assignment untuk semua karyawan & tanggal: update yang sudah ada, siapkan insert untuk yang baru.
    private function buildBulkAssignmentRecords(array $employeeIds, array $dates, array $shiftCodes, Carbon $startDate): array
    {
        $insertData   = [];
        $totalRecords = 0;
        $dayOffCode   = $this->findDayOffShiftCode();

        foreach ($employeeIds as $employeeId) {
            $prevAssignment = EmployeeShiftAssignment::where('employee_id', $employeeId)
                ->where('date', '<', $startDate->toDateString())
                ->orderBy('date', 'desc')
                ->first();

            foreach ($dates as $date) {
                $newShiftId = $this->resolveNewShiftIdForDate($date, $shiftCodes, $dayOffCode);

                if (!$newShiftId) {
                    continue;
                }

                $existing = EmployeeShiftAssignment::where('employee_id', $employeeId)
                    ->where('date', $date)
                    ->first();

                if ($existing) {
                    $existing->update([
                        'new_working_shift_id' => $newShiftId,
                        'created_by'           => auth()->id(),
                    ]);
                } else {
                    $insertData[] = $this->buildInsertRecord($employeeId, $date, $newShiftId, $prevAssignment);
                }

                $totalRecords++;
            }
        }

        return [$insertData, $totalRecords];
    }

    // Tentukan shift_id baru untuk suatu tanggal: day-off jika Minggu, atau shift sesuai hari kerja lainnya.
    private function resolveNewShiftIdForDate(string $date, array $shiftCodes, ?ShiftCode $dayOffCode): ?int
    {
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;

        if ($dayOfWeek === 0) {
            return $dayOffCode?->id;
        }

        return $this->getShiftCodeForDay($shiftCodes, $dayOfWeek);
    }

    // Bangun satu baris data insert assignment baru, dengan shift_code_id default dari histori.
    private function buildInsertRecord(int $employeeId, string $date, int $newShiftId, $prevAssignment): array
    {
        $sameDayLastWeek   = Carbon::parse($date)->subWeek()->toDateString();
        $sameDayAssignment = EmployeeShiftAssignment::where('employee_id', $employeeId)
            ->where('date', $sameDayLastWeek)
            ->first();

        $defaultShiftCodeId = $sameDayAssignment?->shift_code_id
            ?? $prevAssignment?->shift_code_id
            ?? $newShiftId;

        return [
            'employee_id'          => $employeeId,
            'shift_code_id'        => $defaultShiftCodeId,
            'new_working_shift_id' => $newShiftId,
            'date'                 => $date,
            'created_by'           => auth()->id(),
            'created_at'           => now(),
            'updated_at'           => now(),
        ];
    }

    // Cari kode shift day-off (berdasarkan kode "day off" atau flag is_day_off).
    private function findDayOffShiftCode(): ?ShiftCode
    {
        return ShiftCode::whereRaw('LOWER(code) = ?', ['day off'])->first()
            ?? ShiftCode::where('is_day_off', true)->first();
    }

    // Pilih shift code yang sesuai untuk suatu hari (Senin-Kamis/Jumat/Sabtu/Minggu) dari daftar shift yang dipilih.
    private function getShiftCodeForDay(array $shiftCodeIds, int $dayOfWeek): ?int
    {
        $shiftCodes = ShiftCode::whereIn('id', $shiftCodeIds)->get();

        if ($dayOfWeek === 0) {
            return null;
        }

        if ($dayOfWeek === 6) {
            return $shiftCodes->first(fn($sc) => in_array($sc->code, self::SABTU_CODES))?->id;
        }

        if ($dayOfWeek === 5) {
            $code = $shiftCodes->first(fn($sc) => in_array($sc->code, self::JUMAT_CODES));
            if ($code) {
                return $code->id;
            }
            return $shiftCodes->first(fn($sc) => in_array($sc->code, self::SENIN_JUMAT_CODES))?->id;
        }

        if ($dayOfWeek >= 1 && $dayOfWeek <= 4) {
            $code = $shiftCodes->first(fn($sc) => in_array($sc->code, self::SENIN_KAMIS_CODES));
            if ($code) {
                return $code->id;
            }
            return $shiftCodes->first(fn($sc) => in_array($sc->code, self::SENIN_JUMAT_CODES))?->id;
        }

        return null;
    }
}
