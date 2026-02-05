<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Staff Performance' }} - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen" x-data="{ mobileMenu: false }">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200/80 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="text-xl font-bold text-indigo-600">
                                Staff Performance
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden sm:ml-8 sm:flex sm:space-x-1">
                            <a href="{{ route('dashboard') }}"
                               class="@if(request()->routeIs('dashboard')) bg-indigo-50 text-indigo-700 border-indigo-500 @else text-gray-600 border-transparent hover:text-gray-900 hover:bg-gray-50 @endif inline-flex items-center px-3 pt-1 border-b-2 text-sm font-medium rounded-t-lg transition-colors duration-150">
                                Dashboard
                            </a>
                            <a href="{{ route('staff.index') }}"
                               class="@if(request()->routeIs('staff.*')) bg-indigo-50 text-indigo-700 border-indigo-500 @else text-gray-600 border-transparent hover:text-gray-900 hover:bg-gray-50 @endif inline-flex items-center px-3 pt-1 border-b-2 text-sm font-medium rounded-t-lg transition-colors duration-150">
                                Staff
                            </a>
                            <a href="{{ route('training.index') }}"
                               class="@if(request()->routeIs('training.*')) bg-indigo-50 text-indigo-700 border-indigo-500 @else text-gray-600 border-transparent hover:text-gray-900 hover:bg-gray-50 @endif inline-flex items-center px-3 pt-1 border-b-2 text-sm font-medium rounded-t-lg transition-colors duration-150">
                                Training
                            </a>
                            <a href="{{ route('attendance.index') }}"
                               class="@if(request()->routeIs('attendance.*')) bg-indigo-50 text-indigo-700 border-indigo-500 @else text-gray-600 border-transparent hover:text-gray-900 hover:bg-gray-50 @endif inline-flex items-center px-3 pt-1 border-b-2 text-sm font-medium rounded-t-lg transition-colors duration-150">
                                Attendance
                            </a>
                            <a href="{{ route('actions.index') }}"
                               class="@if(request()->routeIs('actions.*')) bg-indigo-50 text-indigo-700 border-indigo-500 @else text-gray-600 border-transparent hover:text-gray-900 hover:bg-gray-50 @endif inline-flex items-center px-3 pt-1 border-b-2 text-sm font-medium rounded-t-lg transition-colors duration-150">
                                Actions
                            </a>
                            <a href="{{ route('reports.index') }}"
                               class="@if(request()->routeIs('reports.*')) bg-indigo-50 text-indigo-700 border-indigo-500 @else text-gray-600 border-transparent hover:text-gray-900 hover:bg-gray-50 @endif inline-flex items-center px-3 pt-1 border-b-2 text-sm font-medium rounded-t-lg transition-colors duration-150">
                                Reports
                            </a>
                            @if(auth()->user()->isAdmin())
                            <div x-data="{ settingsOpen: false }" class="relative">
                                <button @click="settingsOpen = !settingsOpen"
                                        class="@if(request()->routeIs('settings.*')) bg-indigo-50 text-indigo-700 border-indigo-500 @else text-gray-600 border-transparent hover:text-gray-900 hover:bg-gray-50 @endif inline-flex items-center px-3 pt-1 border-b-2 text-sm font-medium rounded-t-lg transition-colors duration-150 h-full gap-1">
                                    Settings
                                    <svg class="w-3.5 h-3.5 transition-transform" :class="settingsOpen && 'rotate-180'" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                <div x-show="settingsOpen" @click.away="settingsOpen = false"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute left-0 mt-0 w-52 rounded-xl shadow-lg bg-white ring-1 ring-black/5 z-50 py-1">
                                    <a href="{{ route('settings.departments') }}" class="@if(request()->routeIs('settings.departments*')) bg-indigo-50 text-indigo-700 @else text-gray-700 hover:bg-gray-50 @endif flex items-center gap-2.5 px-4 py-2.5 text-sm transition-colors">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                                        Departments
                                    </a>
                                    <a href="{{ route('settings.designations') }}" class="@if(request()->routeIs('settings.designations*')) bg-indigo-50 text-indigo-700 @else text-gray-700 hover:bg-gray-50 @endif flex items-center gap-2.5 px-4 py-2.5 text-sm transition-colors">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm-3.375 6.166a3.001 3.001 0 015.003.006"/></svg>
                                        Designations
                                    </a>
                                    <a href="{{ route('settings.action-types') }}" class="@if(request()->routeIs('settings.action-types*')) bg-indigo-50 text-indigo-700 @else text-gray-700 hover:bg-gray-50 @endif flex items-center gap-2.5 px-4 py-2.5 text-sm transition-colors">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                                        Action Types
                                    </a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <a href="{{ route('settings.triggers') }}" class="@if(request()->routeIs('settings.triggers*')) bg-indigo-50 text-indigo-700 @else text-gray-700 hover:bg-gray-50 @endif flex items-center gap-2.5 px-4 py-2.5 text-sm transition-colors">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                                        Triggers
                                    </a>
                                    <a href="{{ route('settings.users') }}" class="@if(request()->routeIs('settings.users*')) bg-indigo-50 text-indigo-700 @else text-gray-700 hover:bg-gray-50 @endif flex items-center gap-2.5 px-4 py-2.5 text-sm transition-colors">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                                        Users
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center space-x-2 text-sm font-medium text-gray-600 hover:text-gray-900 rounded-lg px-3 py-2 hover:bg-gray-50 transition-colors duration-150 focus:outline-none">
                                @if(auth()->user()->avatar)
                                    <img class="h-8 w-8 rounded-full ring-2 ring-white" src="{{ auth()->user()->avatar }}" alt="">
                                @else
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-indigo-500 ring-2 ring-white">
                                        <span class="text-sm font-medium leading-none text-white">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </span>
                                    </span>
                                @endif
                                <span>{{ auth()->user()->name }}</span>
                                <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="origin-top-right absolute right-0 mt-2 w-48 rounded-xl shadow-lg bg-white ring-1 ring-black/5 z-50 py-1">
                                <span class="block px-4 py-2 text-xs text-gray-400">
                                    Role: {{ ucfirst(auth()->user()->role) }}
                                </span>
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="flex items-center sm:hidden">
                        <button @click="mobileMenu = !mobileMenu" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                            <svg x-show="!mobileMenu" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            <svg x-show="mobileMenu" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div x-show="mobileMenu" x-transition class="sm:hidden border-t border-gray-200">
                <div class="pt-2 pb-3 space-y-1 px-3">
                    <a href="{{ route('dashboard') }}" class="@if(request()->routeIs('dashboard')) bg-indigo-50 text-indigo-700 @else text-gray-600 hover:bg-gray-50 @endif block px-3 py-2 rounded-lg text-base font-medium transition-colors">Dashboard</a>
                    <a href="{{ route('staff.index') }}" class="@if(request()->routeIs('staff.*')) bg-indigo-50 text-indigo-700 @else text-gray-600 hover:bg-gray-50 @endif block px-3 py-2 rounded-lg text-base font-medium transition-colors">Staff</a>
                    <a href="{{ route('training.index') }}" class="@if(request()->routeIs('training.*')) bg-indigo-50 text-indigo-700 @else text-gray-600 hover:bg-gray-50 @endif block px-3 py-2 rounded-lg text-base font-medium transition-colors">Training</a>
                    <a href="{{ route('attendance.index') }}" class="@if(request()->routeIs('attendance.*')) bg-indigo-50 text-indigo-700 @else text-gray-600 hover:bg-gray-50 @endif block px-3 py-2 rounded-lg text-base font-medium transition-colors">Attendance</a>
                    <a href="{{ route('actions.index') }}" class="@if(request()->routeIs('actions.*')) bg-indigo-50 text-indigo-700 @else text-gray-600 hover:bg-gray-50 @endif block px-3 py-2 rounded-lg text-base font-medium transition-colors">Actions</a>
                    <a href="{{ route('reports.index') }}" class="@if(request()->routeIs('reports.*')) bg-indigo-50 text-indigo-700 @else text-gray-600 hover:bg-gray-50 @endif block px-3 py-2 rounded-lg text-base font-medium transition-colors">Reports</a>
                    @if(auth()->user()->isAdmin())
                    <div x-data="{ settingsExpanded: {{ request()->routeIs('settings.*') ? 'true' : 'false' }} }">
                        <button @click="settingsExpanded = !settingsExpanded"
                                class="@if(request()->routeIs('settings.*')) bg-indigo-50 text-indigo-700 @else text-gray-600 hover:bg-gray-50 @endif flex items-center justify-between w-full px-3 py-2 rounded-lg text-base font-medium transition-colors">
                            Settings
                            <svg class="w-4 h-4 transition-transform" :class="settingsExpanded && 'rotate-180'" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div x-show="settingsExpanded" x-transition class="pl-4 mt-1 space-y-1">
                            <a href="{{ route('settings.departments') }}" class="@if(request()->routeIs('settings.departments*')) text-indigo-700 bg-indigo-50 @else text-gray-500 hover:bg-gray-50 @endif block px-3 py-2 rounded-lg text-sm font-medium transition-colors">Departments</a>
                            <a href="{{ route('settings.designations') }}" class="@if(request()->routeIs('settings.designations*')) text-indigo-700 bg-indigo-50 @else text-gray-500 hover:bg-gray-50 @endif block px-3 py-2 rounded-lg text-sm font-medium transition-colors">Designations</a>
                            <a href="{{ route('settings.action-types') }}" class="@if(request()->routeIs('settings.action-types*')) text-indigo-700 bg-indigo-50 @else text-gray-500 hover:bg-gray-50 @endif block px-3 py-2 rounded-lg text-sm font-medium transition-colors">Action Types</a>
                            <a href="{{ route('settings.triggers') }}" class="@if(request()->routeIs('settings.triggers*')) text-indigo-700 bg-indigo-50 @else text-gray-500 hover:bg-gray-50 @endif block px-3 py-2 rounded-lg text-sm font-medium transition-colors">Triggers</a>
                            <a href="{{ route('settings.users') }}" class="@if(request()->routeIs('settings.users*')) text-indigo-700 bg-indigo-50 @else text-gray-500 hover:bg-gray-50 @endif block px-3 py-2 rounded-lg text-sm font-medium transition-colors">Users</a>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="pt-3 pb-3 px-3 border-t border-gray-200">
                    <div class="flex items-center px-3 mb-3">
                        @if(auth()->user()->avatar)
                            <img class="h-10 w-10 rounded-full" src="{{ auth()->user()->avatar }}" alt="">
                        @else
                            <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500">
                                <span class="text-sm font-medium leading-none text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </span>
                        @endif
                        <div class="ml-3">
                            <div class="text-base font-medium text-gray-800">{{ auth()->user()->name }}</div>
                            <div class="text-sm text-gray-500">{{ ucfirst(auth()->user()->role) }}</div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 rounded-lg text-base font-medium text-gray-600 hover:bg-gray-50 transition-colors">Logout</button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4" x-data="{ show: true }" x-show="show" x-transition.duration.300ms>
            <div class="flex items-center justify-between bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl" role="alert">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-green-500 hover:text-green-700 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4" x-data="{ show: true }" x-show="show" x-transition.duration.300ms>
            <div class="flex items-center justify-between bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl" role="alert">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-red-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-red-500 hover:text-red-700 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        @endif

        <!-- Page Content -->
        <main class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
