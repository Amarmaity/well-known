 <!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

<?php $__env->startSection('title', 'User Dashboard'); ?> <!-- Page Title -->

<?php $__env->startSection('breadcrumb', 'User'); ?> <!-- Breadcrumb -->

<?php $__env->startSection('page-title', 'User Dashboard'); ?> <!-- Page Title in Breadcrumb -->


<?php $__env->startSection('body-class', 'special-page'); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\well-known\resources\views/delostyleUsers/users-dashboard.blade.php ENDPATH**/ ?>