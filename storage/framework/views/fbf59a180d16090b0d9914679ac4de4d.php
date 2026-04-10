 <!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

<?php $__env->startSection('title', 'Super Admin Dashboard'); ?> <!-- Page Title -->

<?php $__env->startSection('breadcrumb', 'Super Admin'); ?> <!-- Breadcrumb -->

<?php $__env->startSection('page-title', 'Super Admin Dashboard'); ?> <!-- Page Title in Breadcrumb -->

<?php $__env->startSection('body-class', 'special-page'); ?>

<?php $__env->startSection('content'); ?>
<div cass="body-class">
    
</div>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">

                  
                
                <p>Email: <?php echo e(Session::get('user_email')); ?></p>
                <p>This is the Super Admin Dashboard.</p>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/relaxed-tereshkova.74-208-156-247.plesk.page/modest-gagarin.74-208-156-247.plesk.page/resources/views/admin/SuperAdminDashbord.blade.php ENDPATH**/ ?>