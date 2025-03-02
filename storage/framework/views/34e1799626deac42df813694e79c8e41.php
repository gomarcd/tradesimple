
<?php if (isset($component)) { $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::button.index','data' => ['attributes' => $attributes->merge([
        'class' => 'shrink-0',
        'variant' => 'subtle',
    ]),'square' => true,'xData' => true,'xOn:click' => 'document.body.hasAttribute(\'data-show-stashed-sidebar\') ? document.body.removeAttribute(\'data-show-stashed-sidebar\') : document.body.setAttribute(\'data-show-stashed-sidebar\', \'\')','dataFluxSidebarToggle' => true,'ariaLabel' => ''.e(__('Toggle sidebar')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attributes->merge([
        'class' => 'shrink-0',
        'variant' => 'subtle',
    ])),'square' => true,'x-data' => true,'x-on:click' => 'document.body.hasAttribute(\'data-show-stashed-sidebar\') ? document.body.removeAttribute(\'data-show-stashed-sidebar\') : document.body.setAttribute(\'data-show-stashed-sidebar\', \'\')','data-flux-sidebar-toggle' => true,'aria-label' => ''.e(__('Toggle sidebar')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $attributes = $__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__attributesOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580)): ?>
<?php $component = $__componentOriginalc04b147acd0e65cc1a77f86fb0e81580; ?>
<?php unset($__componentOriginalc04b147acd0e65cc1a77f86fb0e81580); ?>
<?php endif; ?>
<?php /**PATH /Users/md/dev/tradesimple/vendor/livewire/flux/src/../stubs/resources/views/flux/sidebar/toggle.blade.php ENDPATH**/ ?>