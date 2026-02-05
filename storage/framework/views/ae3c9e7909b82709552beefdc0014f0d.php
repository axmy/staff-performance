<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'label', 'value' => '', 'required' => false, 'rows' => 3]));

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

foreach (array_filter((['name', 'label', 'value' => '', 'required' => false, 'rows' => 3]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div x-data="{ focused: false, filled: false }" x-init="setTimeout(() => { filled = $refs.textarea.value !== '' }, 100)" class="relative">
    <textarea
        x-ref="textarea"
        name="<?php echo e($name); ?>"
        id="<?php echo e($name); ?>"
        rows="<?php echo e($rows); ?>"
        <?php if($required): ?> required <?php endif; ?>
        @focus="focused = true"
        @blur="focused = false; filled = $refs.textarea.value !== ''"
        @input="filled = $refs.textarea.value !== ''"
        placeholder=" "
        <?php echo e($attributes->merge(['class' => 'peer block w-full px-4 pt-6 pb-2 text-base text-gray-900 bg-white border rounded-lg focus:outline-none focus:ring-2 placeholder-transparent transition-colors duration-200 ' . ($errors->has($name) ? 'border-red-400 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-indigo-500 focus:border-indigo-500')])); ?>

    ><?php echo e(old($name, $value)); ?></textarea>
    <label
        for="<?php echo e($name); ?>"
        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
            'absolute left-4 top-4 origin-[0] transform transition-all duration-200 pointer-events-none',
            'text-red-600' => $errors->has($name),
        ]); ?>"
        :class="focused || filled ? '-translate-y-3 scale-75 text-<?php echo e($errors->has($name) ? 'red' : 'indigo'); ?>-600' : 'translate-y-0 scale-100 text-gray-500'"
    >
        <?php echo e($label); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($required): ?> *<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </label>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH /Users/axmeen/Projects/staff-performance/resources/views/components/form/textarea.blade.php ENDPATH**/ ?>