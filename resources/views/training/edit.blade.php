<x-layouts.app>
    <x-slot name="title">Edit Training</x-slot>

    <div class="mb-6">
        <a href="{{ route('training.show', $training) }}" class="text-indigo-600 hover:text-indigo-900">&larr; Back to Training</a>
    </div>

    <div class="bg-white shadow-sm rounded-xl border border-gray-100">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-6">Edit Training Session</h2>

            <form method="POST" action="{{ route('training.update', $training) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <x-form.input name="title" label="Training Title" :value="old('title', $training->title)" :required="true" />
                    </div>

                    <div class="sm:col-span-2">
                        <x-form.textarea name="description" label="Description" :value="old('description', $training->description)" rows="4" />
                    </div>

                    <x-form.input name="trainer" label="Trainer" :value="old('trainer', $training->trainer)" :required="true" />

                    <x-form.input name="location" label="Location" :value="old('location', $training->location)" />

                    <x-form.date-input name="scheduled_date" label="Date" :value="old('scheduled_date', $training->scheduled_date?->format('Y-m-d'))" :required="true" />

                    <x-form.date-input name="scheduled_time" label="Time" type="time" :value="old('scheduled_time', $training->scheduled_time?->format('H:i'))" :required="true" />

                    <x-form.input name="duration_minutes" label="Duration (minutes)" type="number" :value="old('duration_minutes', $training->duration_minutes)" :required="true" step="1" />

                    <x-form.select name="status" label="Status" :required="true">
                        <option value="upcoming" @if(old('status', $training->status) == 'upcoming') selected @endif>Upcoming</option>
                        <option value="ongoing" @if(old('status', $training->status) == 'ongoing') selected @endif>Ongoing</option>
                        <option value="completed" @if(old('status', $training->status) == 'completed') selected @endif>Completed</option>
                        <option value="cancelled" @if(old('status', $training->status) == 'cancelled') selected @endif>Cancelled</option>
                    </x-form.select>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Assign Staff Members *</label>
                        <div class="border border-gray-200 rounded-xl p-4 max-h-96 overflow-y-auto bg-gray-50/50">
                            @forelse($staff as $member)
                            <label for="staff_{{ $member->id }}" class="flex items-center p-3 mb-2 last:mb-0 bg-white rounded-lg border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/50 cursor-pointer transition-colors duration-150">
                                <input type="checkbox" name="staff_ids[]" id="staff_{{ $member->id }}"
                                       value="{{ $member->id }}"
                                       @if(in_array($member->id, old('staff_ids', $assignedStaffIds))) checked @endif
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <span class="ml-3 text-sm text-gray-700">
                                    {{ $member->name }} <span class="text-gray-400">({{ $member->record_card_number }})</span>
                                </span>
                            </label>
                            @empty
                            <p class="text-sm text-gray-500 text-center py-4">No staff members available</p>
                            @endforelse
                        </div>
                        @error('staff_ids')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('training.show', $training) }}"
                       class="inline-flex items-center px-6 py-3 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm">
                        Cancel
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
                        Update Training
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
