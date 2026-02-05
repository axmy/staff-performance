<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Staff Performance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gradient-to-br from-indigo-50 via-white to-indigo-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full">
            <div class="bg-white py-10 px-6 shadow-xl rounded-2xl sm:px-10 border border-gray-100">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-indigo-600">Staff Performance</h1>
                    <p class="mt-2 text-sm text-gray-600">
                        Track attendance, training, and performance actions
                    </p>
                </div>

                @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
                @endif

                @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
                @endif

                <form method="POST" action="{{ route('login.attempt') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div x-data="{ focused: false, filled: false }" x-init="setTimeout(() => { filled = $refs.input.value !== '' }, 100)" class="relative">
                        <input
                            x-ref="input"
                            id="email" name="email" type="email" autocomplete="email" required
                            value="{{ old('email') }}"
                            @focus="focused = true"
                            @blur="focused = false; filled = $refs.input.value !== ''"
                            @input="filled = $refs.input.value !== ''"
                            placeholder=" "
                            class="peer block w-full px-4 pt-6 pb-2 text-base text-gray-900 bg-white border rounded-lg focus:outline-none focus:ring-2 placeholder-transparent transition-colors duration-200 {{ $errors->has('email') ? 'border-red-400 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-indigo-500 focus:border-indigo-500' }}"
                        >
                        <label for="email"
                            class="absolute left-4 top-4 origin-[0] transform transition-all duration-200 pointer-events-none {{ $errors->has('email') ? 'text-red-600' : '' }}"
                            :class="focused || filled ? '-translate-y-3 scale-75 text-{{ $errors->has('email') ? 'red' : 'indigo' }}-600' : 'translate-y-0 scale-100 text-gray-500'"
                        >
                            Email address
                        </label>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div x-data="{ focused: false, filled: false }" x-init="setTimeout(() => { filled = $refs.input.value !== '' }, 100)" class="relative">
                        <input
                            x-ref="input"
                            id="password" name="password" type="password" autocomplete="current-password" required
                            @focus="focused = true"
                            @blur="focused = false; filled = $refs.input.value !== ''"
                            @input="filled = $refs.input.value !== ''"
                            placeholder=" "
                            class="peer block w-full px-4 pt-6 pb-2 text-base text-gray-900 bg-white border rounded-lg focus:outline-none focus:ring-2 placeholder-transparent transition-colors duration-200 {{ $errors->has('password') ? 'border-red-400 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-indigo-500 focus:border-indigo-500' }}"
                        >
                        <label for="password"
                            class="absolute left-4 top-4 origin-[0] transform transition-all duration-200 pointer-events-none {{ $errors->has('password') ? 'text-red-600' : '' }}"
                            :class="focused || filled ? '-translate-y-3 scale-75 text-{{ $errors->has('password') ? 'red' : 'indigo' }}-600' : 'translate-y-0 scale-100 text-gray-500'"
                        >
                            Password
                        </label>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me Toggle -->
                    <label for="remember" class="inline-flex items-center cursor-pointer">
                        <input type="hidden" name="remember" value="0">
                        <input
                            type="checkbox"
                            name="remember"
                            id="remember"
                            value="1"
                            class="sr-only peer"
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-indigo-300 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        <span class="ms-3 text-sm font-medium text-gray-700">Remember me</span>
                    </label>

                    <button type="submit"
                            class="w-full flex justify-center px-6 py-3 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Sign in
                    </button>
                </form>

                <div class="mt-6 text-center text-xs text-gray-500">
                    <p>Only authorized users can access this system.</p>
                    <p>Contact your administrator if you need access.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
