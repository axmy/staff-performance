<x-layouts.app>
    <x-slot name="title">Import Details</x-slot>

    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Import Details</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $import->original_file_name ?? $import->file_name }} &mdash; {{ date('F Y', mktime(0, 0, 0, $import->month, 1, $import->year)) }}</p>
        </div>
        <div class="flex items-center gap-3">
            @if(auth()->user()->canManage())
            <form method="POST" action="{{ route('attendance.destroy', $import) }}"
                  onsubmit="return confirm('Delete this entire import and all its attendance records? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-6 py-3 rounded-lg font-semibold text-white bg-red-600 hover:bg-red-700 active:bg-red-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-red-500 text-sm">
                    Delete Import
                </button>
            </form>
            @endif
            <a href="{{ route('attendance.history') }}"
               class="px-6 py-3 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm">
                Back to History
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4">
        <p class="text-sm text-green-800">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-4 mb-8">
        <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-5">
            <p class="text-sm font-medium text-gray-500">Status</p>
            <p class="mt-1">
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $import->status_badge_class }}">
                    {{ ucfirst($import->status) }}
                </span>
            </p>
        </div>
        <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-5">
            <p class="text-sm font-medium text-gray-500">Imported By</p>
            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $import->importer->name ?? 'N/A' }}</p>
        </div>
        <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-5">
            <p class="text-sm font-medium text-gray-500">Success / Errors</p>
            <p class="mt-1 text-lg font-semibold text-gray-900">
                <span class="text-green-600">{{ $import->success_count ?? 0 }}</span>
                /
                <span class="text-red-600">{{ $import->error_count ?? 0 }}</span>
            </p>
        </div>
        <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-5">
            <p class="text-sm font-medium text-gray-500">Imported On</p>
            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $import->created_at->format('d M Y, H:i') }}</p>
        </div>
    </div>

    <!-- Error Log -->
    @if($import->has_errors && !empty($import->error_log))
    <div class="bg-red-50 border border-red-200 rounded-xl p-5 mb-8">
        <h3 class="text-sm font-semibold text-red-800 mb-2">Import Errors</h3>
        <ul class="list-disc list-inside space-y-1">
            @foreach($import->error_log as $error)
            <li class="text-sm text-red-700">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Attendance Records -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden" x-data="{ editingId: null }">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Imported Records</h3>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Working Days</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Present</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Absent</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Leave</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Late</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Attendance %</th>
                    @if(auth()->user()->canManage())
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($import->monthlyAttendances as $attendance)
                <!-- Display Row -->
                <tr class="hover:bg-gray-50 transition-colors" x-show="editingId !== {{ $attendance->id }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $attendance->staff->name ?? 'Unknown' }}</div>
                        <div class="text-xs text-gray-500">{{ $attendance->staff->record_card_number ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                        {{ $attendance->total_working_days }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-green-600 font-medium">
                        {{ $attendance->days_present }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-red-600 font-medium">
                        {{ $attendance->days_absent }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-yellow-600 font-medium">
                        {{ $attendance->days_leave }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-orange-600 font-medium">
                        {{ $attendance->days_late }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $attendance->status_badge_class }}">
                            {{ $attendance->attendance_percentage }}%
                        </span>
                    </td>
                    @if(auth()->user()->canManage())
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <button @click="editingId = {{ $attendance->id }}"
                                class="font-medium text-indigo-600 hover:text-indigo-800 transition-colors mr-3">
                            Edit
                        </button>
                        <form method="POST" action="{{ route('attendance.records.destroy', $attendance) }}" class="inline"
                              onsubmit="return confirm('Delete this attendance record?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-medium text-red-600 hover:text-red-800 transition-colors">
                                Delete
                            </button>
                        </form>
                    </td>
                    @endif
                </tr>
                @if(auth()->user()->canManage())
                <!-- Edit Row -->
                <tr x-show="editingId === {{ $attendance->id }}" x-cloak class="bg-gray-50/50">
                    <td colspan="8" class="px-6 py-4">
                        <form method="POST" action="{{ route('attendance.records.update', $attendance) }}" class="flex items-end gap-4 flex-wrap">
                            @csrf
                            @method('PUT')
                            <div class="text-sm font-medium text-gray-900 min-w-[140px]">
                                {{ $attendance->staff->name ?? 'Unknown' }}
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Working Days</label>
                                <input type="number" name="total_working_days" value="{{ $attendance->total_working_days }}" min="1"
                                       class="w-20 rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Present</label>
                                <input type="number" name="days_present" value="{{ $attendance->days_present }}" min="0"
                                       class="w-20 rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Absent</label>
                                <input type="number" name="days_absent" value="{{ $attendance->days_absent }}" min="0"
                                       class="w-20 rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Leave</label>
                                <input type="number" name="days_leave" value="{{ $attendance->days_leave }}" min="0"
                                       class="w-20 rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Late</label>
                                <input type="number" name="days_late" value="{{ $attendance->days_late }}" min="0"
                                       class="w-20 rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="submit"
                                        class="px-4 py-2 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm text-sm transition-all duration-200">
                                    Save
                                </button>
                                <button type="button" @click="editingId = null"
                                        class="px-4 py-2 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-sm transition-all duration-200">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </td>
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        No attendance records found for this import.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
