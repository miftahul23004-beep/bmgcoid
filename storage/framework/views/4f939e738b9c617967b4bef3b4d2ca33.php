

<?php
    $showSocialTopbar = !empty($socialLinks['show_social_topbar']) && $socialLinks['show_social_topbar'] == '1';
?>
<div class="bg-primary-900 text-white text-sm hidden md:block">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-2">
            
            <div class="flex items-center space-x-6">
                <a href="tel:<?php echo e($companyInfo['phone'] ?? '+62-21-12345678'); ?>" class="flex items-center hover:text-primary-200 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <span><?php echo e($companyInfo['phone'] ?? '+62-21-12345678'); ?></span>
                </a>
                <?php if (isset($component)) { $__componentOriginal9a04fb077030929f0e22a70ea6a78c92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9a04fb077030929f0e22a70ea6a78c92 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.protected-email','data' => ['email' => $companyInfo['email'] ?? 'info@berkahmandiriglobalindo.com','class' => 'flex items-center hover:text-primary-200 transition-colors','showIcon' => true,'iconClass' => 'w-4 h-4 mr-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('protected-email'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['email' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($companyInfo['email'] ?? 'info@berkahmandiriglobalindo.com'),'class' => 'flex items-center hover:text-primary-200 transition-colors','showIcon' => true,'iconClass' => 'w-4 h-4 mr-2']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9a04fb077030929f0e22a70ea6a78c92)): ?>
<?php $attributes = $__attributesOriginal9a04fb077030929f0e22a70ea6a78c92; ?>
<?php unset($__attributesOriginal9a04fb077030929f0e22a70ea6a78c92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9a04fb077030929f0e22a70ea6a78c92)): ?>
<?php $component = $__componentOriginal9a04fb077030929f0e22a70ea6a78c92; ?>
<?php unset($__componentOriginal9a04fb077030929f0e22a70ea6a78c92); ?>
<?php endif; ?>
            </div>

            
            <div class="flex items-center space-x-4">
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showSocialTopbar): ?>
                <div class="flex items-center space-x-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['facebook', 'instagram', 'youtube', 'tiktok', 'twitter', 'linkedin', 'whatsapp']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($socialLinks[$platform]) && (!empty($socialLinks[$platform . '_active']) && $socialLinks[$platform . '_active'] == '1')): ?>
                        <a href="<?php echo e($platform === 'whatsapp' ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $socialLinks[$platform]) : $socialLinks[$platform]); ?>" target="_blank" rel="noopener noreferrer" class="hover:text-primary-200 transition-colors" aria-label="<?php echo e(ucfirst($platform)); ?>">
                            <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => 'icons.' . $platform] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-4 h-4']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $attributes = $__attributesOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $component = $__componentOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__componentOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
                        </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
                
                
                <div class="w-px h-4 bg-primary-700"></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                
                <?php echo $__env->make('layouts.partials.language-switcher-topbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
    </div>
</div>

<?php /**PATH C:\xampp2\htdocs\berkahmandiri.co.id\resources\views/layouts/partials/topbar.blade.php ENDPATH**/ ?>