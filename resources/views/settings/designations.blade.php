<x-layouts.app>
    <x-slot name="title">Designations</x-slot>

    <x-settings-nav />

    <!-- Header + Create Form -->
    <div x-data="{ showCreateForm: false }">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Designations</h1>
            <p class="mt-1 text-sm text-gray-500">Manage job designations and positions</p>
        </div>
        <button @click="showCreateForm = !showCreateForm"
                class="inline-flex items-center px-6 py-3 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
            Add Designation
        </button>
    </div>
        <div x-show="showCreateForm" x-transition class="bg-white shadow-sm rounded-xl border border-gray-100 p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Create New Designation</h3>
            <form method="POST" action="{{ route('settings.designations.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-form.input name="name" label="Designation Title" :required="true" />
                    <x-form.input name="code" label="Designation Code" />
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
                        Create Designation
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Designations Table -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden" x-data="{ editingId: null }">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Designation Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff Count</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($designations as $designation)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $designation->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <code class="px-2 py-1 bg-gray-100 rounded text-xs text-gray-700">{{ $designation->code }}</code>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $designation->description ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $designation->staff_count ?? 0 }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button @click="editingId = editingId === {{ $designation->id }} ? null : {{ $designation->id }}"
                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                            Edit
                        </button>
                        <form method="POST" action="{{ route('settings.designations.destroy', $designation) }}" class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this designation?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>

                <!-- Edit Row -->
                <tr x-show="editingId === {{ $designation->id }}" x-transition class="bg-indigo-50/50">
                    <td colspan="5" class="px-6 py-4">
                        <form method="POST" action="{{ route('settings.designations.update', $designation) }}" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Designation Title</label>
                                    <input type="text" name="name" value="{{ $designation->name }}" required
                                           class="block w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Code</label>
                                    <input type="text" name="code" value="{{ $designation->code }}"
                                           class="block w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                    <input type="text" name="description" value="{{ $designation->description }}"
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
                        No designations found. Create one to get started.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($designations->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $designations->links() }}
        </div>
        @endif
    </div>
</x-layouts.app>
