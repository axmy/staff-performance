<?php

namespace App\Exports;

use App\Services\ReportService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TrainingSummaryExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected int $year;
    protected ?int $month;

    public function __construct(int $year, ?int $month = null)
    {
        $this->year = $year;
        $this->month = $month;
    }

    public function collection()
    {
        $reportService = new ReportService();
        $data = $reportService->getTrainingSummaryReport($this->year, $this->month);

        return collect($data['trainings'])->map(function ($item) {
            return [
                'Title' => $item['training']->title,
                'Date' => $item['training']->scheduled_date->format('d M Y'),
                'Trainer' => $item['training']->trainer ?? '-',
                'Location' => $item['training']->location ?? '-',
                'Status' => ucfirst($item['training']->status),
                'Staff Assigned' => $item['assigned_count'],
                'Present' => $item['present_count'],
                'Late' => $item['late_count'],
                'Absent' => $item['absent_count'],
                'On Leave' => $item['on_leave_count'],
                'Attendance Rate (%)' => $item['attendance_rate'],
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Title',
            'Date',
            'Trainer',
            'Location',
            'Status',
            'Staff Assigned',
            'Present',
            'Late',
            'Absent',
            'On Leave',
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
        $title = "Training Summary {$this->year}";
        if ($this->month) {
            $title .= " - " . date('F', mktime(0, 0, 0, $this->month, 1));
        }
        return $title;
    }
}
