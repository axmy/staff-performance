<x-layouts.app>
    <x-slot name="title">Action Triggers</x-slot>

    <x-settings-nav />

    <div x-data="{ showCreateModal: false, showEditModal: false, editingTrigger: null }">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Action Triggers</h1>
            <p class="mt-1 text-sm text-gray-500">Manage automated action triggers based on performance conditions</p>
        </div>
        <button @click="showCreateModal = true"
                class="inline-flex items-center px-6 py-3 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
            Create Trigger
        </button>
    </div>

    <!-- Triggers Table -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trigger Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Condition</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($triggers as $trigger)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $trigger->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ ucfirst(str_replace('_', ' ', $trigger->trigger_type)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div class="text-xs">
                            {{ ucfirst(str_replace('_', ' ', $trigger->condition_field)) }}
                            <span class="font-semibold">{{ $trigger->condition_operator }}</span>
                            <span class="font-semibold">{{ $trigger->threshold_value }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $trigger->actionType?->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ ucfirst(str_replace('_', ' ', $trigger->period_type)) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($trigger->is_active) bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                            @if($trigger->is_active) Active @else Inactive @endif
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button @click="showEditModal = true; editingTrigger = {{ json_encode($trigger) }}"
                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                            Edit
                        </button>
                        <form method="POST" action="{{ route('settings.triggers.destroy', $trigger) }}" class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this trigger?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        No triggers found. Create one to get started.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($triggers->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $triggers->links() }}
        </div>
        @endif

        <!-- Create Modal -->
        <div x-show="showCreateModal" x-transition.opacity
             class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div @click.away="showCreateModal = false" class="bg-white rounded-2xl shadow-xl max-w-2xl w-full mx-4">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Create New Trigger</h3>
                    <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('settings.triggers.store') }}" class="p-6">
                    @csrf

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-4">
                        <x-form.input name="name" label="Trigger Name" :required="true" />

                        <x-form.select name="trigger_type" label="Trigger Type" :required="true">
                            <option value="">Select Type</option>
                            <option value="attendance">Attendance</option>
                            <option value="performance">Performance</option>
                            <option value="training">Training</option>
                            <option value="behavioral">Behavioral</option>
                        </x-form.select>

                        <x-form.select name="condition_field" label="Condition Field" :required="true">
                            <option value="">Select Field</option>
                            <option value="attendance_percentage">Attendance Percentage</option>
                            <option value="performance_score">Performance Score</option>
                            <option value="training_completion">Training Completion</option>
                            <option value="late_count">Late Count</option>
                            <option value="absent_count">Absent Count</option>
                        </x-form.select>

                        <x-form.select name="condition_operator" label="Condition Operator" :required="true">
                            <option value="">Select Operator</option>
                            <option value="<">Less than (<)</option>
                            <option value="<=">Less than or equal (<=)</option>
                            <option value=">">Greater than (>)</option>
                            <option value=">=">Greater than or equal (>=)</option>
                            <option value="=">Equal (=)</option>
                            <option value="!=">Not equal (!=)</option>
                        </x-form.select>

                        <x-form.input name="threshold_value" label="Threshold Value" type="number" :required="true" step="0.01" />

                        <x-form.select name="action_type_id" label="Action Type" :required="true">
                            <option value="">Select Action Type</option>
                            @foreach($actionTypes as $actionType)
                            <option value="{{ $actionType->id }}">{{ $actionType->name }}</option>
                            @endforeach
                        </x-form.select>

                        <div class="sm:col-span-2">
                            <x-form.select name="period_type" label="Period Type" :required="true">
                                <option value="">Select Period</option>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="yearly">Yearly</option>
                            </x-form.select>
                        </div>

                        <div class="sm:col-span-2">
                            <x-form.toggle name="is_active" label="Active" :checked="true" />
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="showCreateModal = false"
                                class="px-6 py-3 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-6 py-3 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
                            Create Trigger
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="showEditModal" x-transition.opacity
             class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div @click.away="showEditModal = false" class="bg-white rounded-2xl shadow-xl max-w-2xl w-full mx-4">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Edit Trigger</h3>
                    <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <template x-if="editingTrigger">
                    <form :action="`{{ url('settings/triggers') }}/${editingTrigger.id}`" method="POST" class="p-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-4">
                            <div class="relative">
                                <input type="text" name="name" x-model="editingTrigger.name" required
                                       placeholder=" "
                                       class="peer block w-full px-4 pt-6 pb-2 text-base text-gray-900 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder-transparent transition-colors duration-200">
                                <label class="absolute left-4 top-4 origin-[0] -translate-y-3 scale-75 text-indigo-600 transform transition-all duration-200 pointer-events-none">
                                    Trigger Name *
                                </label>
                            </div>

                            <div class="relative">
                                <select name="trigger_type" x-model="editingTrigger.trigger_type" required
                                        class="peer block w-full px-4 pt-6 pb-2 text-base text-gray-900 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none transition-colors duration-200">
                                    <option value="attendance">Attendance</option>
                                    <option value="performance">Performance</option>
                                    <option value="training">Training</option>
                                    <option value="behavioral">Behavioral</option>
                                </select>
                                <label class="absolute left-4 top-4 origin-[0] -translate-y-3 scale-75 text-indigo-600 transform transition-all duration-200 pointer-events-none">
                                    Trigger Type *
                                </label>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                </div>
                            </div>

                            <div class="relative">
                                <select name="condition_field" x-model="editingTrigger.condition_field" required
                                        class="peer block w-full px-4 pt-6 pb-2 text-base text-gray-900 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none transition-colors duration-200">
                                    <option value="attendance_percentage">Attendance Percentage</option>
                                    <option value="performance_score">Performance Score</option>
                                    <option value="training_completion">Training Completion</option>
                                    <option value="late_count">Late Count</option>
                                    <option value="absent_count">Absent Count</option>
                                </select>
                                <label class="absolute left-4 top-4 origin-[0] -translate-y-3 scale-75 text-indigo-600 transform transition-all duration-200 pointer-events-none">
                                    Condition Field *
                                </label>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                </div>
                            </div>

                            <div class="relative">
                                <select name="condition_operator" x-model="editingTrigger.condition_operator" required
                                        class="peer block w-full px-4 pt-6 pb-2 text-base text-gray-900 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none transition-colors duration-200">
                                    <option value="<">Less than (<)</option>
                                    <option value="<=">Less than or equal (<=)</option>
                                    <option value=">">Greater than (>)</option>
                                    <option value=">=">Greater than or equal (>=)</option>
                                    <option value="=">Equal (=)</option>
                                    <option value="!=">Not equal (!=)</option>
                                </select>
                                <label class="absolute left-4 top-4 origin-[0] -translate-y-3 scale-75 text-indigo-600 transform transition-all duration-200 pointer-events-none">
                                    Condition Operator *
                                </label>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                </div>
                            </div>

                            <div class="relative">
                                <input type="number" name="threshold_value" x-model="editingTrigger.threshold_value" required step="0.01"
                                       placeholder=" "
                                       class="peer block w-full px-4 pt-6 pb-2 text-base text-gray-900 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder-transparent transition-colors duration-200">
                                <label class="absolute left-4 top-4 origin-[0] -translate-y-3 scale-75 text-indigo-600 transform transition-all duration-200 pointer-events-none">
                                    Threshold Value *
                                </label>
                            </div>

                            <div class="relative">
                                <select name="action_type_id" x-model="editingTrigger.action_type_id" required
                                        class="peer block w-full px-4 pt-6 pb-2 text-base text-gray-900 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none transition-colors duration-200">
                                    @foreach($actionTypes as $actionType)
                                    <option value="{{ $actionType->id }}">{{ $actionType->name }}</option>
                                    @endforeach
                                </select>
                                <label class="absolute left-4 top-4 origin-[0] -translate-y-3 scale-75 text-indigo-600 transform transition-all duration-200 pointer-events-none">
                                    Action Type *
                                </label>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <div class="relative">
                                    <select name="period_type" x-model="editingTrigger.period_type" required
                                            class="peer block w-full px-4 pt-6 pb-2 text-base text-gray-900 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none transition-colors duration-200">
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly">Monthly</option>
                                        <option value="quarterly">Quarterly</option>
                                        <option value="yearly">Yearly</option>
                                    </select>
                                    <label class="absolute left-4 top-4 origin-[0] -translate-y-3 scale-75 text-indigo-600 transform transition-all duration-200 pointer-events-none">
                                        Period Type *
                                    </label>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    </div>
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="edit_is_active" class="inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" id="edit_is_active" value="1"
                                           class="sr-only peer" x-bind:checked="editingTrigger.is_active">
                                    <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-indigo-300 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                    <span class="ms-3 text-sm font-medium text-gray-700">Active</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" @click="showEditModal = false"
                                    class="px-6 py-3 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-6 py-3 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
                                Update Trigger
                            </button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>
    </div>
</x-layouts.app>
