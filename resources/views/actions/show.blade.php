<x-layouts.app>
    <x-slot name="title">{{ $action->staff->name }} - {{ $action->actionType->name }}</x-slot>

    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('actions.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Back to Actions</a>
    </div>

    <!-- Action Header -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 mb-6">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $action->staff->name }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ $action->staff->record_card_number }}</p>
                    <p class="mt-2 text-sm text-gray-700">
                        <strong>Action Type:</strong> {{ $action->actionType->name }}
                    </p>
                </div>
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $action->status_badge_class }}">
                    {{ $action->status_label }}
                </span>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Period</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $action->period }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Due Date</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($action->due_date)
                            {{ $action->due_date->format('d M Y') }}
                            @if($action->is_overdue)
                                <span class="ml-2 px-2 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800">
                                    Overdue
                                </span>
                            @endif
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Assigned To</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $action->assignee?->name ?? 'Unassigned' }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Created By</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $action->creator?->name ?? '-' }}
                    </dd>
                </div>
                @if($action->notes)
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Notes</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $action->notes }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Trigger Data Section (if exists) -->
    @if($action->trigger_data)
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 mb-6">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Trigger Information</h3>
            <div class="space-y-4">
                @if($action->trigger_data['trigger_name'] ?? null)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Trigger Name</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $action->trigger_data['trigger_name'] }}</dd>
                </div>
                @endif
                @if($action->trigger_data['condition'] ?? null)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Condition</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $action->trigger_data['condition'] }}</dd>
                </div>
                @endif
                @if($action->trigger_data['value'] ?? null)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Trigger Value</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $action->trigger_data['value'] }}</dd>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Status Update Buttons -->
    @if(!in_array($action->status, ['completed', 'cancelled']))
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 mb-6">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Status Actions</h3>
            <div class="flex flex-wrap gap-3">
                @if($action->status === 'pending')
                <form method="POST" action="{{ route('actions.status', $action) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="in_progress">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 rounded-lg font-semibold text-white bg-blue-600 hover:bg-blue-700 active:bg-blue-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-sm">
                        Mark as In Progress
                    </button>
                </form>
                @endif

                @if($action->status === 'in_progress')
                <form method="POST" action="{{ route('actions.status', $action) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="completed">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 rounded-lg font-semibold text-white bg-green-600 hover:bg-green-700 active:bg-green-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-green-500 text-sm">
                        Mark as Completed
                    </button>
                </form>
                @endif

                @if(!in_array($action->status, ['escalated']))
                <form method="POST" action="{{ route('actions.status', $action) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="escalated">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 rounded-lg font-semibold text-white bg-red-600 hover:bg-red-700 active:bg-red-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-red-500 text-sm">
                        Escalate
                    </button>
                </form>
                @endif

                @if($action->status !== 'cancelled')
                <form method="POST" action="{{ route('actions.status', $action) }}" class="inline"
                      onsubmit="return confirm('Are you sure you want to cancel this action?')">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm">
                        Cancel Action
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Documents Section -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 mb-6" x-data="{ documentsOpen: false }">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Documents</h3>
                <button type="button" @click="documentsOpen = !documentsOpen"
                        class="inline-flex items-center px-4 py-2 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm hover:shadow-md transition-all duration-200 text-sm">
                    Upload Document
                </button>
            </div>

            <!-- Upload Form (initially hidden) -->
            <div x-show="documentsOpen" class="mb-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                <form method="POST" action="{{ route('actions.documents.upload', $action) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <x-form.select name="document_type" label="Document Type" :required="true">
                            <option value="">Select type</option>
                            <option value="warning_letter">Warning Letter</option>
                            <option value="meeting_notes">Meeting Notes</option>
                            <option value="report">Report</option>
                            <option value="acknowledgment">Acknowledgment</option>
                            <option value="other">Other</option>
                        </x-form.select>

                        <x-form.input name="title" label="Title" />
                    </div>
                    <div>
                        <label for="document" class="block text-sm font-medium text-gray-700 mb-1">File *</label>
                        <input type="file" name="document" id="document" required
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="documentsOpen = false"
                                class="inline-flex items-center px-6 py-3 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm">
                            Cancel
                        </button>
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
                            Upload
                        </button>
                    </div>
                </form>
            </div>

            <!-- Documents List -->
            @if($action->documents->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">File Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Uploaded By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Uploaded At</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($action->documents as $document)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ str_replace('_', ' ', ucfirst($document->document_type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $document->title ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $document->file_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $document->uploadedBy?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $document->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 mr-3">Download</a>
                                <form method="POST" action="{{ route('actions.documents.delete', $document) }}" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this document?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-gray-500 text-sm">No documents uploaded yet.</p>
            @endif
        </div>
    </div>

    <!-- Feedback Section -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-100" x-data="{ feedbackOpen: false }">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Feedback & Acknowledgment</h3>
                <button type="button" @click="feedbackOpen = !feedbackOpen"
                        class="inline-flex items-center px-4 py-2 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm hover:shadow-md transition-all duration-200 text-sm">
                    Add Feedback
                </button>
            </div>

            <!-- Add Feedback Form (initially hidden) -->
            <div x-show="feedbackOpen" class="mb-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                <form method="POST" action="{{ route('actions.feedback.add', $action) }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <x-form.select name="feedback_by" label="Feedback From" :required="true">
                            <option value="">Select type</option>
                            <option value="staff">Staff</option>
                            <option value="manager">Manager</option>
                            <option value="hr">HR</option>
                            <option value="other">Other</option>
                        </x-form.select>

                        <x-form.select name="feedback_user_id" label="User">
                            <option value="">Select user (optional)</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </x-form.select>
                    </div>
                    <x-form.textarea name="feedback_text" label="Feedback Text" :required="true" rows="4" />

                    <x-form.date-input name="feedback_date" label="Feedback Date" :value="date('Y-m-d')" :required="true" />

                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="feedbackOpen = false"
                                class="inline-flex items-center px-6 py-3 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm">
                            Cancel
                        </button>
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
                            Add Feedback
                        </button>
                    </div>
                </form>
            </div>

            <!-- Feedbacks List -->
            @if($action->feedbacks->count() > 0)
            <div class="space-y-4">
                @foreach($action->feedbacks as $feedback)
                <div class="border border-gray-200 rounded-xl p-4 hover:border-gray-300 transition-colors">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                {{ ucfirst($feedback->feedback_by) }}
                                @if($feedback->feedback_user_id)
                                    <span class="text-gray-500">by {{ $feedback->feedbackUser?->name }}</span>
                                @endif
                            </p>
                            <p class="text-sm text-gray-500">{{ $feedback->feedback_date->format('d M Y') }}</p>
                        </div>
                        <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full
                            @if($feedback->acknowledgment_status === 'acknowledged') bg-green-100 text-green-800
                            @elseif($feedback->acknowledgment_status === 'disputed') bg-red-100 text-red-800
                            @elseif($feedback->acknowledgment_status === 'appealed') bg-orange-100 text-orange-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ str_replace('_', ' ', ucfirst($feedback->acknowledgment_status)) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-900 mb-3">{{ $feedback->feedback_text }}</p>
                    @if($feedback->acknowledgment_notes)
                    <div class="mt-2 pt-2 border-t border-gray-200">
                        <p class="text-xs text-gray-500 font-medium">Acknowledgment Notes:</p>
                        <p class="text-sm text-gray-700">{{ $feedback->acknowledgment_notes }}</p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-sm">No feedback added yet.</p>
            @endif
        </div>
    </div>
</x-layouts.app>
