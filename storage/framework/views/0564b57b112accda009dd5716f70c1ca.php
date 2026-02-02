<?php extract((new \Illuminate\Support\Collection($attributes->getAttributes()))->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['class']));

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

foreach (array_filter((['class']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php if (isset($component)) { $__componentOriginal55fb16c72e18dadef3b8ad39feae4ebd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal55fb16c72e18dadef3b8ad39feae4ebd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icons.tiktok','data' => ['class' => $class]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icons.tiktok'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($class)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>


<?php echo e($slot ?? ""); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal55fb16c72e18dadef3b8ad39feae4ebd)): ?>
<?php $attributes = $__attributesOriginal55fb16c72e18dadef3b8ad39feae4ebd; ?>
<?php unset($__attributesOriginal55fb16c72e18dadef3b8ad39feae4ebd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal55fb16c72e18dadef3b8ad39feae4ebd)): ?>
<?php $component = $__componentOriginal55fb16c72e18dadef3b8ad39feae4ebd; ?>
<?php unset($__componentOriginal55fb16c72e18dadef3b8ad39feae4ebd); ?>
<?php endif; ?><?php /**PATH C:\xampp2\htdocs\berkahmandiri.co.id\storage\framework\views/4a99fb1abf88415654107649320d4aba.blade.php ENDPATH**/ ?>