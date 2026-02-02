

<?php
    $companyName = $companyInfo['company_name'] ?? config('app.name');
    $companyTagline = $companyInfo['company_tagline'] ?? 'Distributor Besi Baja Terpercaya';
    $logoPath = !empty($companyInfo['logo']) ? Storage::url($companyInfo['logo']) : asset('images/logo.png');
    $navCategories = $navbarCategories ?? collect();
?>
<header 
    x-data="{ 
        mobileMenuOpen: false, 
        scrolled: false,
        activeDropdown: null,
        mobileExpanded: null
    }"
    x-on:scroll.window="scrolled = window.scrollY > 50"
    :class="{ 'shadow-md bg-white': scrolled, 'bg-white md:bg-transparent': !scrolled }"
    class="sticky top-0 z-50 transition-all duration-300 min-h-[72px] md:min-h-[80px]"
>
    <nav class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            
            <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-3">
                <img 
                    src="<?php echo e($logoPath); ?>" 
                    alt="<?php echo e($companyName); ?>" 
                    class="h-10 md:h-12 w-auto"
                    width="48"
                    height="48"
                    fetchpriority="high"
                >
                <div class="hidden sm:block">
                    <span class="block font-bold text-gray-900 text-lg leading-tight"><?php echo e($companyName); ?></span>
                    <span class="block text-xs text-gray-500 leading-tight"><?php echo e($companyTagline); ?></span>
                </div>
            </a>

            
            <div class="hidden lg:flex items-center space-x-8">
                
                <a href="<?php echo e(route('home')); ?>" class="font-medium text-gray-700 hover:text-primary-600 transition-colors <?php echo e(request()->routeIs('home') ? 'text-primary-600' : ''); ?>">
                    <?php echo e(__('Home')); ?>

                </a>

                
                <div 
                    class="relative"
                    @mouseenter="activeDropdown = 'about'"
                    @mouseleave="activeDropdown = null"
                >
                    <button class="flex items-center font-medium text-gray-700 hover:text-primary-600 transition-colors <?php echo e(request()->routeIs('about.*') ? 'text-primary-600' : ''); ?>">
                        <?php echo e(__('About')); ?>

                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div 
                        x-show="activeDropdown === 'about'"
                        x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1"
                        class="absolute top-full left-0 w-48 bg-white rounded-lg shadow-lg py-2 mt-2 z-50"
                    >
                        <a href="<?php echo e(route('about.company')); ?>" class="block px-4 py-2 text-gray-700 hover:bg-primary-50 hover:text-primary-600"><?php echo e(__('Company Profile')); ?></a>
                        <a href="<?php echo e(route('about.vision-mission')); ?>" class="block px-4 py-2 text-gray-700 hover:bg-primary-50 hover:text-primary-600"><?php echo e(__('Vision & Mission')); ?></a>
                        <a href="<?php echo e(route('about.team')); ?>" class="block px-4 py-2 text-gray-700 hover:bg-primary-50 hover:text-primary-600"><?php echo e(__('Our Team')); ?></a>
                        <a href="<?php echo e(route('about.certificates')); ?>" class="block px-4 py-2 text-gray-700 hover:bg-primary-50 hover:text-primary-600"><?php echo e(__('Certificates')); ?></a>
                    </div>
                </div>

                
                <div 
                    class="relative"
                    @mouseenter="activeDropdown = 'products'"
                    @mouseleave="activeDropdown = null"
                >
                    <a href="<?php echo e(route('products.index')); ?>" class="flex items-center font-medium text-gray-700 hover:text-primary-600 transition-colors <?php echo e(request()->routeIs('products.*') ? 'text-primary-600' : ''); ?>">
                        <?php echo e(__('Products')); ?>

                        <svg class="w-4 h-4 ml-1 transition-transform duration-200" :class="activeDropdown === 'products' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </a>
                    
                    
                    <div 
                        x-show="activeDropdown === 'products'"
                        x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-1"
                        class="absolute top-full left-1/2 -translate-x-1/2 w-[640px] bg-white rounded-xl shadow-xl ring-1 ring-gray-900/5 mt-3 z-50 overflow-hidden"
                    >
                        
                        <div class="p-6">
                            <div class="grid grid-cols-2 gap-4">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $navCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <a href="<?php echo e(route('products.index', ['category' => $category->slug])); ?>" 
                                       class="group flex items-center gap-4 p-4 rounded-xl hover:bg-gray-50 transition-all duration-200">
                                        
                                        <span class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center shadow-sm group-hover:shadow-md group-hover:scale-105 transition-all duration-200">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->icon): ?>
                                                <img src="<?php echo e(asset('storage/' . $category->icon)); ?>" alt="" class="w-6 h-6 object-contain filter brightness-0 invert">
                                            <?php else: ?>
                                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                </svg>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </span>
                                        
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">
                                                <?php echo e($category->getTranslation('name', app()->getLocale())); ?>

                                            </p>
                                            <p class="text-xs text-gray-500 mt-0.5">
                                                <?php echo e($category->products_count); ?> <?php echo e(__('products')); ?>

                                            </p>
                                        </div>
                                        
                                        <svg class="w-4 h-4 text-gray-300 group-hover:text-primary-500 group-hover:translate-x-0.5 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        </div>

                        
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <a href="<?php echo e(route('products.index')); ?>" class="inline-flex items-center gap-2 text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                    <?php echo e(__('Browse All Products')); ?>

                                </a>
                                <a href="<?php echo e(route('quote')); ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                    <?php echo e(__('Request Quote')); ?>

                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="<?php echo e(route('articles.index')); ?>" class="font-medium text-gray-700 hover:text-primary-600 transition-colors <?php echo e(request()->routeIs('articles.*') ? 'text-primary-600' : ''); ?>">
                    <?php echo e(__('Articles')); ?>

                </a>

                <a href="<?php echo e(route('contact')); ?>" class="font-medium text-gray-700 hover:text-primary-600 transition-colors <?php echo e(request()->routeIs('contact') ? 'text-primary-600' : ''); ?>">
                    <?php echo e(__('Contact')); ?>

                </a>
            </div>

            
            <div class="hidden lg:flex items-center space-x-3">
                
                <a 
                    href="<?php echo e(route('search')); ?>"
                    class="p-2 text-gray-600 hover:text-primary-600 transition-colors"
                    aria-label="<?php echo e(__('Search')); ?>"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </a>

                
                <a href="<?php echo e(route('quote')); ?>" class="bg-secondary-600 hover:bg-secondary-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors">
                    <?php echo e(__('Request Quote')); ?>

                </a>
            </div>

            
            <button 
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="lg:hidden p-2 text-gray-600"
                aria-label="<?php echo e(__('Toggle menu')); ?>"
            >
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        
        <div 
            x-show="mobileMenuOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="lg:hidden bg-white border-t"
        >
            <div class="py-4 space-y-1">
                <a href="<?php echo e(route('home')); ?>" class="block px-4 py-3 text-gray-700 hover:bg-primary-50 hover:text-primary-600 <?php echo e(request()->routeIs('home') ? 'text-primary-600 bg-primary-50' : ''); ?>"><?php echo e(__('Home')); ?></a>
                
                
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-primary-50 hover:text-primary-600">
                        <span><?php echo e(__('About')); ?></span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="bg-gray-50">
                        <a href="<?php echo e(route('about.company')); ?>" class="block px-8 py-2 text-sm text-gray-600 hover:text-primary-600"><?php echo e(__('Company Profile')); ?></a>
                        <a href="<?php echo e(route('about.vision-mission')); ?>" class="block px-8 py-2 text-sm text-gray-600 hover:text-primary-600"><?php echo e(__('Vision & Mission')); ?></a>
                        <a href="<?php echo e(route('about.team')); ?>" class="block px-8 py-2 text-sm text-gray-600 hover:text-primary-600"><?php echo e(__('Our Team')); ?></a>
                        <a href="<?php echo e(route('about.certificates')); ?>" class="block px-8 py-2 text-sm text-gray-600 hover:text-primary-600"><?php echo e(__('Certificates')); ?></a>
                    </div>
                </div>
                
                
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-primary-50 hover:text-primary-600 <?php echo e(request()->routeIs('products.*') ? 'text-primary-600' : ''); ?>">
                        <span><?php echo e(__('Products')); ?></span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="bg-gray-50">
                        <a href="<?php echo e(route('products.index')); ?>" class="block px-8 py-2 text-sm text-primary-600 font-medium"><?php echo e(__('All Products')); ?></a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $navCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <div class="px-6 py-2">
                                
                                <a href="<?php echo e(route('products.index', ['category' => $category->slug])); ?>" 
                                   class="flex items-center justify-between text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1 hover:text-primary-600 transition-colors py-1">
                                    <span><?php echo e($category->getTranslation('name', app()->getLocale())); ?></span>
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                                
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->children->count() > 0): ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $category->children->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <a href="<?php echo e(route('products.index', ['category' => $child->slug])); ?>" class="block px-2 py-1.5 text-sm text-gray-600 hover:text-primary-600">
                                            <?php echo e($child->getTranslation('name', app()->getLocale())); ?>

                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($category->children->count() > 4): ?>
                                        <a href="<?php echo e(route('products.index', ['category' => $category->slug])); ?>" class="block px-2 py-1 text-xs text-primary-600 font-medium">
                                            +<?php echo e($category->children->count() - 4); ?> <?php echo e(__('more')); ?> →
                                        </a>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
                
                <a href="<?php echo e(route('articles.index')); ?>" class="block px-4 py-3 text-gray-700 hover:bg-primary-50 hover:text-primary-600 <?php echo e(request()->routeIs('articles.*') ? 'text-primary-600 bg-primary-50' : ''); ?>"><?php echo e(__('Articles')); ?></a>
                <a href="<?php echo e(route('contact')); ?>" class="block px-4 py-3 text-gray-700 hover:bg-primary-50 hover:text-primary-600 <?php echo e(request()->routeIs('contact') ? 'text-primary-600 bg-primary-50' : ''); ?>"><?php echo e(__('Contact')); ?></a>
                
                <div class="px-4 pt-4 space-y-2">
                    <a href="<?php echo e(route('search')); ?>" class="flex items-center justify-center gap-2 w-full border border-gray-200 text-gray-700 py-3 rounded-lg font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <?php echo e(__('Search')); ?>

                    </a>
                    <a href="<?php echo e(route('quote')); ?>" class="block w-full bg-secondary-600 text-white text-center py-3 rounded-lg font-medium"><?php echo e(__('Request Quote')); ?></a>
                </div>
            </div>
        </div>
    </nav>
</header>

<?php /**PATH C:\xampp2\htdocs\berkahmandiri.co.id\resources\views/layouts/partials/navbar.blade.php ENDPATH**/ ?>