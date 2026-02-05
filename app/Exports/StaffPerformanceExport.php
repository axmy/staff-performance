<?php

namespace App\Exports;

use App\Services\ReportService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StaffPerformanceExport implements FromCollection, WithHeadings, WithStyles, WithTitle
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
        $data = $reportService->getStaffPerformanceReport($this->year, $this->month, $this->departmentId);

        return collect($data['staff_data'])->map(function ($item) {
            return [
                'Record Card No' => $item['staff']->record_card_number,
                'Name' => $item['staff']->name,
                'Department' => $item['staff']->department?->name ?? '-',
                'Designation' => $item['staff']->designation?->name ?? '-',
                'Days Present' => $item['attendance']['total_present'],
                'Days Absent' => $item['attendance']['total_absent'],
                'Days Late' => $item['attendance']['total_late'],
                'Working Days' => $item['attendance']['total_working_days'],
                'Attendance Rate (%)' => $item['attendance']['attendance_rate'],
                'Trainings Assigned' => $item['training']['total_assigned'],
                'Trainings Attended' => $item['training']['total_attended'],
                'Training Compliance (%)' => $item['training']['compliance_rate'],
                'Total Actions' => $item['actions']['total'],
                'Pending Actions' => $item['actions']['pending'],
                'Completed Actions' => $item['actions']['completed'],
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Record Card No',
            'Name',
            'Department',
            'Designation',
            'Days Present',
            'Days Absent',
            'Days Late',
            'Working Days',
            'Attendance Rate (%)',
            'Trainings Assigned',
            'Trainings Attended',
            'Training Compliance (%)',
            'Total Actions',
            'Pending Actions',
            'Completed Actions',
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
        $title = "Staff Performance {$this->year}";
        if ($this->month) {
            $title .= " - " . date('F', mktime(0, 0, 0, $this->month, 1));
        }
        return $title;
    }
}
