<x-layouts.app>
    <x-slot name="title">Staff Actions</x-slot>

    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Staff Actions</h1>
            <p class="mt-1 text-sm text-gray-500">Manage corrective actions, warnings, and performance improvement plans</p>
        </div>
        <a href="{{ route('actions.create') }}"
           class="inline-flex items-center justify-center px-6 py-3 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Create Action
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 mb-8">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Status Filter -->
                <x-form.select name="status" label="Status">
                    <option value="">All Status</option>
                    <option value="pending" @if(request('status') == 'pending') selected @endif>Pending</option>
                    <option value="in_progress" @if(request('status') == 'in_progress') selected @endif>In Progress</option>
                    <option value="completed" @if(request('status') == 'completed') selected @endif>Completed</option>
                    <option value="cancelled" @if(request('status') == 'cancelled') selected @endif>Cancelled</option>
                    <option value="escalated" @if(request('status') == 'escalated') selected @endif>Escalated</option>
                </x-form.select>

                <!-- Staff Filter -->
                <x-form.select name="staff_id" label="Staff Member">
                    <option value="">All Staff</option>
                    @foreach($staff as $member)
                        <option value="{{ $member->id }}" @if(request('staff_id') == $member->id) selected @endif>
                            {{ $member->name }}
                        </option>
                    @endforeach
                </x-form.select>

                <!-- Action Type Filter -->
                <x-form.select name="action_type_id" label="Action Type">
                    <option value="">All Types</option>
                    @foreach($actionTypes as $type)
                        <option value="{{ $type->id }}" @if(request('action_type_id') == $type->id) selected @endif>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </x-form.select>

                <!-- Year Filter -->
                <x-form.select name="year" label="Year">
                    <option value="">All Years</option>
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" @if(request('year', '') == $y) selected @endif>{{ $y }}</option>
                    @endfor
                </x-form.select>

                <!-- Month Filter -->
                <x-form.select name="month" label="Month">
                    <option value="">All Months</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" @if(request('month', '') == $m) selected @endif>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endfor
                </x-form.select>

                <!-- Buttons -->
                <div class="flex items-end gap-2">
                    <button type="submit"
                            class="px-6 py-3 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
                        <svg class="w-4 h-4 inline-block mr-1.5 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('actions.index') }}"
                       class="px-6 py-3 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Actions Table -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Staff</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Action Type</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Period</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Assigned To</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($actions as $action)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-sm">
                                        <span class="text-sm font-medium leading-none text-white">
                                            {{ substr($action->staff->name, 0, 2) }}
                                        </span>
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $action->staff->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $action->staff->record_card_number }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ $action->actionType->name }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $action->period }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold rounded-full {{ $action->status_badge_class }}">
                                {{ $action->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($action->due_date)
                                <span class="text-gray-900">{{ $action->due_date->format('d M Y') }}</span>
                                @if($action->is_overdue)
                                    <span class="ml-1.5 inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700">Overdue</span>
                                @endif
                            @else
                                <span class="text-gray-400">--</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $action->assignee?->name ?? 'Unassigned' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('actions.show', $action) }}"
                               class="inline-flex items-center text-indigo-600 hover:text-indigo-900 font-semibold transition-colors duration-150">
                                View
                                <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                </svg>
                                <p class="text-sm font-medium text-gray-500">No staff actions found</p>
                                <p class="text-xs text-gray-400 mt-1">Try adjusting your filters or create a new action.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($actions->hasPages())
        <div class="bg-gray-50/50 px-4 py-3 border-t border-gray-100 sm:px-6">
            {{ $actions->withQueryString()->links() }}
        </div>
        @endif
    </div>
</x-layouts.app>
