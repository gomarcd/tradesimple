<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use PPFinances\Wealthsimple\WealthsimpleAPI;

?>

<!-- ✅ Ensure a root <div> wraps the content -->
<div>
    <?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
            
            <!--[if BLOCK]><![endif]--><?php if(!$this->connected || empty($this->accountData)): ?>
                <!-- Show placeholder if no data is available -->
                <div class="flex flex-col items-center justify-center h-full p-6 border border-neutral-200 dark:border-neutral-700 rounded-xl text-center">
                    <p class="text-lg font-semibold text-gray-600 dark:text-gray-300">
                        <?php echo e(__('No data available.')); ?>

                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        <?php echo e(__('Connect your Wealthsimple account to get started.')); ?>

                    </p>
                    <a href="<?php echo e(route('settings.ws-api')); ?>" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        <?php echo e(__('Connect Wealthsimple')); ?>

                    </a>
                </div>
            <?php else: ?>
                <!-- Show actual Wealthsimple data -->
                <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->accountData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
                            <h3 class="text-lg font-semibold"><?php echo e($account->description); ?></h3>
                            <p class="text-sm text-gray-500">Balance: $<?php echo e(number_format($account->balance, 2)); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
</div><?php /**PATH /Users/md/dev/tradesimple/resources/views/livewire/dashboard/dashboard.blade.php ENDPATH**/ ?>