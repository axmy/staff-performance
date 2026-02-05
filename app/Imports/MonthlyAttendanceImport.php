<?php

namespace App\Imports;

use App\Models\Staff;
use App\Models\MonthlyAttendance;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class MonthlyAttendanceImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;

    protected int $year;
    protected int $month;
    protected int $importBatchId;
    protected int $successCount = 0;
    protected int $errorCount = 0;
    protected array $importErrors = [];

    public function __construct(int $year, int $month, int $importBatchId)
    {
        $this->year = $year;
        $this->month = $month;
        $this->importBatchId = $importBatchId;
    }

    public function model(array $row)
    {
        // Find staff by record card number
        $staff = Staff::where('record_card_number', $row['record_card_number'] ?? $row['record_card_no'] ?? null)
            ->first();

        if (!$staff) {
            $this->errorCount++;
            $this->importErrors[] = "Staff with record card number '{$row['record_card_number']}' not found.";
            return null;
        }

        // Check if record already exists
        $existing = MonthlyAttendance::where('staff_id', $staff->id)
            ->where('year', $this->year)
            ->where('month', $this->month)
            ->first();

        $data = [
            'staff_id' => $staff->id,
            'year' => $this->year,
            'month' => $this->month,
            'days_present' => (int) ($row['days_present'] ?? $row['present'] ?? 0),
            'days_absent' => (int) ($row['days_absent'] ?? $row['absent'] ?? 0),
            'days_leave' => (int) ($row['days_leave'] ?? $row['leave'] ?? 0),
            'days_late' => (int) ($row['days_late'] ?? $row['late'] ?? 0),
            'total_working_days' => (int) ($row['total_working_days'] ?? $row['working_days'] ?? 0),
            'import_batch_id' => $this->importBatchId,
            'is_processed' => false,
        ];

        if ($existing) {
            $existing->update($data);
            $this->successCount++;
            return null;
        }

        $this->successCount++;
        return new MonthlyAttendance($data);
    }

    public function rules(): array
    {
        return [
            'record_card_number' => 'required',
            'days_present' => 'nullable|integer|min:0',
            'days_absent' => 'nullable|integer|min:0',
            'days_leave' => 'nullable|integer|min:0',
            'days_late' => 'nullable|integer|min:0',
            'total_working_days' => 'nullable|integer|min:0',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'record_card_number.required' => 'Record card number is required.',
        ];
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    public function getErrorCount(): int
    {
        return $this->errorCount;
    }

    public function getErrors(): array
    {
        return $this->importErrors;
    }
}
