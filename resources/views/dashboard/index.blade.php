<x-layouts.app>
    <x-slot name="title">Dashboard</x-slot>

    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500">
                Overview for {{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET" class="flex items-center gap-2">
                <select name="month" class="rounded-lg border-gray-300 bg-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors" onchange="this.form.submit()">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" @if($m == $month) selected @endif>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endfor
                </select>
                <select name="year" class="rounded-lg border-gray-300 bg-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors" onchange="this.form.submit()">
                    @for($y = now()->year - 2; $y <= now()->year + 1; $y++)
                        <option value="{{ $y }}" @if($y == $year) selected @endif>{{ $y }}</option>
                    @endfor
                </select>
            </form>
            <a href="{{ route('dashboard.export', ['year' => $year, 'month' => $month, 'format' => 'excel']) }}"
               class="inline-flex items-center px-4 py-2 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm shadow-sm">
                <svg class="h-4 w-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export
            </a>
        </div>
    </div>

    <!-- Overview Stats -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-indigo-50 rounded-xl p-3">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Staff</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['overview']['total_staff'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-50 rounded-xl p-3">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Attendance Rate</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['attendance']['attendance_rate'] }}%</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-50 rounded-xl p-3">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Training Compliance</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['training']['compliance_rate'] }}%</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-amber-50 rounded-xl p-3">
                    <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pending Actions</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['actions']['pending'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Attendance Summary -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-100">
            <div class="px-6 py-5">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Attendance Summary</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-green-50 p-4 rounded-xl border border-green-100">
                        <p class="text-sm font-medium text-green-700">Present Days</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['attendance']['total_present'] }}</p>
                    </div>
                    <div class="bg-red-50 p-4 rounded-xl border border-red-100">
                        <p class="text-sm font-medium text-red-700">Absent Days</p>
                        <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['attendance']['total_absent'] }}</p>
                    </div>
                    <div class="bg-amber-50 p-4 rounded-xl border border-amber-100">
                        <p class="text-sm font-medium text-amber-700">Late Days</p>
                        <p class="text-2xl font-bold text-amber-600 mt-1">{{ $stats['attendance']['total_late'] }}</p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                        <p class="text-sm font-medium text-blue-700">Leave Days</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['attendance']['total_leave'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Training Summary -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-100">
            <div class="px-6 py-5">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Training Summary</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                        <p class="text-sm font-medium text-indigo-700">Total Sessions</p>
                        <p class="text-2xl font-bold text-indigo-600 mt-1">{{ $stats['training']['total_sessions'] }}</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-xl border border-green-100">
                        <p class="text-sm font-medium text-green-700">Completed</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['training']['completed_sessions'] }}</p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-xl border border-blue-100">
                        <p class="text-sm font-medium text-blue-700">Upcoming</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['training']['upcoming_sessions'] }}</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-xl border border-purple-100">
                        <p class="text-sm font-medium text-purple-700">Total Attended</p>
                        <p class="text-2xl font-bold text-purple-600 mt-1">{{ $stats['training']['total_attended'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Staff at Risk -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-100">
            <div class="px-6 py-5">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Staff at Risk</h3>
                @if(count($stats['staffAtRisk']) > 0)
                <div class="space-y-3">
                    @foreach($stats['staffAtRisk'] as $item)
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-xl border border-red-100">
                        <div>
                            <p class="font-medium text-gray-900 text-sm">{{ $item['staff']->name }}</p>
                            <p class="text-xs text-gray-500">{{ $item['staff']->record_card_number }}</p>
                        </div>
                        <div class="text-right flex flex-wrap gap-1 justify-end">
                            @foreach($item['risks'] as $risk)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-medium bg-red-100 text-red-700 border border-red-200">
                                {{ $risk['trigger'] }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-400 text-center py-8 text-sm">No staff at risk this period</p>
                @endif
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-100">
            <div class="px-6 py-5">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Recent Activity</h3>
                @if(count($stats['recentActivity']) > 0)
                <div class="flow-root">
                    <ul class="-mb-8">
                        @foreach($stats['recentActivity'] as $index => $activity)
                        <li>
                            <div class="relative pb-8">
                                @if($index < count($stats['recentActivity']) - 1)
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-4 ring-white
                                            @if($activity['type'] === 'action') bg-amber-100 @else bg-blue-100 @endif">
                                            @if($activity['type'] === 'action')
                                            <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/>
                                            </svg>
                                            @else
                                            <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/>
                                            </svg>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-700">{{ $activity['title'] }}</p>
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $activity['subtitle'] }}</p>
                                        </div>
                                        <div class="text-right text-xs whitespace-nowrap text-gray-400">
                                            {{ $activity['time']->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @else
                <p class="text-gray-400 text-center py-8 text-sm">No recent activity</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Monthly Trends Chart -->
    <div class="mt-6 bg-white shadow-sm rounded-xl border border-gray-100">
        <div class="px-6 py-5">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Monthly Trends - {{ $year }}</h3>
            <canvas id="trendsChart" height="100"></canvas>
        </div>
    </div>

    @push('scripts')
    <script>
        const ctx = document.getElementById('trendsChart').getContext('2d');
        const trendsData = @json($stats['monthlyTrends']);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: trendsData.map(d => d.month),
                datasets: [
                    {
                        label: 'Attendance Rate (%)',
                        data: trendsData.map(d => d.attendance_rate),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgb(34, 197, 94)',
                    },
                    {
                        label: 'Training Sessions',
                        data: trendsData.map(d => d.training_count),
                        borderColor: 'rgb(99, 102, 241)',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgb(99, 102, 241)',
                    },
                    {
                        label: 'Actions',
                        data: trendsData.map(d => d.action_count),
                        borderColor: 'rgb(245, 158, 11)',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgb(245, 158, 11)',
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-layouts.app>
