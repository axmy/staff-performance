@props(['name', 'label', 'type' => 'date', 'value' => '', 'required' => false])

<div class="relative">
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'peer block w-full px-4 pt-6 pb-2 text-base text-gray-900 bg-white border rounded-lg focus:outline-none focus:ring-2 transition-colors duration-200 ' . ($errors->has($name) ? 'border-red-400 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-indigo-500 focus:border-indigo-500')]) }}
    >
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
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
