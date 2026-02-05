<?php

namespace App\Exports;

use App\Services\ReportService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ActionReportExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected int $year;
    protected ?int $month;
    protected ?string $status;

    public function __construct(int $year, ?int $month = null, ?string $status = null)
    {
        $this->year = $year;
        $this->month = $month;
        $this->status = $status;
    }

    public function collection()
    {
        $reportService = new ReportService();
        $data = $reportService->getActionReport($this->year, $this->month, $this->status);

        return $data['actions']->map(function ($action) {
            return [
                'Staff Name' => $action->staff->name,
                'Record Card No' => $action->staff->record_card_number,
                'Department' => $action->staff->department?->name ?? '-',
                'Action Type' => $action->actionType->name,
                'Period' => $action->period,
                'Status' => $action->status_label,
                'Due Date' => $action->due_date?->format('d M Y') ?? '-',
                'Assigned To' => $action->assignee?->name ?? '-',
                'Created At' => $action->created_at->format('d M Y'),
                'Completed At' => $action->completed_at?->format('d M Y') ?? '-',
                'Has Documents' => $action->has_documents ? 'Yes' : 'No',
                'Has Feedback' => $action->has_feedback ? 'Yes' : 'No',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Staff Name',
            'Record Card No',
            'Department',
            'Action Type',
            'Period',
            'Status',
            'Due Date',
            'Assigned To',
            'Created At',
            'Completed At',
            'Has Documents',
            'Has Feedback',
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
        $title = "Action Report {$this->year}";
        if ($this->month) {
            $title .= " - " . date('F', mktime(0, 0, 0, $this->month, 1));
        }
        return $title;
    }
}
