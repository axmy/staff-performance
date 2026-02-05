<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'label', 'checked' => false]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['name', 'label', 'checked' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<label for="<?php echo e($name); ?>" class="inline-flex items-center cursor-pointer">
    <input type="hidden" name="<?php echo e($name); ?>" value="0">
    <input
        type="checkbox"
        name="<?php echo e($name); ?>"
        id="<?php echo e($name); ?>"
        value="1"
        class="sr-only peer"
        <?php if($checked): ?> checked <?php endif; ?>
    >
    <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-indigo-300 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
    <span class="ms-3 text-sm font-medium text-gray-700"><?php echo e($label); ?></span>
</label>
<?php /**PATH /Users/axmeen/Projects/staff-performance/resources/views/components/form/toggle.blade.php ENDPATH**/ ?>