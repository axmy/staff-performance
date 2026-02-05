<?php

namespace App\Services;

use App\Models\Staff;
use App\Models\Department;
use App\Models\MonthlyAttendance;
use App\Models\TrainingSession;
use App\Models\TrainingAttendance;
use App\Models\StaffAction;
use Illuminate\Support\Collection;

class ReportService
{
    public function getStaffPerformanceReport(int $year, ?int $month = null, ?int $departmentId = null): array
    {
        $query = Staff::active()->with(['department', 'designation']);

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        $staff = $query->get();

        $data = $staff->map(function ($staffMember) use ($year, $month) {
            // Get attendance data
            $attendanceQuery = $staffMember->monthlyAttendances()->where('year', $year);
            if ($month) {
                $attendanceQuery->where('month', $month);
            }
            $attendances = $attendanceQuery->get();

            $totalPresent = $attendances->sum('days_present');
            $totalAbsent = $attendances->sum('days_absent');
            $totalLate = $attendances->sum('days_late');
            $totalWorkingDays = $attendances->sum('total_working_days');

            // Get training data
            $trainingQuery = TrainingAttendance::where('staff_id', $staffMember->id)
                ->whereHas('trainingSession', function ($q) use ($year, $month) {
                    $q->whereYear('scheduled_date', $year);
                    if ($month) {
                        $q->whereMonth('scheduled_date', $month);
                    }
                });

            $trainingAttendances = $trainingQuery->get();
            $totalTrainings = $trainingAttendances->count();
            $attendedTrainings = $trainingAttendances->whereIn('attendance_status', ['present', 'late'])->count();

            // Get actions
            $actionsQuery = $staffMember->actions()->where('year', $year);
            if ($month) {
                $actionsQuery->where('month', $month);
            }
            $actions = $actionsQuery->get();

            return [
                'staff' => $staffMember,
                'attendance' => [
                    'total_present' => $totalPresent,
                    'total_absent' => $totalAbsent,
                    'total_late' => $totalLate,
                    'total_working_days' => $totalWorkingDays,
                    'attendance_rate' => $totalWorkingDays > 0
                        ? round(($totalPresent / $totalWorkingDays) * 100, 1)
                        : 0,
                ],
                'training' => [
                    'total_assigned' => $totalTrainings,
                    'total_attended' => $attendedTrainings,
                    'compliance_rate' => $totalTrainings > 0
                        ? round(($attendedTrainings / $totalTrainings) * 100, 1)
                        : 0,
                ],
                'actions' => [
                    'total' => $actions->count(),
                    'pending' => $actions->where('status', 'pending')->count(),
                    'completed' => $actions->where('status', 'completed')->count(),
                ],
            ];
        });

        return [
            'staff_data' => $data,
            'summary' => $this->calculateSummary($data),
            'departments' => Department::active()->get(),
        ];
    }

    public function getAttendanceSummaryReport(int $year, ?int $month = null, ?int $departmentId = null): array
    {
        $query = MonthlyAttendance::with(['staff.department', 'staff.designation'])
            ->where('year', $year);

        if ($month) {
            $query->where('month', $month);
        }

        if ($departmentId) {
            $query->whereHas('staff', function ($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        $attendances = $query->get();

        // Group by department
        $byDepartment = $attendances->groupBy(fn($a) => $a->staff->department?->name ?? 'Unassigned')
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total_present' => $group->sum('days_present'),
                    'total_absent' => $group->sum('days_absent'),
                    'total_late' => $group->sum('days_late'),
                    'total_working_days' => $group->sum('total_working_days'),
                    'avg_attendance_rate' => $group->avg('attendance_percentage'),
                ];
            });

        return [
            'attendances' => $attendances,
            'by_department' => $byDepartment,
            'totals' => [
                'total_staff' => $attendances->unique('staff_id')->count(),
                'total_present' => $attendances->sum('days_present'),
                'total_absent' => $attendances->sum('days_absent'),
                'total_late' => $attendances->sum('days_late'),
                'total_working_days' => $attendances->sum('total_working_days'),
            ],
            'departments' => Department::active()->get(),
        ];
    }

    public function getTrainingSummaryReport(int $year, ?int $month = null): array
    {
        $query = TrainingSession::withCount(['notifications', 'attendances'])
            ->whereYear('scheduled_date', $year);

        if ($month) {
            $query->whereMonth('scheduled_date', $month);
        }

        $trainings = $query->orderBy('scheduled_date')->get();

        $trainingsWithStats = $trainings->map(function ($training) {
            $attendances = $training->attendances;

            return [
                'training' => $training,
                'assigned_count' => $training->notifications_count,
                'present_count' => $attendances->where('attendance_status', 'present')->count(),
                'late_count' => $attendances->where('attendance_status', 'late')->count(),
                'absent_count' => $attendances->where('attendance_status', 'absent')->count(),
                'on_leave_count' => $attendances->where('attendance_status', 'on_leave')->count(),
                'attendance_rate' => $training->attendance_rate,
            ];
        });

        return [
            'trainings' => $trainingsWithStats,
            'totals' => [
                'total_sessions' => $trainings->count(),
                'completed_sessions' => $trainings->where('status', 'completed')->count(),
                'total_assigned' => $trainings->sum('notifications_count'),
                'total_attended' => $trainingsWithStats->sum(fn($t) => $t['present_count'] + $t['late_count']),
            ],
        ];
    }

    public function getActionReport(int $year, ?int $month = null, ?string $status = null): array
    {
        $query = StaffAction::with(['staff.department', 'actionType', 'assignee'])
            ->where('year', $year);

        if ($month) {
            $query->where('month', $month);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $actions = $query->orderByDesc('created_at')->get();

        // Group by action type
        $byActionType = $actions->groupBy(fn($a) => $a->actionType->name)
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'pending' => $group->where('status', 'pending')->count(),
                    'in_progress' => $group->where('status', 'in_progress')->count(),
                    'completed' => $group->where('status', 'completed')->count(),
                    'with_feedback' => $group->filter(fn($a) => $a->has_feedback)->count(),
                ];
            });

        // Group by department
        $byDepartment = $actions->groupBy(fn($a) => $a->staff->department?->name ?? 'Unassigned')
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'pending' => $group->where('status', 'pending')->count(),
                    'completed' => $group->where('status', 'completed')->count(),
                ];
            });

        return [
            'actions' => $actions,
            'by_action_type' => $byActionType,
            'by_department' => $byDepartment,
            'totals' => [
                'total' => $actions->count(),
                'pending' => $actions->where('status', 'pending')->count(),
                'in_progress' => $actions->where('status', 'in_progress')->count(),
                'completed' => $actions->where('status', 'completed')->count(),
                'overdue' => $actions->filter(fn($a) => $a->is_overdue)->count(),
            ],
        ];
    }

    protected function calculateSummary(Collection $data): array
    {
        $totalStaff = $data->count();

        if ($totalStaff === 0) {
            return [
                'total_staff' => 0,
                'avg_attendance_rate' => 0,
                'avg_training_compliance' => 0,
                'staff_with_actions' => 0,
            ];
        }

        return [
            'total_staff' => $totalStaff,
            'avg_attendance_rate' => round($data->avg('attendance.attendance_rate'), 1),
            'avg_training_compliance' => round($data->avg('training.compliance_rate'), 1),
            'staff_with_actions' => $data->filter(fn($d) => $d['actions']['total'] > 0)->count(),
        ];
    }
}
