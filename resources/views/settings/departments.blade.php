<x-layouts.app>
    <x-slot name="title">Departments</x-slot>

    <x-settings-nav />

    <!-- Header + Create Form -->
    <div x-data="{ showCreateForm: false }">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Departments</h1>
            <p class="mt-1 text-sm text-gray-500">Manage departments and their staff members</p>
        </div>
        <button @click="showCreateForm = !showCreateForm"
                class="inline-flex items-center px-6 py-3 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
            Add Department
        </button>
    </div>
        <div x-show="showCreateForm" x-transition class="bg-white shadow-sm rounded-xl border border-gray-100 p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Create New Department</h3>
            <form method="POST" action="{{ route('settings.departments.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-form.input name="name" label="Department Name" :required="true" />
                    <x-form.input name="code" label="Department Code" />
                    <div class="sm:col-span-2">
                        <x-form.textarea name="description" label="Description" rows="2" />
                    </div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" @click="showCreateForm = false"
                            class="px-6 py-3 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-6 py-3 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
                        Create Department
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Departments Table -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden" x-data="{ editingId: null }">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff Count</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($departments as $department)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $department->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <code class="px-2 py-1 bg-gray-100 rounded text-xs text-gray-700">{{ $department->code }}</code>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $department->description ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            {{ $department->staff_count ?? 0 }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button @click="editingId = editingId === {{ $department->id }} ? null : {{ $department->id }}"
                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                            Edit
                        </button>
                        <form method="POST" action="{{ route('settings.departments.destroy', $department) }}" class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this department?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>

                <!-- Edit Row -->
                <tr x-show="editingId === {{ $department->id }}" x-transition class="bg-indigo-50/50">
                    <td colspan="5" class="px-6 py-4">
                        <form method="POST" action="{{ route('settings.departments.update', $department) }}" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Department Name</label>
                                    <input type="text" name="name" value="{{ $department->name }}" required
                                           class="block w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Code</label>
                                    <input type="text" name="code" value="{{ $department->code }}"
                                           class="block w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                    <input type="text" name="description" value="{{ $department->description }}"
                                           class="block w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                                </div>
                                <div class="flex items-end space-x-2">
                                    <button type="submit"
                                            class="px-4 py-2 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition-all duration-200 text-sm">
                                        Save
                                    </button>
                                    <button type="button" @click="editingId = null"
                                            class="px-4 py-2 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        No departments found. Create one to get started.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($departments->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $departments->links() }}
        </div>
        @endif
    </div>
</x-layouts.app>
