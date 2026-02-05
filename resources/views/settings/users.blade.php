<x-layouts.app>
    <x-slot name="title">User Management</x-slot>

    <x-settings-nav />

    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
            <p class="mt-1 text-sm text-gray-500">Manage system users and their roles</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 mb-6">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <x-form.input name="search" label="Search" :value="request('search')" />
                </div>
                <div class="w-48">
                    <x-form.select name="role" label="Role">
                        <option value="">All Roles</option>
                        <option value="admin" @if(request('role') == 'admin') selected @endif>Admin</option>
                        <option value="manager" @if(request('role') == 'manager') selected @endif>Manager</option>
                        <option value="user" @if(request('role') == 'user') selected @endif>User</option>
                    </x-form.select>
                </div>
                <div class="w-48">
                    <x-form.select name="status" label="Status">
                        <option value="">All Status</option>
                        <option value="active" @if(request('status') == 'active') selected @endif>Active</option>
                        <option value="inactive" @if(request('status') == 'inactive') selected @endif>Inactive</option>
                    </x-form.select>
                </div>
                <div class="flex space-x-2">
                    <button type="submit" class="inline-flex items-center px-6 py-3 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm">
                        Filter
                    </button>
                    <a href="{{ route('settings.users') }}" class="inline-flex items-center px-6 py-3 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if($user->avatar)
                                    <img class="h-10 w-10 rounded-full" src="{{ $user->avatar }}" alt="">
                                @else
                                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500">
                                        <span class="text-xs font-medium leading-none text-white">
                                            {{ substr($user->name, 0, 2) }}
                                        </span>
                                    </span>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <form method="POST" action="{{ route('settings.users.role', $user) }}" class="inline">
                            @csrf
                            @method('PUT')
                            <select name="role" onchange="this.form.submit()"
                                    class="text-sm rounded-lg border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                                <option value="admin" @if($user->role == 'admin') selected @endif>Admin</option>
                                <option value="manager" @if($user->role == 'manager') selected @endif>Manager</option>
                                <option value="user" @if($user->role == 'user') selected @endif>User</option>
                            </select>
                        </form>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($user->is_active) bg-green-100 text-green-800 @else bg-gray-100 text-gray-800 @endif">
                            @if($user->is_active) Active @else Inactive @endif
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <form method="POST" action="{{ route('settings.users.toggle', $user) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="text-blue-600 hover:text-blue-900">
                                @if($user->is_active) Deactivate @else Activate @endif
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($users->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $users->withQueryString()->links() }}
        </div>
        @endif
    </div>
</x-layouts.app>
