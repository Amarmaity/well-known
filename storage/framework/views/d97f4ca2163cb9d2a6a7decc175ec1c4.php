<?php $__env->startSection('title', 'Access Management'); ?>
<?php $__env->startSection('breadcrumb', 'Access Management'); ?>
<?php $__env->startSection('page-title', 'Access Management'); ?>
<?php $__env->startSection('body-class', 'special-page'); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid">
    <div class="back-button">
        <a href="<?php echo e(route('setting-view')); ?>" class="btn btn-secondary mb-3">
            <i class="bi bi-arrow-left"></i>
            Back
        </a>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">
                        <i class="bi bi-shield-lock"></i>
                        Access Management
                    </h4>

                    <small class="text-muted">
                        Manage sidebar permissions by designation
                    </small>
                </div>
                <button id="savePermission" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i>
                    Save Permission
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-5">
                    <label class="form-label fw-bold">
                        Designation
                        <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" id="designation">
                        <option value="">
                            Select Designation
                        </option>
                        <?php $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $designation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($designation->id); ?>">
                            <?php echo e(ucwords($designation->designation_name)); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <hr>
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAll">
                    <label class="form-check-label fw-bold" for="selectAll">
                        Select All Permissions
                    </label>
                </div>
            </div>
            <div class="accordion" id="permissionAccordion">
                <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="accordion-item mb-2">
                    <h2 class="accordion-header" id="heading<?php echo e($parent->id); ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse<?php echo e($parent->id); ?>">
                            <div class="d-flex align-items-center w-100">
                                <input type="checkbox" class="form-check-input me-3 parent-checkbox"
                                    data-parent="<?php echo e($parent->id); ?>">
                                <strong>
                                    <?php echo e($parent->module_name); ?>

                                </strong>
                            </div>
                        </button>
                    </h2>
                    <div id="collapse<?php echo e($parent->id); ?>" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <?php if($parent->children->count()): ?>
                            <div class="row">
                                <?php $__currentLoopData = $parent->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input child-checkbox" type="checkbox"
                                            value="<?php echo e($child->id); ?>" data-parent="<?php echo e($parent->id); ?>"
                                            id="module<?php echo e($child->id); ?>">
                                        <label class="form-check-label" for="module<?php echo e($child->id); ?>">
                                            <?php echo e($child->module_name); ?>

                                        </label>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <?php else: ?>
                            <div class="form-check">
                                <input class="form-check-input single-checkbox" type="checkbox"
                                    value="<?php echo e($parent->id); ?>" id="module<?php echo e($parent->id); ?>">
                                <label class="form-check-label">
                                    <?php echo e($parent->module_name); ?>

                                </label>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>

<script>
    $(function() {

            /*
            =====================================
            SELECT ALL
            =====================================
            */

            $('#selectAll').change(function() {

                $('input[type=checkbox]').prop(

                    'checked',

                    $(this).is(':checked')

                );

            });


            /*
            =====================================
            PARENT
            =====================================
            */

            $('.parent-checkbox').change(function() {

                let parent = $(this).data('parent');

                $('.child-checkbox[data-parent="' + parent + '"]')

                    .prop('checked', $(this).is(':checked'));

            });



            /*
            =====================================
            CHILD
            =====================================
            */

            $('.child-checkbox').change(function() {

                let parent = $(this).data('parent');

                let total = $('.child-checkbox[data-parent="' + parent + '"]').length;

                let checked = $('.child-checkbox[data-parent="' + parent + '"]:checked').length;

                $('.parent-checkbox[data-parent="' + parent + '"]')

                    .prop('checked', total == checked);

            });

        });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/well-known/resources/views/admin/accessmanagement.blade.php ENDPATH**/ ?>