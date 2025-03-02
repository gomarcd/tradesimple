<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'paginate' => null,
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
    'paginate' => null,
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
    ->add('[:where(&)]:min-w-full table-fixed')
    ->add('text-zinc-800')
    ->add('divide-y divide-zinc-800/10 dark:divide-white/20 text-zinc-800')
    // We want whitespace-nowrap for the table, but not for modals and dropdowns...
    ->add('whitespace-nowrap [&_dialog]:whitespace-normal [&_[popover]]:whitespace-normal')
    ;
?>

<div>
    <?php echo e($header ?? ''); ?>


    <div class="overflow-x-auto">
        <table <?php echo e($attributes->class($classes)); ?> data-flux-table>
            <?php echo e($slot); ?>

        </table>
    </div>

    <?php echo e($footer ?? ''); ?>


    <?php if ($paginate): ?>
        <?php if (isset($component)) { $__componentOriginal460af0af147e5c473bd44cf084c50f42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal460af0af147e5c473bd44cf084c50f42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::pagination','data' => ['paginator' => $paginate]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($paginate)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal460af0af147e5c473bd44cf084c50f42)): ?>
<?php $attributes = $__attributesOriginal460af0af147e5c473bd44cf084c50f42; ?>
<?php unset($__attributesOriginal460af0af147e5c473bd44cf084c50f42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal460af0af147e5c473bd44cf084c50f42)): ?>
<?php $component = $__componentOriginal460af0af147e5c473bd44cf084c50f42; ?>
<?php unset($__componentOriginal460af0af147e5c473bd44cf084c50f42); ?>
<?php endif; ?>
    <?php endif; ?>
</div>
<?php /**PATH /Users/md/dev/tradesimple/vendor/livewire/flux-pro/src/../stubs/resources/views/flux/table/index.blade.php ENDPATH**/ ?>