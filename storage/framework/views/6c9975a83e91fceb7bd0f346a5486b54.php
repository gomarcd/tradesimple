<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'badge' => null,
    'aside' => null,
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
    'badge' => null,
    'aside' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $classes = Flux::classes()
        ->add('text-sm font-medium select-none')
        ->add('text-zinc-800 dark:text-white')
        ;
?>

<ui-label <?php echo e($attributes->class($classes)); ?> data-flux-label>
    <?php echo e($slot); ?>


    <?php if ($badge): ?>
        <span class="ml-1.5 text-zinc-800/70 text-xs bg-zinc-800/5 px-1.5 py-1 rounded-[4px] dark:bg-white/10 dark:text-zinc-300" aria-hidden="true">
            <?php echo e($badge); ?>

        </span>
    <?php endif; ?>

    <?php if ($aside): ?>
        <span class="ml-1.5 text-zinc-800/70 text-xs bg-zinc-800/5 px-1.5 py-1 rounded-[4px] dark:bg-white/10 dark:text-zinc-300" aria-hidden="true">
            <?php echo e($aside); ?>

        </span>
    <?php endif; ?>
</ui-label>
<?php /**PATH /Users/md/dev/tradesimple/vendor/livewire/flux/src/../stubs/resources/views/flux/label.blade.php ENDPATH**/ ?>