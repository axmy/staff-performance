@props(['name', 'label', 'checked' => false])

<label for="{{ $name }}" class="inline-flex items-center cursor-pointer">
    <input type="hidden" name="{{ $name }}" value="0">
    <input
        type="checkbox"
        name="{{ $name }}"
        id="{{ $name }}"
        value="1"
        class="sr-only peer"
        @if($checked) checked @endif
    >
    <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-indigo-300 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
    <span class="ms-3 text-sm font-medium text-gray-700">{{ $label }}</span>
</label>
