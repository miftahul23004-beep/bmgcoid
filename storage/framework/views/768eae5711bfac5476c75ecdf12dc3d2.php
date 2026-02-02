<div
    <?php echo e($attributes
            ->merge([
                'id' => $getId(),
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)); ?>

>
    <?php echo e($getChildSchema()); ?>

</div>
<?php /**PATH C:\xampp2\htdocs\berkahmandiri.co.id\vendor\filament\schemas\resources\views/components/grid.blade.php ENDPATH**/ ?>