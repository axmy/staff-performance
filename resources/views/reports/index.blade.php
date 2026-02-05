<x-layouts.app>
    <x-slot name="title">Reports</x-slot>

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Reports</h1>
        <p class="mt-1 text-sm text-gray-500">Generate and export performance reports</p>
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <!-- Staff Performance Report -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-100">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="bg-indigo-50 rounded-xl p-3">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-semibold text-gray-900">Staff Performance Report</h3>
                        <p class="mt-1 text-sm text-gray-500">Individual staff performance with attendance and training data</p>
                    </div>
                </div>
                <div class="mt-5 flex items-center gap-3">
                    <a href="{{ route('reports.staff-performance') }}"
                       class="px-6 py-3 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm">
                        View Report
                    </a>
                    <a href="{{ route('reports.export.staff-performance', ['year' => now()->year]) }}"
                       class="px-6 py-3 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
                        Export Excel
                    </a>
                </div>
            </div>
        </div>

        <!-- Attendance Summary Report -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-100">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="bg-green-50 rounded-xl p-3">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-semibold text-gray-900">Attendance Summary</h3>
                        <p class="mt-1 text-sm text-gray-500">Monthly attendance statistics by department</p>
                    </div>
                </div>
                <div class="mt-5 flex items-center gap-3">
                    <a href="{{ route('reports.attendance-summary') }}"
                       class="px-6 py-3 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm">
                        View Report
                    </a>
                    <a href="{{ route('reports.export.attendance-summary', ['year' => now()->year, 'month' => now()->month]) }}"
                       class="px-6 py-3 rounded-lg font-semibold text-white bg-green-600 hover:bg-green-700 active:bg-green-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-green-500 text-sm">
                        Export Excel
                    </a>
                </div>
            </div>
        </div>

        <!-- Training Summary Report -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-100">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="bg-blue-50 rounded-xl p-3">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-semibold text-gray-900">Training Summary</h3>
                        <p class="mt-1 text-sm text-gray-500">Training sessions with attendance and compliance rates</p>
                    </div>
                </div>
                <div class="mt-5 flex items-center gap-3">
                    <a href="{{ route('reports.training-summary') }}"
                       class="px-6 py-3 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm">
                        View Report
                    </a>
                    <a href="{{ route('reports.export.training-summary', ['year' => now()->year]) }}"
                       class="px-6 py-3 rounded-lg font-semibold text-white bg-blue-600 hover:bg-blue-700 active:bg-blue-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-sm">
                        Export Excel
                    </a>
                </div>
            </div>
        </div>

        <!-- Action Report -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-100">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="bg-amber-50 rounded-xl p-3">
                        <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-semibold text-gray-900">Action Report</h3>
                        <p class="mt-1 text-sm text-gray-500">Actions taken, pending, and feedback status</p>
                    </div>
                </div>
                <div class="mt-5 flex items-center gap-3">
                    <a href="{{ route('reports.action-report') }}"
                       class="px-6 py-3 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm">
                        View Report
                    </a>
                    <a href="{{ route('reports.export.action-report', ['year' => now()->year]) }}"
                       class="px-6 py-3 rounded-lg font-semibold text-white bg-amber-600 hover:bg-amber-700 active:bg-amber-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 text-sm">
                        Export Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
