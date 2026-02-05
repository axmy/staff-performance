<?php

namespace App\Services;

use App\Models\Staff;
use App\Models\MonthlyAttendance;
use App\Models\TrainingSession;
use App\Models\StaffAction;
use App\Models\ActionTrigger;
use App\Exports\DashboardExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getStats(int $year, int $month): array
    {
        return [
            'overview' => $this->getOverviewStats(),
            'attendance' => $this->getAttendanceStats($year, $month),
            'training' => $this->getTrainingStats($year, $month),
            'actions' => $this->getActionStats($year, $month),
            'staffAtRisk' => $this->getStaffAtRisk($year, $month),
            'recentActivity' => $this->getRecentActivity(),
            'monthlyTrends' => $this->getMonthlyTrends($year),
        ];
    }

    protected function getOverviewStats(): array
    {
        return [
            'total_staff' => Staff::active()->count(),
            'total_departments' => Staff::active()->distinct('department_id')->count('department_id'),
            'total_trainings_this_month' => TrainingSession::whereMonth('scheduled_date', now()->month)
                ->whereYear('scheduled_date', now()->year)
                ->count(),
            'pending_actions' => StaffAction::pending()->count(),
        ];
    }

    protected function getAttendanceStats(int $year, int $month): array
    {
        $attendances = MonthlyAttendance::forPeriod($year, $month)->get();

        $totalPresent = $attendances->sum('days_present');
        $totalAbsent = $attendances->sum('days_absent');
        $totalLate = $attendances->sum('days_late');
        $totalLeave = $attendances->sum('days_leave');
        $totalWorkingDays = $attendances->sum('total_working_days');

        return [
            'total_records' => $attendances->count(),
            'total_present' => $totalPresent,
            'total_absent' => $totalAbsent,
            'total_late' => $totalLate,
            'total_leave' => $totalLeave,
            'total_working_days' => $totalWorkingDays,
            'attendance_rate' => $totalWorkingDays > 0
                ? round(($totalPresent / $totalWorkingDays) * 100, 1)
                : 0,
            'absence_rate' => $totalWorkingDays > 0
                ? round(($totalAbsent / $totalWorkingDays) * 100, 1)
                : 0,
            'late_rate' => $totalWorkingDays > 0
                ? round(($totalLate / $totalWorkingDays) * 100, 1)
                : 0,
        ];
    }

    protected function getTrainingStats(int $year, int $month): array
    {
        $trainings = TrainingSession::inMonth($year, $month)
            ->withCount(['notifications', 'attendances'])
            ->get();

        $totalAssigned = $trainings->sum('notifications_count');
        $totalAttended = $trainings->flatMap(function ($training) {
            return $training->attendances->whereIn('attendance_status', ['present', 'late']);
        })->count();

        return [
            'total_sessions' => $trainings->count(),
            'completed_sessions' => $trainings->where('status', 'completed')->count(),
            'upcoming_sessions' => $trainings->where('status', 'upcoming')->count(),
            'total_assigned' => $totalAssigned,
            'total_attended' => $totalAttended,
            'compliance_rate' => $totalAssigned > 0
                ? round(($totalAttended / $totalAssigned) * 100, 1)
                : 0,
        ];
    }

    protected function getActionStats(int $year, int $month): array
    {
        $actions = StaffAction::forPeriod($year, $month)->get();

        return [
            'total_actions' => $actions->count(),
            'pending' => $actions->where('status', 'pending')->count(),
            'in_progress' => $actions->where('status', 'in_progress')->count(),
            'completed' => $actions->where('status', 'completed')->count(),
            'overdue' => StaffAction::overdue()->forPeriod($year, $month)->count(),
            'with_feedback' => $actions->filter(fn($a) => $a->has_feedback)->count(),
        ];
    }

    protected function getStaffAtRisk(int $year, int $month): array
    {
        $triggers = ActionTrigger::active()->get();
        $staffAtRisk = [];

        $allStaff = Staff::active()->with([
            'monthlyAttendances' => fn($q) => $q->forPeriod($year, $month),
        ])->get();

        foreach ($allStaff as $staff) {
            $risks = [];

            foreach ($triggers as $trigger) {
                if ($trigger->evaluateForStaff($staff, $year, $month)) {
                    $risks[] = [
                        'trigger' => $trigger->name,
                        'condition' => $trigger->condition_text,
                        'value' => $trigger->getStaffValue($staff, $year, $month),
                    ];
                }
            }

            if (!empty($risks)) {
                $staffAtRisk[] = [
                    'staff' => $staff,
                    'risks' => $risks,
                ];
            }
        }

        // Sort by number of risks
        usort($staffAtRisk, fn($a, $b) => count($b['risks']) <=> count($a['risks']));

        return array_slice($staffAtRisk, 0, 10);
    }

    protected function getRecentActivity(): array
    {
        $recentActions = StaffAction::with(['staff', 'actionType'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(fn($action) => [
                'type' => 'action',
                'title' => "Action created for {$action->staff->name}",
                'subtitle' => $action->actionType->name,
                'time' => $action->created_at,
            ]);

        $recentTrainings = TrainingSession::orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(fn($training) => [
                'type' => 'training',
                'title' => $training->title,
                'subtitle' => "Scheduled for {$training->formatted_date}",
                'time' => $training->created_at,
            ]);

        return $recentActions->toBase()->merge($recentTrainings->toBase())
            ->sortByDesc('time')
            ->take(10)
            ->values()
            ->toArray();
    }

    protected function getMonthlyTrends(int $year): array
    {
        $months = [];

        for ($month = 1; $month <= 12; $month++) {
            $attendances = MonthlyAttendance::forPeriod($year, $month)->get();

            $totalPresent = $attendances->sum('days_present');
            $totalWorkingDays = $attendances->sum('total_working_days');

            $months[] = [
                'month' => date('M', mktime(0, 0, 0, $month, 1)),
                'attendance_rate' => $totalWorkingDays > 0
                    ? round(($totalPresent / $totalWorkingDays) * 100, 1)
                    : 0,
                'training_count' => TrainingSession::inMonth($year, $month)->count(),
                'action_count' => StaffAction::forPeriod($year, $month)->count(),
            ];
        }

        return $months;
    }

    public function export(int $year, int $month, string $format)
    {
        $stats = $this->getStats($year, $month);

        if ($format === 'pdf') {
            $pdf = \PDF::loadView('reports.pdf.dashboard', compact('stats', 'year', 'month'));
            return $pdf->download("dashboard_{$year}_{$month}.pdf");
        }

        return Excel::download(
            new DashboardExport($stats, $year, $month),
            "dashboard_{$year}_{$month}.xlsx"
        );
    }
}
