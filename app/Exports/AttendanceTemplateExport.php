<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceTemplateExport implements FromArray, WithHeadings, WithStyles, WithTitle
{
    public function array(): array
    {
        return [
            [
                'record_card_number' => 'RC001',
                'staff_name' => 'John Doe (Example)',
                'days_present' => 22,
                'days_absent' => 2,
                'days_leave' => 1,
                'days_late' => 3,
                'total_working_days' => 25,
            ],
            [
                'record_card_number' => 'RC002',
                'staff_name' => 'Jane Smith (Example)',
                'days_present' => 24,
                'days_absent' => 0,
                'days_leave' => 1,
                'days_late' => 1,
                'total_working_days' => 25,
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'record_card_number',
            'staff_name',
            'days_present',
            'days_absent',
            'days_leave',
            'days_late',
            'total_working_days',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header style
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E5E7EB'],
            ],
        ]);

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(20);

        return [];
    }

    public function title(): string
    {
        return 'Attendance Import Template';
    }
}
