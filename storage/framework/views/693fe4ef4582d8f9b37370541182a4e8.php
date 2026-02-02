

<?php
    $showSocialFooter = !empty($socialLinks['show_social_footer']) && $socialLinks['show_social_footer'] == '1';
    $logoWhitePath = !empty($companyInfo['logo_white']) ? Storage::url($companyInfo['logo_white']) : asset('images/logo-white.png');
?>
<footer class="bg-gray-900 text-gray-300">
    
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            
            <div class="lg:col-span-1">
                <div class="flex items-center gap-3 mb-4">
                    <img src="<?php echo e($logoWhitePath); ?>" alt="<?php echo e($companyInfo['company_name'] ?? config('app.name')); ?>" class="h-10" width="40" height="40" loading="lazy">
                    <div>
                        <h3 class="text-white font-bold text-lg leading-tight"><?php echo e($companyInfo['company_name'] ?? config('app.name')); ?></h3>
                    </div>
                </div>
                <p class="text-sm text-gray-400 mb-4">
                    <?php echo e($companyInfo['company_tagline'] ?? 'Distributor dan supplier besi berkualitas untuk industri, manufaktur, konstruksi bangunan dan pekerjaan sipil.'); ?>

                </p>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showSocialFooter): ?>
                <div class="flex space-x-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['facebook', 'instagram', 'youtube', 'tiktok', 'twitter', 'linkedin', 'whatsapp']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($socialLinks[$platform]) && (!empty($socialLinks[$platform . '_active']) && $socialLinks[$platform . '_active'] == '1')): ?>
                        <a 
                            href="<?php echo e($platform === 'whatsapp' ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $socialLinks[$platform]) : $socialLinks[$platform]); ?>" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-primary-600 transition-colors"
                            aria-label="<?php echo e(ucfirst($platform)); ?>"
                        >
                            <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => 'icons.' . $platform] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-5 h-5']); ?>
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
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div>
                <h4 class="text-white font-semibold text-lg mb-4"><?php echo e(__('Quick Links')); ?></h4>
                <ul class="space-y-2">
                    <li><a href="<?php echo e(route('home')); ?>" class="hover:text-primary-400 transition-colors"><?php echo e(__('Home')); ?></a></li>
                    <li><a href="<?php echo e(route('about.company')); ?>" class="hover:text-primary-400 transition-colors"><?php echo e(__('About Us')); ?></a></li>
                    <li><a href="<?php echo e(route('products.index')); ?>" class="hover:text-primary-400 transition-colors"><?php echo e(__('Products')); ?></a></li>
                    <li><a href="<?php echo e(route('articles.index')); ?>" class="hover:text-primary-400 transition-colors"><?php echo e(__('Articles')); ?></a></li>
                    <li><a href="<?php echo e(route('contact')); ?>" class="hover:text-primary-400 transition-colors"><?php echo e(__('Contact')); ?></a></li>
                    <li><a href="<?php echo e(route('quote')); ?>" class="hover:text-primary-400 transition-colors"><?php echo e(__('Request Quote')); ?></a></li>
                </ul>
            </div>

            
            <div>
                <h4 class="text-white font-semibold text-lg mb-4"><?php echo e(__('Product Categories')); ?></h4>
                <ul class="space-y-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = \App\Models\Category::active()->roots()->ordered()->take(6)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <li>
                        <a href="<?php echo e(route('products.category', $category->slug)); ?>" class="hover:text-primary-400 transition-colors">
                            <?php echo e($category->getTranslation('name', app()->getLocale())); ?>

                        </a>
                    </li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </ul>
            </div>

            
            <div>
                <h4 class="text-white font-semibold text-lg mb-4"><?php echo e(__('Contact Us')); ?></h4>
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-3 mt-0.5 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-sm"><?php echo e($companyInfo['address'] ?? 'Jl. Industri Raya No. 123, Jakarta 12345'); ?></span>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <a href="tel:<?php echo e($companyInfo['phone'] ?? '+62-21-12345678'); ?>" class="text-sm hover:text-primary-400 transition-colors">
                            <?php echo e($companyInfo['phone'] ?? '+62-21-12345678'); ?>

                        </a>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <?php if (isset($component)) { $__componentOriginal9a04fb077030929f0e22a70ea6a78c92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9a04fb077030929f0e22a70ea6a78c92 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.protected-email','data' => ['email' => $companyInfo['email'] ?? 'info@berkahmandiriglobalindo.com','class' => 'text-sm hover:text-primary-400 transition-colors']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('protected-email'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['email' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($companyInfo['email'] ?? 'info@berkahmandiriglobalindo.com'),'class' => 'text-sm hover:text-primary-400 transition-colors']); ?>
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
                    </li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($socialLinks['whatsapp'])): ?>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-primary-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                        <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/', '', $socialLinks['whatsapp'])); ?>" target="_blank" rel="noopener noreferrer" class="text-sm hover:text-primary-400 transition-colors">
                            WA: <?php echo e($socialLinks['whatsapp']); ?>

                        </a>
                    </li>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-3 text-primary-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($companyInfo['business_hours_weekday']) || !empty($companyInfo['business_hours_weekend']) || !empty($companyInfo['business_hours_sunday'])): ?>
                                <?php if(!empty($companyInfo['business_hours_weekday'])): ?>
                                    <?php echo e($companyInfo['business_hours_weekday']); ?>

                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($companyInfo['business_hours_weekend'])): ?>
                                    <br><?php echo e($companyInfo['business_hours_weekend']); ?>

                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($companyInfo['business_hours_sunday'])): ?>
                                    <br><?php echo e($companyInfo['business_hours_sunday']); ?>

                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php else: ?>
                                <?php echo e(app()->getLocale() === 'id' ? 'Sen - Jum: 08:00 - 17:00' : 'Mon - Fri: 08:00 - 17:00'); ?>

                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
        
        
        <div class="border-t border-gray-800 mt-8 pt-8">
            <div class="text-center">
                <h4 class="text-white font-semibold text-sm mb-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(app()->getLocale() === 'en'): ?> 
                        Steel Supplier Serving All of Indonesia
                    <?php else: ?> 
                        Supplier Besi Baja Melayani Seluruh Indonesia
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </h4>
                <p class="text-xs text-gray-500 max-w-4xl mx-auto leading-relaxed mb-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(app()->getLocale() === 'en'): ?> 
                        <span class="text-primary-400">Wholesale & Retail</span> • 
                        <span class="text-gray-400">Large Projects</span> • 
                        <span class="text-gray-400">Individual Orders</span> • 
                        <span class="text-gray-400">Industrial</span> • 
                        <span class="text-gray-400">Construction</span>
                    <?php else: ?> 
                        <span class="text-primary-400">Partai Besar & Eceran</span> • 
                        <span class="text-gray-400">Proyek Besar</span> • 
                        <span class="text-gray-400">Pembelian Satuan</span> • 
                        <span class="text-gray-400">Industri</span> • 
                        <span class="text-gray-400">Konstruksi</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </p>
                <p class="text-xs text-gray-500 max-w-4xl mx-auto leading-relaxed">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(app()->getLocale() === 'en'): ?> 
                        <span class="text-gray-400 font-medium">Primary:</span> Surabaya • Sidoarjo • Gresik • Mojokerto • Jombang • East Java |
                        <span class="text-gray-400 font-medium">National:</span> Java • Sumatra • Kalimantan • Sulawesi • Bali • Papua • All Indonesia
                    <?php else: ?> 
                        <span class="text-gray-400 font-medium">Utama:</span> Surabaya • Sidoarjo • Gresik • Mojokerto • Jombang • Jawa Timur |
                        <span class="text-gray-400 font-medium">Nasional:</span> Jawa • Sumatera • Kalimantan • Sulawesi • Bali • Papua • Seluruh Indonesia
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </p>
            </div>
        </div>
    </div>

    
    <div class="border-t border-gray-800">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <p class="text-sm text-gray-400">
                    © <?php echo e(date('Y')); ?> <?php echo e($companyInfo['company_name'] ?? config('app.name')); ?>. <?php echo e(__('All rights reserved.')); ?>

                </p>
                <div class="flex space-x-6 text-sm">
                    <a href="<?php echo e(route('privacy')); ?>" class="hover:text-primary-400 transition-colors"><?php echo e(__('Privacy Policy')); ?></a>
                    <a href="<?php echo e(route('terms')); ?>" class="hover:text-primary-400 transition-colors"><?php echo e(__('Terms of Service')); ?></a>
                    <a href="<?php echo e(route('sitemap')); ?>" class="hover:text-primary-400 transition-colors"><?php echo e(__('Sitemap')); ?></a>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php /**PATH C:\xampp2\htdocs\berkahmandiri.co.id\resources\views/layouts/partials/footer.blade.php ENDPATH**/ ?>