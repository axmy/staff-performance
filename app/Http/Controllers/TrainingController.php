<?php

namespace App\Http\Controllers;

use App\Models\TrainingSession;
use App\Models\TrainingNotification;
use App\Models\TrainingAttendance;
use App\Models\Staff;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
        $query = TrainingSession::withCount(['notifications', 'attendances']);

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($month = $request->get('month')) {
            $query->whereMonth('scheduled_date', $month);
        }

        if ($year = $request->get('year')) {
            $query->whereYear('scheduled_date', $year);
        }

        $trainings = $query->orderByDesc('scheduled_date')->paginate(15);

        return view('training.index', compact('trainings'));
    }

    public function create()
    {
        $staff = Staff::active()->orderBy('name')->get();

        return view('training.create', compact('staff'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'trainer' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'nullable|date_format:H:i',
            'duration_minutes' => 'nullable|integer|min:1',
            'status' => 'nullable|in:upcoming,ongoing,completed,cancelled',
            'staff_ids' => 'nullable|array',
            'staff_ids.*' => 'exists:staff,id',
        ]);

        $training = TrainingSession::create([
            ...$validated,
            'created_by' => auth()->id(),
            'status' => $validated['status'] ?? 'upcoming',
        ]);

        // Assign staff to training
        if (!empty($validated['staff_ids'])) {
            foreach ($validated['staff_ids'] as $staffId) {
                TrainingNotification::create([
                    'training_session_id' => $training->id,
                    'staff_id' => $staffId,
                    'response' => 'no_response',
                ]);
            }
        }

        return redirect()->route('training.show', $training)
            ->with('success', 'Training session created successfully.');
    }

    public function show(TrainingSession $training)
    {
        $training->load(['notifications.staff', 'attendances.staff', 'creator']);

        return view('training.show', compact('training'));
    }

    public function edit(TrainingSession $training)
    {
        $staff = Staff::active()->orderBy('name')->get();
        $assignedStaffIds = $training->notifications()->pluck('staff_id')->toArray();

        return view('training.edit', compact('training', 'staff', 'assignedStaffIds'));
    }

    public function update(Request $request, TrainingSession $training)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'trainer' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'nullable|date_format:H:i',
            'duration_minutes' => 'nullable|integer|min:1',
            'status' => 'nullable|in:upcoming,ongoing,completed,cancelled',
            'staff_ids' => 'nullable|array',
            'staff_ids.*' => 'exists:staff,id',
        ]);

        $training->update($validated);

        // Update staff assignments
        if (isset($validated['staff_ids'])) {
            $currentStaffIds = $training->notifications()->pluck('staff_id')->toArray();
            $newStaffIds = $validated['staff_ids'];

            // Remove unassigned staff
            $training->notifications()
                ->whereNotIn('staff_id', $newStaffIds)
                ->delete();

            // Add new staff
            foreach (array_diff($newStaffIds, $currentStaffIds) as $staffId) {
                TrainingNotification::create([
                    'training_session_id' => $training->id,
                    'staff_id' => $staffId,
                    'response' => 'no_response',
                ]);
            }
        }

        return redirect()->route('training.show', $training)
            ->with('success', 'Training session updated successfully.');
    }

    public function destroy(TrainingSession $training)
    {
        $training->delete();

        return redirect()->route('training.index')
            ->with('success', 'Training session deleted successfully.');
    }

    public function attendance(TrainingSession $training)
    {
        $training->load(['notifications.staff', 'attendances']);

        $assignedStaff = $training->notifications->map(function ($notification) use ($training) {
            $attendance = $training->attendances->where('staff_id', $notification->staff_id)->first();
            return [
                'staff' => $notification->staff,
                'notification' => $notification,
                'attendance' => $attendance,
            ];
        })->values();

        return view('training.attendance', compact('training', 'assignedStaff'));
    }

    public function saveAttendance(Request $request, TrainingSession $training)
    {
        $validated = $request->validate([
            'attendance' => 'required|array',
            'attendance.*.staff_id' => 'required|exists:staff,id',
            'attendance.*.status' => 'required|in:present,late,absent,on_leave,excused',
            'attendance.*.reason' => 'nullable|string|max:500',
        ]);

        foreach ($validated['attendance'] as $record) {
            TrainingAttendance::updateOrCreate(
                [
                    'training_session_id' => $training->id,
                    'staff_id' => $record['staff_id'],
                ],
                [
                    'attendance_status' => $record['status'],
                    'absence_reason' => in_array($record['status'], ['absent', 'on_leave', 'excused'])
                        ? $record['reason']
                        : null,
                    'marked_by' => auth()->id(),
                    'marked_at' => now(),
                ]
            );
        }

        return redirect()->route('training.show', $training)
            ->with('success', 'Attendance saved successfully.');
    }

    public function updateStatus(Request $request, TrainingSession $training)
    {
        $validated = $request->validate([
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
        ]);

        $training->update($validated);

        return redirect()->back()
            ->with('success', 'Training status updated successfully.');
    }

    public function sendNotifications(Request $request, TrainingSession $training)
    {
        $validated = $request->validate([
            'method' => 'required|in:email,sms,verbal,notice_board,other',
            'staff_ids' => 'nullable|array',
            'staff_ids.*' => 'exists:staff,id',
        ]);

        $query = $training->notifications();

        if (!empty($validated['staff_ids'])) {
            $query->whereIn('staff_id', $validated['staff_ids']);
        }

        $query->update([
            'notified_at' => now(),
            'notification_method' => $validated['method'],
            'notified_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Notifications recorded successfully.');
    }
}
