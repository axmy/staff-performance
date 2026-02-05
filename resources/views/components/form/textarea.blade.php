@props(['name', 'label', 'value' => '', 'required' => false, 'rows' => 3])

<div x-data="{ focused: false, filled: false }" x-init="setTimeout(() => { filled = $refs.textarea.value !== '' }, 100)" class="relative">
    <textarea
        x-ref="textarea"
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        @if($required) required @endif
        @focus="focused = true"
        @blur="focused = false; filled = $refs.textarea.value !== ''"
        @input="filled = $refs.textarea.value !== ''"
        placeholder=" "
        {{ $attributes->merge(['class' => 'peer block w-full px-4 pt-6 pb-2 text-base text-gray-900 bg-white border rounded-lg focus:outline-none focus:ring-2 placeholder-transparent transition-colors duration-200 ' . ($errors->has($name) ? 'border-red-400 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-indigo-500 focus:border-indigo-500')]) }}
    >{{ old($name, $value) }}</textarea>
    <label
        for="{{ $name }}"
        @class([
            'absolute left-4 top-4 origin-[0] transform transition-all duration-200 pointer-events-none',
            'text-red-600' => $errors->has($name),
        ])
        :class="focused || filled ? '-translate-y-3 scale-75 text-{{ $errors->has($name) ? 'red' : 'indigo' }}-600' : 'translate-y-0 scale-100 text-gray-500'"
    >
        {{ $label }}@if($required) *@endif
    </label>
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
