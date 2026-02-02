<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'email' => '',
    'class' => '',
    'showIcon' => false,
    'iconClass' => 'w-4 h-4 mr-2'
]));

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

foreach (array_filter(([
    'email' => '',
    'class' => '',
    'showIcon' => false,
    'iconClass' => 'w-4 h-4 mr-2'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $parts = explode('@', $email);
    $user = $parts[0] ?? '';
    $domain = $parts[1] ?? '';
    $uniqueId = 'email-' . uniqid();
?>

<a 
    href="#" 
    id="<?php echo e($uniqueId); ?>"
    class="protected-email <?php echo e($class); ?>"
    data-u="<?php echo e(base64_encode($user)); ?>"
    data-d="<?php echo e(base64_encode($domain)); ?>"
    onclick="return false;"
>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showIcon): ?>
    <svg class="<?php echo e($iconClass); ?> inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
    </svg>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <span class="email-text">[<?php echo e(__('Loading...')); ?>]</span>
</a>
<?php /**PATH C:\xampp2\htdocs\berkahmandiri.co.id\resources\views/components/protected-email.blade.php ENDPATH**/ ?>