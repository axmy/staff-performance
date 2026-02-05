@props(['name', 'label', 'required' => false])

<div class="relative">
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'peer block w-full px-4 pt-6 pb-2 text-base text-gray-900 bg-white border rounded-lg focus:outline-none focus:ring-2 appearance-none transition-colors duration-200 ' . ($errors->has($name) ? 'border-red-400 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-indigo-500 focus:border-indigo-500')]) }}
    >
        {{ $slot }}
    </select>
    <label
        for="{{ $name }}"
        @class([
            'absolute left-4 top-4 origin-[0] -translate-y-3 scale-75 transform transition-all duration-200 pointer-events-none',
            'text-red-600' => $errors->has($name),
            'text-indigo-600' => !$errors->has($name),
        ])
    >
        {{ $label }}@if($required) *@endif
    </label>
    <!-- Custom chevron -->
    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
        <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
    </div>
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
