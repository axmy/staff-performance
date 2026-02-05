<?php

namespace App\Http\Controllers;

use App\Models\ActionType;
use App\Models\ActionTrigger;
use App\Models\Department;
use App\Models\Designation;
use App\Models\User;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    // Action Types
    public function actionTypes()
    {
        $actionTypes = ActionType::ordered()->paginate(20);
        return view('settings.action-types', compact('actionTypes'));
    }

    public function storeActionType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:action_types',
            'description' => 'nullable|string',
            'requires_document' => 'boolean',
            'requires_feedback' => 'boolean',
            'is_active' => 'boolean',
        ]);

        ActionType::create($validated);

        return redirect()->back()->with('success', 'Action type created successfully.');
    }

    public function updateActionType(Request $request, ActionType $actionType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:action_types,code,' . $actionType->id,
            'description' => 'nullable|string',
            'requires_document' => 'boolean',
            'requires_feedback' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $actionType->update($validated);

        return redirect()->back()->with('success', 'Action type updated successfully.');
    }

    public function destroyActionType(ActionType $actionType)
    {
        if ($actionType->staffActions()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete action type with existing actions.');
        }

        $actionType->delete();

        return redirect()->back()->with('success', 'Action type deleted successfully.');
    }

    // Action Triggers
    public function triggers()
    {
        $triggers = ActionTrigger::with('actionType')->byPriority()->paginate(20);
        $actionTypes = ActionType::active()->ordered()->get();

        return view('settings.triggers', compact('triggers', 'actionTypes'));
    }

    public function storeTrigger(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'trigger_type' => 'required|in:attendance,training',
            'condition_field' => 'required|in:absent_days,late_days,leave_days,missed_trainings,late_to_trainings',
            'condition_operator' => 'required|in:>=,>,=,<=,<',
            'threshold_value' => 'required|integer|min:1',
            'action_type_id' => 'required|exists:action_types,id',
            'period_type' => 'required|in:monthly,quarterly,yearly,cumulative',
            'is_active' => 'boolean',
            'auto_trigger' => 'boolean',
            'priority' => 'integer|min:0',
        ]);

        ActionTrigger::create($validated);

        return redirect()->back()->with('success', 'Action trigger created successfully.');
    }

    public function updateTrigger(Request $request, ActionTrigger $trigger)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'trigger_type' => 'required|in:attendance,training',
            'condition_field' => 'required|in:absent_days,late_days,leave_days,missed_trainings,late_to_trainings',
            'condition_operator' => 'required|in:>=,>,=,<=,<',
            'threshold_value' => 'required|integer|min:1',
            'action_type_id' => 'required|exists:action_types,id',
            'period_type' => 'required|in:monthly,quarterly,yearly,cumulative',
            'is_active' => 'boolean',
            'auto_trigger' => 'boolean',
            'priority' => 'integer|min:0',
        ]);

        $trigger->update($validated);

        return redirect()->back()->with('success', 'Action trigger updated successfully.');
    }

    public function destroyTrigger(ActionTrigger $trigger)
    {
        $trigger->delete();
        return redirect()->back()->with('success', 'Action trigger deleted successfully.');
    }

    public function toggleTrigger(ActionTrigger $trigger)
    {
        $trigger->update(['is_active' => !$trigger->is_active]);

        $status = $trigger->is_active ? 'enabled' : 'disabled';
        return redirect()->back()->with('success', "Trigger {$status} successfully.");
    }

    // Users Management
    public function users()
    {
        $users = User::orderBy('name')->paginate(20);
        return view('settings.users', compact('users'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,manager,viewer',
        ]);

        $user->forceFill($validated)->save();

        return redirect()->back()->with('success', 'User role updated successfully.');
    }

    public function toggleUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->forceFill(['is_active' => !$user->is_active])->save();

        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "User {$status} successfully.");
    }

    // Departments
    public function departments()
    {
        $departments = Department::withCount('staff')->orderBy('name')->paginate(20);
        return view('settings.departments', compact('departments'));
    }

    public function storeDepartment(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        Department::create($validated);

        return redirect()->back()->with('success', 'Department created successfully.');
    }

    public function updateDepartment(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $department->update($validated);

        return redirect()->back()->with('success', 'Department updated successfully.');
    }

    public function destroyDepartment(Department $department)
    {
        if ($department->staff()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete department with existing staff.');
        }

        $department->delete();

        return redirect()->back()->with('success', 'Department deleted successfully.');
    }

    // Designations
    public function designations()
    {
        $designations = Designation::withCount('staff')->orderBy('name')->paginate(20);
        return view('settings.designations', compact('designations'));
    }

    public function storeDesignation(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        Designation::create($validated);

        return redirect()->back()->with('success', 'Designation created successfully.');
    }

    public function updateDesignation(Request $request, Designation $designation)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $designation->update($validated);

        return redirect()->back()->with('success', 'Designation updated successfully.');
    }

    public function destroyDesignation(Designation $designation)
    {
        if ($designation->staff()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete designation with existing staff.');
        }

        $designation->delete();

        return redirect()->back()->with('success', 'Designation deleted successfully.');
    }
}
