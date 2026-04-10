 <!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

<?php $__env->startSection('title', 'Client Dashboard'); ?> <!-- Page Title -->

<?php $__env->startSection('breadcrumb', 'Client'); ?> <!-- Breadcrumb -->

<?php $__env->startSection('page-title', 'Client Dashboard'); ?> <!-- Page Title in Breadcrumb -->


<?php $__env->startSection('body-class', 'special-page'); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/relaxed-tereshkova.74-208-156-247.plesk.page/modest-gagarin.74-208-156-247.plesk.page/resources/views/delostyleUsers/client-dashboard.blade.php ENDPATH**/ ?>