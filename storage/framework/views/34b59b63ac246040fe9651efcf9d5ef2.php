<?php $__env->startSection('title', 'Designation Management'); ?>

<?php $__env->startSection('breadcrumb', 'Designation Management'); ?>

<?php $__env->startSection('page-title', 'Designation Management'); ?>
<?php $__env->startSection('body-class', 'special-page'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="back-button">
        <a href="<?php echo e(route('setting-view')); ?>" class="btn btn-secondary mb-3">
            <i class="bi bi-arrow-left"></i>
            Back
        </a>
    </div>
    <div class="card">
        <div class="card-header">
            <h4>Designation Management</h4>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('designation-store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" name="designation_name" class="form-control" placeholder="Designation Name">
                    </div>
                    <div class="col-md-4">

                        <button class="btn btn-primary w-100">
                            Add Designation
                        </button>
                    </div>
                </div>
            </form>
            <hr>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Designation</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $designation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><?php echo e($designation->designation_name); ?></td>
                        <td>
                            <?php if($designation->status): ?>
                            <span class="badge bg-success">
                                Active
                            </span>
                            <?php else: ?>
                            <span class="badge bg-danger">
                                Inactive
                            </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm editDesignation"
                                data-id="<?php echo e($designation->id); ?>">
                                Edit
                            </button>
                            <button type="button" class="btn btn-info btn-sm changeStatus"
                                data-id="<?php echo e($designation->id); ?>">
                                <?php echo e($designation->status ? 'Deactivate' : 'Activate'); ?>

                            </button>
                            <button type="button" class="btn btn-danger btn-sm deleteDesignation"
                                data-id="<?php echo e($designation->id); ?>">
                                Delete
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <!-- Edit Modal -->
        <div class="modal fade" id="editDesignationModal" tabindex="-1">
            <div class="modal-dialog">
                <form id="updateDesignationForm">

                    <?php echo csrf_field(); ?>

                    <div class="modal-content">

                        <div class="modal-header">
                            <h5>Edit Designation</h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            <input type="hidden" id="designation_id">

                            <div class="form-group">

                                <label>Designation</label>

                                <input type="text" id="designation_name" class="form-control">

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button class="btn btn-success" type="submit">

                                Update

                            </button>

                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
            const editModal = new bootstrap.Modal(document.getElementById('editDesignationModal'));

            //================== EDIT ===================

            $(document).on('click', '.editDesignation', function() {

                let id = $(this).data('id');
                let url = "<?php echo e(route('designation-edit', ':id')); ?>";
                url = url.replace(':id', id);
                $.get(url, function(res) {

                    $('#designation_id').val(res.id);

                    $('#designation_name').val(res.designation_name);

                    editModal.show();

                });

            });


            //================ UPDATE ===================

            $('#updateDesignationForm').on('submit', function(e) {

                e.preventDefault();

                let id = $('#designation_id').val();
                let url = "<?php echo e(route('designation-update', ':id')); ?>";
                url = url.replace(':id', id);
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>',
                        designation_name: $('#designation_name').val()
                    },
                    success: function(res) {
                        alert(res.message);
                        location.reload();
                    },
                    error: function() {

                        alert('Update Failed');

                    }
                });
            });
            //=============== STATUS ====================

            $(document).on('click', '.changeStatus', function() {

                let id = $(this).data('id');
                let url = "<?php echo e(route('designation-status', ':id')); ?>";
                url = url.replace(':id', id);

                $.ajax({

                    url: url,

                    type: 'POST',

                    data: {
                        _token: '<?php echo e(csrf_token()); ?>'
                    },

                    success: function(res) {

                        location.reload();

                    }

                });

            });


            //================ DELETE ====================

            $(document).on('click', '.deleteDesignation', function() {

                let id = $(this).data('id');

                if (confirm('Are you sure?')) {

                    let url = "<?php echo e(route('designation-destroy', ':id')); ?>";
                    url = url.replace(':id', id);

                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: '<?php echo e(csrf_token()); ?>'
                        },
                        success: function(res) {
                            alert(res.message);
                            location.reload();
                        },
                        error: function() {
                            alert('Delete Failed');
                        }
                    });

                }

            });

        });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\well-known\resources\views/admin/Designation/index.blade.php ENDPATH**/ ?>