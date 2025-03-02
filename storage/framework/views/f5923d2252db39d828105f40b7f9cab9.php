<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'gutter' => null,
]));

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

foreach (array_filter(([
    'gutter' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<template name="svg" <?php if($gutter): ?> gutter="<?php echo e($gutter); ?>" <?php endif; ?>>
    <svg <?php echo e($attributes->class('absolute inset-0')); ?> xmlns="http://www.w3.org/2000/svg" version="1.1">
        <?php echo e($slot); ?>

    </svg>
</template><?php /**PATH /Users/md/dev/tradesimple/vendor/livewire/flux-pro/src/../stubs/resources/views/flux/chart/svg.blade.php ENDPATH**/ ?>