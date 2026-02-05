<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\MonthlyAttendance;
use App\Models\TrainingSession;
use App\Models\StaffAction;
use App\Services\ReportService;
use App\Exports\StaffPerformanceExport;
use App\Exports\AttendanceSummaryExport;
use App\Exports\TrainingSummaryExport;
use App\Exports\ActionReportExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reportService
    ) {}

    public function index()
    {
        return view('reports.index');
    }

    public function staffPerformance(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month');
        $departmentId = $request->get('department_id');

        $data = $this->reportService->getStaffPerformanceReport($year, $month, $departmentId);

        return view('reports.staff-performance', compact('data', 'year', 'month', 'departmentId'));
    }

    public function attendanceSummary(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month');
        $departmentId = $request->get('department_id');

        $data = $this->reportService->getAttendanceSummaryReport($year, $month, $departmentId);

        return view('reports.attendance-summary', compact('data', 'year', 'month', 'departmentId'));
    }

    public function trainingSummary(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month');

        $data = $this->reportService->getTrainingSummaryReport($year, $month);

        return view('reports.training-summary', compact('data', 'year', 'month'));
    }

    public function actionReport(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month');
        $status = $request->get('status');

        $data = $this->reportService->getActionReport($year, $month, $status);

        return view('reports.action-report', compact('data', 'year', 'month', 'status'));
    }

    // Excel Exports
    public function exportStaffPerformance(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month');
        $departmentId = $request->get('department_id');

        $filename = "staff_performance_{$year}" . ($month ? "_$month" : "") . ".xlsx";

        return Excel::download(
            new StaffPerformanceExport($year, $month, $departmentId),
            $filename
        );
    }

    public function exportAttendanceSummary(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month');
        $departmentId = $request->get('department_id');

        $filename = "attendance_summary_{$year}" . ($month ? "_$month" : "") . ".xlsx";

        return Excel::download(
            new AttendanceSummaryExport($year, $month, $departmentId),
            $filename
        );
    }

    public function exportTrainingSummary(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month');

        $filename = "training_summary_{$year}" . ($month ? "_$month" : "") . ".xlsx";

        return Excel::download(
            new TrainingSummaryExport($year, $month),
            $filename
        );
    }

    public function exportActionReport(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month');
        $status = $request->get('status');

        $filename = "action_report_{$year}" . ($month ? "_$month" : "") . ".xlsx";

        return Excel::download(
            new ActionReportExport($year, $month, $status),
            $filename
        );
    }

    // PDF Exports
    public function staffPdf(Staff $staff, Request $request)
    {
        $year = $request->get('year', now()->year);

        $staff->load([
            'department',
            'designation',
            'monthlyAttendances' => fn($q) => $q->where('year', $year)->orderBy('month'),
            'trainingAttendances.trainingSession' => fn($q) => $q->whereYear('scheduled_date', $year),
            'actions' => fn($q) => $q->where('year', $year)->with('actionType'),
        ]);

        $pdf = Pdf::loadView('reports.pdf.staff-performance', compact('staff', 'year'));

        return $pdf->download("staff_performance_{$staff->record_card_number}_{$year}.pdf");
    }

    public function attendancePdf(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $data = $this->reportService->getAttendanceSummaryReport($year, $month);

        $pdf = Pdf::loadView('reports.pdf.attendance-summary', compact('data', 'year', 'month'));

        return $pdf->download("attendance_summary_{$year}_{$month}.pdf");
    }
}
