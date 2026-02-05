<?php

namespace App\Exports;

use App\Services\ReportService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceSummaryExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected int $year;
    protected ?int $month;
    protected ?int $departmentId;

    public function __construct(int $year, ?int $month = null, ?int $departmentId = null)
    {
        $this->year = $year;
        $this->month = $month;
        $this->departmentId = $departmentId;
    }

    public function collection()
    {
        $reportService = new ReportService();
        $data = $reportService->getAttendanceSummaryReport($this->year, $this->month, $this->departmentId);

        return $data['attendances']->map(function ($attendance) {
            return [
                'Record Card No' => $attendance->staff->record_card_number,
                'Name' => $attendance->staff->name,
                'Department' => $attendance->staff->department?->name ?? '-',
                'Year' => $attendance->year,
                'Month' => $attendance->month_name,
                'Days Present' => $attendance->days_present,
                'Days Absent' => $attendance->days_absent,
                'Days Leave' => $attendance->days_leave,
                'Days Late' => $attendance->days_late,
                'Total Working Days' => $attendance->total_working_days,
                'Attendance Rate (%)' => $attendance->attendance_percentage,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Record Card No',
            'Name',
            'Department',
            'Year',
            'Month',
            'Days Present',
            'Days Absent',
            'Days Leave',
            'Days Late',
            'Total Working Days',
            'Attendance Rate (%)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        $title = "Attendance Summary {$this->year}";
        if ($this->month) {
            $title .= " - " . date('F', mktime(0, 0, 0, $this->month, 1));
        }
        return $title;
    }
}
