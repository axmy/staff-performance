<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $query = Staff::with(['department', 'designation']);

        if ($search = $request->get('search')) {
            $query->search($search);
        }

        if ($department = $request->get('department')) {
            $query->where('department_id', $department);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $staff = $query->orderBy('name')->paginate(15);
        $departments = Department::active()->orderBy('name')->get();

        return view('staff.index', compact('staff', 'departments'));
    }

    public function create()
    {
        $departments = Department::active()->orderBy('name')->get();
        $designations = Designation::active()->orderBy('name')->get();

        return view('staff.create', compact('departments', 'designations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'record_card_number' => 'required|string|max:50|unique:staff',
            'designation_id' => 'nullable|exists:designations,id',
            'department_id' => 'nullable|exists:departments,id',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'joined_date' => 'nullable|date',
            'status' => 'required|in:active,inactive,terminated',
            'notes' => 'nullable|string',
        ]);

        $staff = Staff::create($validated);

        return redirect()->route('staff.index')
            ->with('success', 'Staff member created successfully.');
    }

    public function show(Staff $staff)
    {
        $staff->load(['department', 'designation', 'monthlyAttendances', 'actions.actionType']);

        return view('staff.show', compact('staff'));
    }

    public function edit(Staff $staff)
    {
        $departments = Department::active()->orderBy('name')->get();
        $designations = Designation::active()->orderBy('name')->get();

        return view('staff.edit', compact('staff', 'departments', 'designations'));
    }

    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'record_card_number' => 'required|string|max:50|unique:staff,record_card_number,' . $staff->id,
            'designation_id' => 'nullable|exists:designations,id',
            'department_id' => 'nullable|exists:departments,id',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'joined_date' => 'nullable|date',
            'status' => 'required|in:active,inactive,terminated',
            'notes' => 'nullable|string',
        ]);

        $staff->update($validated);

        return redirect()->route('staff.show', $staff)
            ->with('success', 'Staff member updated successfully.');
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();

        return redirect()->route('staff.index')
            ->with('success', 'Staff member deleted successfully.');
    }

    public function history(Staff $staff)
    {
        $staff->load([
            'monthlyAttendances' => fn($q) => $q->orderByDesc('year')->orderByDesc('month'),
            'trainingAttendances.trainingSession',
            'actions.actionType',
        ]);

        return view('staff.history', compact('staff'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        // Import logic will be handled by Livewire component
        return redirect()->route('staff.index')
            ->with('success', 'Staff imported successfully.');
    }
}
