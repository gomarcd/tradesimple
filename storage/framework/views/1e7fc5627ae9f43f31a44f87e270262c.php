<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <div class="grid auto-rows-min gap-4 md:grid-cols-3">
        <!--[if BLOCK]><![endif]--><?php if(!$connected || empty($accountData)): ?>
            
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 flex flex-col items-center justify-center p-4">
                <p class="text-gray-500 dark:text-gray-400 text-center">No data available</p>
                <a href="<?php echo e(route('settings.ws-api')); ?>" class="text-blue-500 hover:underline mt-2">Connect Wealthsimple API</a>
            </div>
        <?php else: ?>
            
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $accountData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if (isset($component)) { $__componentOriginalc4bce27d2c09d2f98a63d67977c1c3ec = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc4bce27d2c09d2f98a63d67977c1c3ec = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::card.index','data' => ['class' => 'overflow-hidden min-w-[12rem] p-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'overflow-hidden min-w-[12rem] p-4']); ?>
                    <?php if (isset($component)) { $__componentOriginal43e8c568bbb8b06b9124aad3ccf4ec97 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal43e8c568bbb8b06b9124aad3ccf4ec97 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::subheading','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::subheading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e($account->description); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal43e8c568bbb8b06b9124aad3ccf4ec97)): ?>
<?php $attributes = $__attributesOriginal43e8c568bbb8b06b9124aad3ccf4ec97; ?>
<?php unset($__attributesOriginal43e8c568bbb8b06b9124aad3ccf4ec97); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal43e8c568bbb8b06b9124aad3ccf4ec97)): ?>
<?php $component = $__componentOriginal43e8c568bbb8b06b9124aad3ccf4ec97; ?>
<?php unset($__componentOriginal43e8c568bbb8b06b9124aad3ccf4ec97); ?>
<?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginale0fd5b6a0986beffac17a0a103dfd7b9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale0fd5b6a0986beffac17a0a103dfd7b9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::heading','data' => ['size' => 'xl']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'xl']); ?>
                        <!--[if BLOCK]><![endif]--><?php if(isset($account->financials->currentCombined->netLiquidationValue->amount)): ?>
                            $<?php echo e(number_format($account->financials->currentCombined->netLiquidationValue->amount, 2)); ?>

                        <?php else: ?>
                            N/A
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale0fd5b6a0986beffac17a0a103dfd7b9)): ?>
<?php $attributes = $__attributesOriginale0fd5b6a0986beffac17a0a103dfd7b9; ?>
<?php unset($__attributesOriginale0fd5b6a0986beffac17a0a103dfd7b9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale0fd5b6a0986beffac17a0a103dfd7b9)): ?>
<?php $component = $__componentOriginale0fd5b6a0986beffac17a0a103dfd7b9; ?>
<?php unset($__componentOriginale0fd5b6a0986beffac17a0a103dfd7b9); ?>
<?php endif; ?>

                    <?php if (isset($component)) { $__componentOriginal0bb95b4f002874583427509cf65df860 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0bb95b4f002874583427509cf65df860 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::chart.index','data' => ['class' => '-mx-8 -mb-8 h-[3rem]','value' => [10, 12, 11, 13, 15, 14, 16, 18, 17, 19, 21, 20]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::chart'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => '-mx-8 -mb-8 h-[3rem]','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([10, 12, 11, 13, 15, 14, 16, 18, 17, 19, 21, 20])]); ?>
                        <?php if (isset($component)) { $__componentOriginale1f8a65b1528184d3dfece96854be4d1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale1f8a65b1528184d3dfece96854be4d1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::chart.svg','data' => ['gutter' => '0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::chart.svg'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['gutter' => '0']); ?>
                            <?php if (isset($component)) { $__componentOriginalcc3377740c4c1d3f549529e5d7b84155 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcc3377740c4c1d3f549529e5d7b84155 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::chart.line','data' => ['class' => 'text-sky-200 dark:text-sky-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::chart.line'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'text-sky-200 dark:text-sky-400']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcc3377740c4c1d3f549529e5d7b84155)): ?>
<?php $attributes = $__attributesOriginalcc3377740c4c1d3f549529e5d7b84155; ?>
<?php unset($__attributesOriginalcc3377740c4c1d3f549529e5d7b84155); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcc3377740c4c1d3f549529e5d7b84155)): ?>
<?php $component = $__componentOriginalcc3377740c4c1d3f549529e5d7b84155; ?>
<?php unset($__componentOriginalcc3377740c4c1d3f549529e5d7b84155); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginalcfea84d7bc481d110b444613a2285408 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcfea84d7bc481d110b444613a2285408 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::chart.area','data' => ['class' => 'text-sky-100 dark:text-sky-400/30']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::chart.area'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'text-sky-100 dark:text-sky-400/30']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcfea84d7bc481d110b444613a2285408)): ?>
<?php $attributes = $__attributesOriginalcfea84d7bc481d110b444613a2285408; ?>
<?php unset($__attributesOriginalcfea84d7bc481d110b444613a2285408); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcfea84d7bc481d110b444613a2285408)): ?>
<?php $component = $__componentOriginalcfea84d7bc481d110b444613a2285408; ?>
<?php unset($__componentOriginalcfea84d7bc481d110b444613a2285408); ?>
<?php endif; ?>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale1f8a65b1528184d3dfece96854be4d1)): ?>
<?php $attributes = $__attributesOriginale1f8a65b1528184d3dfece96854be4d1; ?>
<?php unset($__attributesOriginale1f8a65b1528184d3dfece96854be4d1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale1f8a65b1528184d3dfece96854be4d1)): ?>
<?php $component = $__componentOriginale1f8a65b1528184d3dfece96854be4d1; ?>
<?php unset($__componentOriginale1f8a65b1528184d3dfece96854be4d1); ?>
<?php endif; ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0bb95b4f002874583427509cf65df860)): ?>
<?php $attributes = $__attributesOriginal0bb95b4f002874583427509cf65df860; ?>
<?php unset($__attributesOriginal0bb95b4f002874583427509cf65df860); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0bb95b4f002874583427509cf65df860)): ?>
<?php $component = $__componentOriginal0bb95b4f002874583427509cf65df860; ?>
<?php unset($__componentOriginal0bb95b4f002874583427509cf65df860); ?>
<?php endif; ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc4bce27d2c09d2f98a63d67977c1c3ec)): ?>
<?php $attributes = $__attributesOriginalc4bce27d2c09d2f98a63d67977c1c3ec; ?>
<?php unset($__attributesOriginalc4bce27d2c09d2f98a63d67977c1c3ec); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc4bce27d2c09d2f98a63d67977c1c3ec)): ?>
<?php $component = $__componentOriginalc4bce27d2c09d2f98a63d67977c1c3ec; ?>
<?php unset($__componentOriginalc4bce27d2c09d2f98a63d67977c1c3ec); ?>
<?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div><?php /**PATH /Users/md/dev/tradesimple/resources/views/livewire/dashboard.blade.php ENDPATH**/ ?>