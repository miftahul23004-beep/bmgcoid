<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    
    <title><?php echo e($metaTitle ?? config('seo.defaults.title')); ?></title>
    <meta name="description" content="<?php echo e($metaDescription ?? config('seo.defaults.description')); ?>">
    <meta name="keywords" content="<?php echo e($metaKeywords ?? config('seo.defaults.keywords')); ?>">
    <meta name="author" content="<?php echo e(config('seo.defaults.author')); ?>">
    <meta name="robots" content="<?php echo e($metaRobots ?? config('seo.defaults.robots')); ?>">
    <link rel="canonical" href="<?php echo e($canonicalUrl ?? url()->current()); ?>">

    
    <meta property="og:title" content="<?php echo e($ogTitle ?? $metaTitle ?? config('seo.defaults.title')); ?>">
    <meta property="og:description" content="<?php echo e($ogDescription ?? $metaDescription ?? config('seo.defaults.description')); ?>">
    <meta property="og:image" content="<?php echo e($ogImage ?? asset(config('seo.og.image'))); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:url" content="<?php echo e(url()->current()); ?>">
    <meta property="og:type" content="<?php echo e($ogType ?? config('seo.og.type')); ?>">
    <meta property="og:site_name" content="<?php echo e(config('seo.og.site_name')); ?>">
    <meta property="og:locale" content="<?php echo e(config('seo.og.locale')); ?>">

    
    <meta name="twitter:card" content="<?php echo e(config('seo.twitter.card')); ?>">
    <meta name="twitter:site" content="<?php echo e(config('seo.twitter.site')); ?>">
    <meta name="twitter:title" content="<?php echo e($metaTitle ?? config('seo.defaults.title')); ?>">
    <meta name="twitter:description" content="<?php echo e($metaDescription ?? config('seo.defaults.description')); ?>">
    <meta name="twitter:image" content="<?php echo e($ogImage ?? asset(config('seo.og.image'))); ?>">

    
    <?php
        $settingService = app(\App\Services\SettingService::class);
        $companyInfo = $settingService->getCompanyInfo();
        $faviconPath = !empty($companyInfo['favicon']) ? Storage::url($companyInfo['favicon']) : asset('images/favicon.png');
    ?>
    <link rel="icon" type="image/x-icon" href="<?php echo e($faviconPath); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('images/apple-touch-icon.png')); ?>">

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">

    
    <style>
        [x-cloak] { display: none !important; }
    </style>

    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    
    <?php echo $__env->yieldPushContent('head'); ?>
    <?php echo $__env->yieldPushContent('meta'); ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('seo.analytics.google_analytics_id')): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo e(config('seo.analytics.google_analytics_id')); ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo e(config('seo.analytics.google_analytics_id')); ?>');
    </script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 overflow-x-hidden">
    
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-primary-600 text-white px-4 py-2 rounded-md z-50">
        <?php echo e(__('Skip to main content')); ?>

    </a>

    
    <?php echo $__env->make('layouts.partials.topbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('layouts.partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <main id="main-content">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <?php echo $__env->make('layouts.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('layouts.partials.floating-social', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    
    

    
    <button 
        x-data="{ show: false }"
        x-on:scroll.window="show = window.scrollY > 500"
        x-show="show"
        x-transition
        x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })"
        class="fixed bottom-24 right-6 bg-primary-600 text-white p-3 rounded-full shadow-lg hover:bg-primary-700 transition-colors z-40"
        aria-label="<?php echo e(__('Back to top')); ?>"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
        </svg>
    </button>

    
    <?php echo $__env->yieldPushContent('scripts'); ?>

    
    <?php
        $schemaService = app(\App\Services\SchemaService::class);
        $baseSchemas = [
            $schemaService->getOrganizationSchema(),
            $schemaService->getWebSiteSchema(),
        ];
        
        // Add LocalBusiness only on homepage
        if(request()->routeIs('home') || request()->is('/')) {
            $baseSchemas[] = $schemaService->getLocalBusinessSchema();
        }
    ?>
    <?php if (isset($component)) { $__componentOriginalc87a372b2c133bb0cf1ddca5ea69be3d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc87a372b2c133bb0cf1ddca5ea69be3d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.schema-markup','data' => ['schemas' => $baseSchemas]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('schema-markup'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['schemas' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($baseSchemas)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc87a372b2c133bb0cf1ddca5ea69be3d)): ?>
<?php $attributes = $__attributesOriginalc87a372b2c133bb0cf1ddca5ea69be3d; ?>
<?php unset($__attributesOriginalc87a372b2c133bb0cf1ddca5ea69be3d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc87a372b2c133bb0cf1ddca5ea69be3d)): ?>
<?php $component = $__componentOriginalc87a372b2c133bb0cf1ddca5ea69be3d; ?>
<?php unset($__componentOriginalc87a372b2c133bb0cf1ddca5ea69be3d); ?>
<?php endif; ?>

    
    <?php echo $__env->yieldPushContent('schema'); ?>
    <?php echo $__env->yieldPushContent('structured-data'); ?>
</body>
</html>

<?php /**PATH C:\xampp2\htdocs\berkahmandiri.co.id\resources\views/layouts/app.blade.php ENDPATH**/ ?>