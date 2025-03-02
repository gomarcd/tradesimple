<?php
$classes = Flux::classes()
    ->add('p-6 rounded-xl')
    ->add('bg-white dark:bg-white/10')
    ->add('border border-zinc-200 dark:border-white/10')
?>

<div <?php echo e($attributes->class($classes)); ?> data-flux-card>
    <?php echo e($slot); ?>

</div>
<?php /**PATH /Users/md/dev/tradesimple/vendor/livewire/flux-pro/src/../stubs/resources/views/flux/card/index.blade.php ENDPATH**/ ?>