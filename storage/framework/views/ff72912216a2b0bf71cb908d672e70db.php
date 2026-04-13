 <!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

<?php $__env->startSection('title', 'Admin Dashboard'); ?> <!-- Page Title -->

<?php $__env->startSection('breadcrumb', 'Admin'); ?> <!-- Breadcrumb -->

<?php $__env->startSection('page-title', 'Admin Dashboard'); ?> <!-- Page Title in Breadcrumb -->

<?php $__env->startSection('body-class', 'special-page'); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/well-known/resources/views/delostyleUsers/admin-dashboard.blade.php ENDPATH**/ ?>