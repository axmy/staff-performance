<?php

namespace App\Http\Controllers;

use App\Models\StaffAction;
use App\Models\ActionDocument;
use App\Models\ActionFeedback;
use App\Models\ActionType;
use App\Models\Staff;
use App\Models\User;
use App\Services\ActionTriggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaffActionController extends Controller
{
    public function __construct(
        protected ActionTriggerService $triggerService
    ) {}

    public function index(Request $request)
    {
        $query = StaffAction::with(['staff', 'actionType', 'assignee']);

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($staffId = $request->get('staff_id')) {
            $query->where('staff_id', $staffId);
        }

        if ($actionTypeId = $request->get('action_type_id')) {
            $query->where('action_type_id', $actionTypeId);
        }

        if ($year = $request->get('year')) {
            $query->where('year', $year);
        }

        if ($month = $request->get('month')) {
            $query->where('month', $month);
        }

        $actions = $query->orderByDesc('created_at')->paginate(20);
        $actionTypes = ActionType::active()->ordered()->get();
        $staff = Staff::active()->orderBy('name')->get();

        return view('actions.index', compact('actions', 'actionTypes', 'staff'));
    }

    public function create()
    {
        $staff = Staff::active()->orderBy('name')->get();
        $actionTypes = ActionType::active()->ordered()->get();
        $users = User::where('is_active', true)->orderBy('name')->get();

        return view('actions.create', compact('staff', 'actionTypes', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'action_type_id' => 'required|exists:action_types,id',
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'required|integer|min:1|max:12',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $action = StaffAction::create([
            ...$validated,
            'triggered_at' => now(),
            'status' => 'pending',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('actions.show', $action)
            ->with('success', 'Action created successfully.');
    }

    public function show(StaffAction $action)
    {
        $action->load([
            'staff.department',
            'staff.designation',
            'actionType',
            'actionTrigger',
            'assignee',
            'creator',
            'documents.uploader',
            'feedbacks.feedbackUser',
        ]);

        $users = User::where('is_active', true)->orderBy('name')->get();

        return view('actions.show', compact('action', 'users'));
    }

    public function edit(StaffAction $action)
    {
        $staff = Staff::active()->orderBy('name')->get();
        $actionTypes = ActionType::active()->ordered()->get();

        return view('actions.edit', compact('action', 'staff', 'actionTypes'));
    }

    public function update(Request $request, StaffAction $action)
    {
        $validated = $request->validate([
            'action_type_id' => 'required|exists:action_types,id',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $action->update($validated);

        return redirect()->route('actions.show', $action)
            ->with('success', 'Action updated successfully.');
    }

    public function destroy(StaffAction $action)
    {
        // Delete associated documents from storage
        foreach ($action->documents as $document) {
            $document->deleteFile();
        }

        $action->delete();

        return redirect()->route('actions.index')
            ->with('success', 'Action deleted successfully.');
    }

    public function updateStatus(Request $request, StaffAction $action)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled,escalated',
            'notes' => 'nullable|string',
        ]);

        $action->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $action->notes,
            'completed_at' => $validated['status'] === 'completed' ? now() : null,
        ]);

        return redirect()->back()
            ->with('success', 'Action status updated successfully.');
    }

    public function uploadDocument(Request $request, StaffAction $action)
    {
        $validated = $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'document_type' => 'required|in:warning_letter,meeting_notes,report,acknowledgment,other',
            'title' => 'nullable|string|max:255',
        ]);

        $file = $request->file('document');
        $path = $file->store('action-documents/' . $action->id, 'public');

        ActionDocument::create([
            'staff_action_id' => $action->id,
            'document_type' => $validated['document_type'],
            'title' => $validated['title'],
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Document uploaded successfully.');
    }

    public function deleteDocument(ActionDocument $document)
    {
        $document->deleteFile();
        $document->delete();

        return redirect()->back()
            ->with('success', 'Document deleted successfully.');
    }

    public function addFeedback(Request $request, StaffAction $action)
    {
        $validated = $request->validate([
            'feedback_by' => 'required|in:staff,manager,hr,other',
            'feedback_text' => 'required|string',
            'feedback_date' => 'required|date',
        ]);

        ActionFeedback::create([
            'staff_action_id' => $action->id,
            'feedback_by' => $validated['feedback_by'],
            'feedback_user_id' => auth()->id(),
            'feedback_text' => $validated['feedback_text'],
            'feedback_date' => $validated['feedback_date'],
            'acknowledgment_status' => 'pending',
        ]);

        return redirect()->back()
            ->with('success', 'Feedback added successfully.');
    }

    public function processTriggers(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $result = $this->triggerService->processTriggersForPeriod(
            $validated['year'],
            $validated['month']
        );

        $message = "Processed triggers for {$result['staff_count']} staff. Created {$result['actions_created']} new actions.";

        return redirect()->route('actions.index', [
            'year' => $validated['year'],
            'month' => $validated['month'],
        ])->with('success', $message);
    }
}
