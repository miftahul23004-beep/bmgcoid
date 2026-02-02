<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['product', 'dark' => false, 'listView' => false]));

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

foreach (array_filter((['product', 'dark' => false, 'listView' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $productName = e($product->getTranslation('name', app()->getLocale()));
    $productSlug = e($product->slug);
    $categoryName = $product->category ? e($product->category->getTranslation('name', app()->getLocale())) : null;
    $shortDesc = $product->short_description ? e($product->getTranslation('short_description', app()->getLocale())) : null;
?>

<article class="product-card group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden <?php echo e($dark ? 'bg-gray-800 border-gray-700' : ''); ?> <?php echo e($listView ? 'flex flex-row' : ''); ?>" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')" itemscope itemtype="https://schema.org/Product">
    <a href="<?php echo e(route('products.show', $productSlug)); ?>" class="block <?php echo e($listView ? 'flex flex-row w-full' : ''); ?>" title="<?php echo e($productName); ?>">
        
        <div class="product-image bg-gray-100 overflow-hidden relative <?php echo e($listView ? 'w-48 md:w-56 flex-shrink-0' : 'aspect-[4/3]'); ?>">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->featured_image): ?>
                <img src="<?php echo e(asset('storage/' . $product->featured_image)); ?>" 
                     alt="<?php echo e($productName); ?>" 
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 <?php echo e($listView ? 'aspect-square md:aspect-[4/3]' : ''); ?>" 
                     width="<?php echo e($listView ? '224' : '400'); ?>"
                     height="<?php echo e($listView ? '224' : '300'); ?>"
                     loading="lazy"
                     decoding="async"
                     itemprop="image">
            <?php else: ?>
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 <?php echo e($listView ? 'aspect-square md:aspect-[4/3]' : ''); ?>">
                    <svg class="w-16 h-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            
            
            <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->is_featured): ?>
                    <span class="bg-primary-600 text-white text-xs font-semibold px-2.5 py-1 rounded-full shadow-sm"><?php echo e(__('Featured')); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->is_new): ?>
                    <span class="bg-green-500 text-white text-xs font-semibold px-2.5 py-1 rounded-full shadow-sm"><?php echo e(__('New')); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->is_bestseller): ?>
                    <span class="bg-amber-500 text-white text-xs font-semibold px-2.5 py-1 rounded-full shadow-sm"><?php echo e(__('Best Seller')); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($listView)): ?>
            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                <span class="bg-white text-gray-900 px-4 py-2 rounded-lg font-medium text-sm transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                    <?php echo e(__('View Details')); ?>

                </span>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        
        
        <div class="product-content p-4 <?php echo e($dark ? 'text-white' : ''); ?> <?php echo e($listView ? 'flex-1 flex flex-col justify-center' : ''); ?>">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($categoryName): ?>
                <p class="text-xs <?php echo e($dark ? 'text-primary-300' : 'text-primary-600'); ?> font-semibold mb-1.5 uppercase tracking-wide" itemprop="category"><?php echo e($categoryName); ?></p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <h3 class="font-bold <?php echo e($listView ? 'text-lg' : 'text-base'); ?> <?php echo e($dark ? 'text-white group-hover:text-primary-300' : 'text-gray-900 group-hover:text-primary-600'); ?> transition-colors line-clamp-2 mb-2 leading-snug" itemprop="name">
                <?php echo e($productName); ?>

            </h3>
            
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($listView && $shortDesc): ?>
                <p class="text-sm text-gray-600 line-clamp-2 mb-3" itemprop="description"><?php echo e($shortDesc); ?></p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->sku): ?>
                <p class="text-xs <?php echo e($dark ? 'text-gray-400' : 'text-gray-500'); ?>"><span itemprop="sku"><?php echo e($product->sku); ?></span></p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            
            
            <meta itemprop="brand" content="<?php echo e(config('app.name')); ?>">
            
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($listView && $product->marketplaceLinks && $product->marketplaceLinks->count() > 0): ?>
                <div class="mt-3 pt-3 border-t <?php echo e($dark ? 'border-gray-700' : 'border-gray-100'); ?>">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs <?php echo e($dark ? 'text-gray-400' : 'text-gray-500'); ?>"><?php echo e(__('Buy on')); ?>:</span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $product->marketplaceLinks->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <span class="text-xs px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 font-medium">
                                <?php echo e(ucfirst($link->platform)); ?>

                            </span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </a>
    
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$listView && $product->marketplaceLinks && $product->marketplaceLinks->count() > 0): ?>
        <div class="px-4 pb-4 pt-0">
            <div class="pt-3 border-t <?php echo e($dark ? 'border-gray-700' : 'border-gray-100'); ?>">
                <p class="text-xs <?php echo e($dark ? 'text-gray-400' : 'text-gray-500'); ?> mb-2"><?php echo e(__('Buy on')); ?>:</p>
                <div class="flex flex-wrap gap-2" itemprop="offers" itemscope itemtype="https://schema.org/AggregateOffer">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $product->marketplaceLinks->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <a href="<?php echo e(route('marketplace.redirect', ['platform' => e($link->platform), 'productId' => $product->id])); ?>" 
                           target="_blank" 
                           rel="noopener nofollow" 
                           class="text-xs px-2.5 py-1 rounded-full bg-gray-100 hover:bg-primary-100 hover:text-primary-700 text-gray-600 transition-colors font-medium" 
                           title="<?php echo e(__('Buy on')); ?> <?php echo e(ucfirst($link->platform)); ?>"
                           itemprop="url">
                            <?php echo e(ucfirst($link->platform)); ?>

                        </a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</article>

<?php /**PATH C:\xampp2\htdocs\berkahmandiri.co.id\resources\views/components/product-card.blade.php ENDPATH**/ ?>