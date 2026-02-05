<x-layouts.app>
    <x-slot name="title">Mark Attendance - {{ $training->title }}</x-slot>

    <div class="mb-6">
        <a href="{{ route('training.show', $training) }}" class="text-indigo-600 hover:text-indigo-900">&larr; Back to Training Details</a>
    </div>

    <div class="bg-white shadow-sm rounded-xl border border-gray-100 mb-6">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-2">{{ $training->title }}</h2>
            <p class="text-sm text-gray-500">
                {{ $training->scheduled_date?->format('d M Y') }} at {{ $training->formatted_time ?? '-' }} | {{ $training->location ?? 'Location TBD' }}
            </p>
        </div>
    </div>

    <form method="POST" action="{{ route('training.attendance.save', $training) }}">
        @csrf

        <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Record Card</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendance Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($assignedStaff as $key => $entry)
                    @php
                        $staff = $entry['staff'];
                        $attendance = $entry['attendance'];
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500">
                                        <span class="text-sm font-medium leading-none text-white">
                                            {{ substr($staff->name, 0, 2) }}
                                        </span>
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $staff->name }}</div>
                                </div>
                            </div>
                            <input type="hidden" name="attendance[{{ $key }}][staff_id]" value="{{ $staff->id }}">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $staff->record_card_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $staff->department?->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <select name="attendance[{{ $key }}][status]"
                                    class="block w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                                <option value="present" @if($attendance && $attendance->attendance_status === 'present') selected @endif>Present</option>
                                <option value="late" @if($attendance && $attendance->attendance_status === 'late') selected @endif>Late</option>
                                <option value="absent" @if($attendance && $attendance->attendance_status === 'absent') selected @endif>Absent</option>
                                <option value="on_leave" @if($attendance && $attendance->attendance_status === 'on_leave') selected @endif>On Leave</option>
                                <option value="excused" @if($attendance && $attendance->attendance_status === 'excused') selected @endif>Excused</option>
                            </select>
                        </td>
                        <td class="px-6 py-4">
                            <textarea name="attendance[{{ $key }}][reason]" rows="2"
                                      placeholder="Reason for absence, late, or leave..."
                                      class="block w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">{{ $attendance?->absence_reason ?? '' }}</textarea>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No staff members assigned to this training.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('training.show', $training) }}"
               class="inline-flex items-center px-6 py-3 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm">
                Cancel
            </a>
            <button type="submit"
                    class="inline-flex items-center px-6 py-3 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
                Save Attendance
            </button>
        </div>
    </form>
</x-layouts.app>
