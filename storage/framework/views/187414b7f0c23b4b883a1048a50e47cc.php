<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'stashable' => null,
    'sticky' => null,
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
    'stashable' => null,
    'sticky' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
$classes = Flux::classes('[grid-area:sidebar]')
    ->add('z-1 flex flex-col gap-4 [:where(&)]:w-64 p-4')
    ;

if ($sticky) {
    $attributes = $attributes->merge([
        'x-bind:style' => '{ position: \'sticky\', top: $el.offsetTop + \'px\', \'max-height\': \'calc(100dvh - \' + $el.offsetTop + \'px)\' }',
        'class' => 'max-h-dvh overflow-y-auto overscroll-contain',
    ]);
}

if ($stashable) {
    $attributes = $attributes->merge([
        'x-bind:data-stashed' => '! screenLg',
        'x-resize.document' => 'screenLg = window.innerWidth >= 1024',
        'x-init' => '$el.classList.add(\'-translate-x-full\'); $el.removeAttribute(\'data-mobile-cloak\'); $el.classList.add(\'transition-transform\')',
    ])->class([
        'max-lg:data-mobile-cloak:hidden',
        '[[data-show-stashed-sidebar]_&]:translate-x-0! lg:translate-x-0!',
        'z-20! data-stashed:left-0! data-stashed:fixed! data-stashed:top-0! data-stashed:min-h-dvh! data-stashed:max-h-dvh!'
    ]);
}
?>

<?php if($stashable): ?>
    <?php if (isset($component)) { $__componentOriginalb789a575a15f5f184ee0fb1f2cfab391 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb789a575a15f5f184ee0fb1f2cfab391 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::sidebar.backdrop','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::sidebar.backdrop'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb789a575a15f5f184ee0fb1f2cfab391)): ?>
<?php $attributes = $__attributesOriginalb789a575a15f5f184ee0fb1f2cfab391; ?>
<?php unset($__attributesOriginalb789a575a15f5f184ee0fb1f2cfab391); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb789a575a15f5f184ee0fb1f2cfab391)): ?>
<?php $component = $__componentOriginalb789a575a15f5f184ee0fb1f2cfab391; ?>
<?php unset($__componentOriginalb789a575a15f5f184ee0fb1f2cfab391); ?>
<?php endif; ?>
<?php endif; ?>

<div <?php echo e($attributes->class($classes)); ?> x-data="{ screenLg: window.innerWidth >= 1024 }" data-mobile-cloak data-flux-sidebar>
    <?php echo e($slot); ?>

</div>

<?php /**PATH /Users/md/dev/tradesimple/vendor/livewire/flux/src/../stubs/resources/views/flux/sidebar/index.blade.php ENDPATH**/ ?>