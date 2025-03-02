<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'tooltip' => null,
    'summary' => null,
    'value' => null,
    'svg' => null,
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
    'tooltip' => null,
    'summary' => null,
    'value' => null,
    'svg' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
$classes = Flux::classes('block [:where(&)]:relative');

$value = is_array($value) ? \Illuminate\Support\Js::encode($value) : $value;
?>

<ui-chart <?php echo e($attributes->class($classes)); ?> wire:ignore <?php if($value): ?> value="<?php echo e($value); ?>" <?php endif; ?>>
    <?php echo e($slot); ?>

</ui-chart>
<?php /**PATH /Users/md/dev/tradesimple/vendor/livewire/flux-pro/src/../stubs/resources/views/flux/chart/index.blade.php ENDPATH**/ ?>