<x-layouts.app>
    <x-slot name="title">{{ $training->title }}</x-slot>

    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('training.index') }}"
               class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors mb-3 group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Back to Training List
            </a>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $training->title }}</h1>
            @if($training->description)
                <p class="mt-1.5 text-sm text-gray-500 max-w-2xl leading-relaxed">{{ $training->description }}</p>
            @endif
        </div>
        <div class="flex items-center gap-3">
            @if($training->status === 'upcoming' || $training->status === 'ongoing')
                <a href="{{ route('training.edit', $training) }}"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                    </svg>
                    Edit Training
                </a>
            @endif
            @if($training->status === 'ongoing' || $training->status === 'upcoming')
                <a href="{{ route('training.attendance', $training) }}"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-lg font-semibold text-white bg-green-600 hover:bg-green-700 active:bg-green-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-green-500 text-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Mark Attendance
                </a>
            @endif
        </div>
    </div>

    <!-- Training Details Card -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 mb-8">
        <div class="px-6 py-6 sm:px-8 sm:py-7">
            <div class="flex items-start justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Training Details</h2>
                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full
                    @if($training->status === 'upcoming') bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20
                    @elseif($training->status === 'ongoing') bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20
                    @elseif($training->status === 'completed') bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20
                    @else bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20 @endif">
                    {{ ucfirst($training->status) }}
                </span>
            </div>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-5">
                <div class="sm:col-span-1">
                    <dt class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Trainer</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $training->trainer }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Date & Time</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $training->scheduled_date?->format('d M Y') ?? '-' }} at {{ $training->formatted_time ?? '-' }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Location</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $training->location ?? '-' }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Duration</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $training->duration_text ?? '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <!-- Assigned Staff -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-6">
            <div class="flex items-center gap-4">
                <div class="bg-indigo-50 rounded-xl p-3">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Assigned Staff</p>
                    <p class="text-2xl font-bold text-gray-900 tabular-nums">{{ $training->notifications->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Present -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-6">
            <div class="flex items-center gap-4">
                <div class="bg-green-50 rounded-xl p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Present</p>
                    <p class="text-2xl font-bold text-gray-900 tabular-nums">{{ $training->attendances->where('attendance_status', 'present')->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Absent -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-6">
            <div class="flex items-center gap-4">
                <div class="bg-red-50 rounded-xl p-3">
                    <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Absent</p>
                    <p class="text-2xl font-bold text-gray-900 tabular-nums">{{ $training->attendances->where('attendance_status', 'absent')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Assigned Staff Table -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 sm:px-8 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Assigned Staff Members</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Staff</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Record Card</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Attendance Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Notified</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($training->notifications as $notification)
                    @php
                        $attendance = $training->attendances->where('staff_id', $notification->staff_id)->first();
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-sm">
                                    <span class="text-sm font-semibold leading-none text-white">
                                        {{ substr($notification->staff->name, 0, 2) }}
                                    </span>
                                </span>
                                <div class="text-sm font-semibold text-gray-900">{{ $notification->staff->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600 font-mono">{{ $notification->staff->record_card_number }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">{{ $notification->staff->department?->name ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($attendance)
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full
                                    @if($attendance->attendance_status === 'present') bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20
                                    @elseif($attendance->attendance_status === 'late') bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20
                                    @elseif($attendance->attendance_status === 'absent') bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20
                                    @else bg-gray-50 text-gray-600 ring-1 ring-inset ring-gray-500/20 @endif">
                                    {{ ucfirst($attendance->attendance_status) }}
                                    @if($attendance->absence_reason)
                                        <span class="ml-1 cursor-help" title="{{ $attendance->absence_reason }}">*</span>
                                    @endif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-50 text-gray-500 ring-1 ring-inset ring-gray-500/20">
                                    Not Marked
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($notification->notified_at)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                    </svg>
                                    Notified
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-50 text-gray-500 ring-1 ring-inset ring-gray-500/20">
                                    Not Sent
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-1 max-w-xs">
                                @if($attendance && $attendance->absence_reason)
                                    <p class="text-sm text-gray-700">{{ $attendance->absence_reason }}</p>
                                @endif
                                @if($notification->response_notes)
                                    <p class="text-sm text-gray-500 italic">{{ $notification->response_notes }}</p>
                                @endif
                                @if(!($attendance && $attendance->absence_reason) && !$notification->response_notes)
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                    <svg class="w-7 h-7 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-900 mb-1">No staff members assigned</p>
                                <p class="text-sm text-gray-500">Staff members assigned to this training will appear here.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
