<x-layouts.app>
    <x-slot name="title">Create Action</x-slot>

    <div class="mb-6">
        <a href="{{ route('actions.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Back to Actions</a>
    </div>

    <div class="bg-white shadow-sm rounded-xl border border-gray-100">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-6">Create New Staff Action</h2>

            <form method="POST" action="{{ route('actions.store') }}">
                @csrf

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Staff Selection -->
                    <x-form.select name="staff_id" label="Staff Member" :required="true">
                        <option value="">Select a staff member</option>
                        @foreach($staff as $member)
                        <option value="{{ $member->id }}" @if(old('staff_id') == $member->id) selected @endif>
                            {{ $member->name }} ({{ $member->record_card_number }})
                        </option>
                        @endforeach
                    </x-form.select>

                    <!-- Action Type -->
                    <x-form.select name="action_type_id" label="Action Type" :required="true">
                        <option value="">Select an action type</option>
                        @foreach($actionTypes as $type)
                        <option value="{{ $type->id }}" @if(old('action_type_id') == $type->id) selected @endif>
                            {{ $type->name }}
                        </option>
                        @endforeach
                    </x-form.select>

                    <!-- Year -->
                    <x-form.select name="year" label="Year" :required="true">
                        <option value="">Select a year</option>
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" @if(old('year', date('Y')) == $y) selected @endif>{{ $y }}</option>
                        @endfor
                    </x-form.select>

                    <!-- Month -->
                    <x-form.select name="month" label="Month" :required="true">
                        <option value="">Select a month</option>
                        @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" @if(old('month', date('n')) == $m) selected @endif>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                        @endfor
                    </x-form.select>

                    <!-- Due Date -->
                    <x-form.date-input name="due_date" label="Due Date" :value="old('due_date')" />

                    <!-- Assigned To -->
                    <x-form.select name="assigned_to" label="Assigned To">
                        <option value="">Select a user</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" @if(old('assigned_to') == $user->id) selected @endif>
                            {{ $user->name }}
                        </option>
                        @endforeach
                    </x-form.select>

                    <!-- Notes -->
                    <div class="sm:col-span-2">
                        <x-form.textarea name="notes" label="Notes" :value="old('notes')" rows="4" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('actions.index') }}"
                       class="inline-flex items-center px-6 py-3 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm">
                        Cancel
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
                        Create Action
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
