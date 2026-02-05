<x-layouts.app>
    <x-slot name="title">Action Types</x-slot>

    <x-settings-nav />

    <div x-data="{ showCreateModal: false, showEditModal: false, editingActionType: null }">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Action Types</h1>
            <p class="mt-1 text-sm text-gray-500">Manage different types of actions that can be triggered</p>
        </div>
        <button @click="showCreateModal = true"
                class="inline-flex items-center px-6 py-3 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
            Create Action Type
        </button>
    </div>

    <!-- Action Types Table -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($actionTypes as $actionType)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $actionType->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <code class="px-2 py-1 bg-gray-100 rounded text-xs text-gray-700">{{ $actionType->code }}</code>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $actionType->description ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button @click="showEditModal = true; editingActionType = {{ json_encode($actionType) }}"
                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                            Edit
                        </button>
                        <form method="POST" action="{{ route('settings.action-types.destroy', $actionType) }}" class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this action type?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                        No action types found. Create one to get started.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($actionTypes->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $actionTypes->links() }}
        </div>
        @endif

        <!-- Create Modal -->
        <div x-show="showCreateModal" x-transition.opacity
             class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div @click.away="showCreateModal = false" class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Create New Action Type</h3>
                    <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('settings.action-types.store') }}" class="p-6">
                    @csrf

                    <div class="space-y-4 mb-6">
                        <x-form.input name="name" label="Action Type Name" :required="true" />
                        <x-form.input name="code" label="Code" :required="true" />
                        <x-form.textarea name="description" label="Description" rows="3" />
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="showCreateModal = false"
                                class="px-6 py-3 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-6 py-3 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
                            Create Action Type
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="showEditModal" x-transition.opacity
             class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div @click.away="showEditModal = false" class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Edit Action Type</h3>
                    <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <template x-if="editingActionType">
                    <form :action="`{{ url('settings/action-types') }}/${editingActionType.id}`" method="POST" class="p-6">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4 mb-6">
                            <div class="relative">
                                <input type="text" name="name" x-model="editingActionType.name" required
                                       placeholder=" "
                                       class="peer block w-full px-4 pt-6 pb-2 text-base text-gray-900 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder-transparent transition-colors duration-200">
                                <label class="absolute left-4 top-4 origin-[0] -translate-y-3 scale-75 text-indigo-600 transform transition-all duration-200 pointer-events-none">
                                    Action Type Name *
                                </label>
                            </div>

                            <div class="relative">
                                <input type="text" name="code" x-model="editingActionType.code" required
                                       placeholder=" "
                                       class="peer block w-full px-4 pt-6 pb-2 text-base text-gray-900 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder-transparent transition-colors duration-200">
                                <label class="absolute left-4 top-4 origin-[0] -translate-y-3 scale-75 text-indigo-600 transform transition-all duration-200 pointer-events-none">
                                    Code *
                                </label>
                            </div>

                            <div class="relative">
                                <textarea name="description" rows="3" x-model="editingActionType.description"
                                          placeholder=" "
                                          class="peer block w-full px-4 pt-6 pb-2 text-base text-gray-900 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder-transparent transition-colors duration-200"></textarea>
                                <label class="absolute left-4 top-4 origin-[0] -translate-y-3 scale-75 text-indigo-600 transform transition-all duration-200 pointer-events-none">
                                    Description
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" @click="showEditModal = false"
                                    class="px-6 py-3 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-6 py-3 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
                                Update Action Type
                            </button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>
</x-layouts.app>
