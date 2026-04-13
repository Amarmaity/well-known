<?php $__env->startSection('title', 'Update User Details'); ?>
<?php $__env->startSection('breadcrumb'); ?>
Update <?php echo e($user->fname); ?> <?php echo e($user->lname); ?> <?php echo e($user->employee_id); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-title', 'Update User'); ?>
<?php $__env->startSection('body-class', 'special-page'); ?>

<?php $__env->startSection('content'); ?>
<div class="">
    <div class="">
        
        <a href="<?php echo e(route('userlist')); ?>" class="btn btn-secondary">Back</a>
    </div>
    <div class="content-block">
        <input type="checkbox" id="block1">
        <label for="block1" class="main-label">Edit Employee Details: <?php echo e($user->fname); ?> <?php echo e($user->lname); ?></label>
        <div class="content">
            <form action="<?php echo e(route('update-user', ['id' => $user->id])); ?>" method="POST" class="forms-block">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="row form-section">
                    <div class="col-md-6">
                        <label for="fname" class="forms-label">First Name</label>
                        <input type="text" name="fname" id="fname" class="form-control" value="<?php echo e($user->fname); ?>"
                            readonly>
                    </div>

                    <div class="col-md-6">
                        <label for="lname" class="forms-label">Last Name</label>
                        <input type="text" name="lname" id="lname" class="form-control" value="<?php echo e($user->lname); ?>"
                            readonly>
                    </div>


                    <div class="col-md-6">
                        <label for="mobno" class="forms-label">Mobile Number</label>
                        <input type="number" name="mobno" id="mobno" class="form-control" value="<?php echo e($user->mobno); ?>"
                            maxlength="10" min="0" required>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="forms-label">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?php echo e($user->email); ?>"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label for="employee_id" class="forms-label">Employee ID</label>
                        <input type="text" name="employee_id" id="employee_id" class="form-control"
                            value="<?php echo e($user->employee_id); ?>" readonly>
                    </div>

                    <div class="col-md-6">
                        <label for="dob" class="forms-label">Joining Date</label>
                        <input type="date" class="form-control" id="dob" name="dob" value="<?php echo e($user->dob); ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label for="gender" class="forms-label">Gender</label>
                        <select class="form-control" id="gender" name="gender">
                            <option value="male" <?php echo e($user->gender == 'male' ? 'selected' : ''); ?>>Male</option>
                            <option value="female" <?php echo e($user->gender == 'female' ? 'selected' : ''); ?>>Female</option>
                            <option value="other" <?php echo e($user->gender == 'other' ? 'selected' : ''); ?>>Other</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="designation" class="forms-label">Designation</label>
                        <select class="form-control" id="designation_dropdown" name="designation" required>
                            <option value="" disabled <?php echo e($user->designation == null ? 'selected' : ''); ?>>Select
                                Designation</option>
                            <option value="Hr" <?php echo e($user->designation == 'Hr' ? 'selected' : ''); ?>>Hr</option>
                            <option value="SEO" <?php echo e($user->designation == 'SEO' ? 'selected' : ''); ?>>SEO</option>
                            <option value="Admin" <?php echo e($user->designation == 'Admin' ? 'selected' : ''); ?>>Admin</option>
                            <option value="UI/UX Designer"
                                <?php echo e($user->designation == 'UI/UX Designer' ? 'selected' : ''); ?>>UI/UX Designer</option>
                            <option value="Quality Analyst"
                                <?php echo e($user->designation == 'Quality Analyst' ? 'selected' : ''); ?>>Quality Analyst</option>
                            <option value="Software Developer"
                                <?php echo e($user->designation == 'Software Developer' ? 'selected' : ''); ?>>Software Developer
                            </option>
                            <option value="Manager" <?php echo e($user->designation == 'Manager' ? 'selected' : ''); ?>>Manager
                            </option>
                            <option value="Business Development"
                                <?php echo e($user->designation == 'Business Development' ? 'selected' : ''); ?>>Business
                                Development(Sales)</option>
                        </select>
                    </div>


                    <div class="col-md-6">
                        <label for="division" class="forms-label">Division</label>
                        <select class="form-control" id="division_dropdown" name="division" required>
                            <option value="" disabled <?php echo e($user->division == null ? 'selected' : ''); ?>>Select Division
                            </option>
                            <option value="Kasba Office" <?php echo e($user->division == 'Kasba Office' ? 'selected' : ''); ?>>Kasba
                                Office</option>
                            <option value="Salt Lake 3B" <?php echo e($user->division == 'Salt Lake 3B' ? 'selected' : ''); ?>>Salt
                                Lake 3B</option>
                            <option value="Salt Lake 17B" <?php echo e($user->division == 'Salt Lake 17B' ? 'selected' : ''); ?>>
                                Salt Lake 17B</option>
                        </select>
                    </div>


                    


                    <style>

                        .search-wrap{
                            display: flex;
                            flex-direction: column;
                        }

                    </style>

                <div class="client-hide col-md-6 search-wrap" id="manager-name-field">
    <label for="manager_id" class="forms-label">Manager Name</label>

    <select class="form-control select2-manager" id="manager_id" name="manager_id" required>
        <?php if($user->manager_id): ?>
            <option value="<?php echo e($user->manager_id); ?>" selected><?php echo e($user->manager_name); ?></option>
        <?php else: ?>
            <option value="">-- Select Manager --</option>
        <?php endif; ?>
    </select>

    <!-- This will be filled automatically by JS on select -->
    <input type="hidden" name="manager_name" id="manager_name" value="<?php echo e($user->manager_name); ?>">
</div>



                    <div class="col-md-6">
                        <label for="user_type_dropdown" class="forms-label">User Type</label>
                        <select class="form-control" id="user_type_dropdown" name="user_type" required>
                            <option value="" disabled <?php echo e($user->user_type == null ? 'selected' : ''); ?>>Select User Type
                            </option>
                            <option value="admin" <?php echo e($user->user_type == 'admin' ? 'selected' : ''); ?>>Admin</option>
                            <option value="hr" <?php echo e($user->user_type == 'hr' ? 'selected' : ''); ?>>HR</option>
                            <option value="users" <?php echo e($user->user_type == 'users' ? 'selected' : ''); ?>>Users</option>
                            <option value="manager" <?php echo e($user->user_type == 'manager' ? 'selected' : ''); ?>>Manager
                            </option>
                        </select>

                        <!-- Hidden input to actually submit the value -->
                        
                    </div>


                    <div class="client-hide col-md-6">
                        <label for="probation_date" class="forms-label">Probation Date</label>
                        <input type="date" name="probation_date" class="form-control" id="probation_date"
                            value="<?php echo e($user->probation_date); ?>">
                    </div>

                    <div class="client-hide col-md-6">
                        <label for="salary" class="forms-label">Salary</label>
                        <input type="number" class="form-control" id="salary" name="salary" placeholder="Enter Salary"
                            min="0" value="<?php echo e($user->salary); ?>" required>
                    </div>


                    <div class="col-md-6">
                        <label for="password" class="forms-label">Password</label>
                        
                            <input type="password" class="form-control" id="password" name="password"
                             placeholder="Enter new password (leave blank to keep existing)">
                    </div>

                    <div class="col-md-6" id="review-section">
                        <label class="forms-label d-block">Selected Person Can Review:</label>
                        <?php
                        $availableRoles = ['admin', 'hr', 'users', 'manager', 'client'];

                        // Define roles to hide based on user_type
                        $hiddenRoles = [];

                        switch ($user->user_type) {
                        case 'admin':
                        $hiddenRoles = ['admin', 'users', 'manager', 'client'];
                        break;
                        case 'manager':
                        $hiddenRoles = ['manager', 'users', 'client'];
                        break;
                        case 'hr':
                        $hiddenRoles = ['hr', 'users', 'manager','client'];
                        break;
                        case 'users':
                        $hiddenRoles = ['users'];
                        break;
                        default:
                        $hiddenRoles = []; // client or other: show all
                        }
                        ?>

                        <?php $__currentLoopData = $availableRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(!in_array($role, $hiddenRoles)): ?>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input check-input" type="checkbox" name="user_roles[]"
                                value="<?php echo e($role); ?>" id="<?php echo e($role === 'client' ? 'client-role' : $role); ?>"
                                <?php echo e(in_array($role, $userRoles) ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="<?php echo e($role === 'client' ? 'client-role' : $role); ?>">
                                <?php echo e(ucfirst($role)); ?>

                            </label>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="col-md-6">
                        <label for="cnf-password" class="forms-label">Confirm Password</label>
                        
                            <input type="password" class="form-control" id="cnf-password" name="password_confirmation"
                              placeholder="Confirm new password">
                    </div>

                    
                </div>
                <div class="client-hide mt-3" id="client-select-container">
                    <label for="client_id" class="forms-label">Select Client</label>
                    <select class="form-control" id="client_id" name="client_id[]" multiple="multiple"
                        style="width: 100%">
                        <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($client->id); ?>" selected><?php echo e($client->client_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="d-flex gap-3 mt-3">
                    <button type="submit" class="btn btn-secondary">Update</button>
                    <a href="<?php echo e(url()->previous()); ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Include jQuery and Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>



<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />

<!-- Select2 CSS + Theme -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
    rel="stylesheet" />

<!-- JS Libraries -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>





<!-- Initialize Select2 -->
<script>
    $(document).ready(function () {
            // SweetAlert success popup
            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '<?php echo e(session('
                success ')); ?>',
                    timer: 2500,
                    showConfirmButton: false
                });
            <?php endif; ?>
    });
    
    <?php if($errors->any()): ?>
   
        $(document).ready(function () {
            let errorMessages = `<?php echo implode('<br>', $errors->all()); ?>`;
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: errorMessages,
                confirmButtonText: 'Okay'
            });
        });
   
<?php endif; ?>
    $('#client_id').select2({
    theme: 'bootstrap-5',
    placeholder: "Select Client",
    allowClear: true,
    maximumSelectionLength: 10,
    ajax: {
        url: "<?php echo e(route('get.clients')); ?>",
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
            return {
                results: $.map(data, function (client) {
                    return {
                        id: client.id,
                        text: client.client_name + ' (' + client.company_name + ')',
                        client_name: client.client_name,
                        company_name: client.company_name
                    };
                })
            };
        },
        cache: true
    },
    templateResult: function (data) {
        if (!data.id) return data.text;
        return $('<div><strong>' + data.client_name + '</strong><br><small>' + data.company_name + '</small></div>');
    },
    templateSelection: function (data) {
        return data.text || data.client_name;
    }
});



        

        <?php if(session('success')): ?>

            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?php echo e(session('
            success ')); ?>',
                timer: 2500,
                showConfirmButton: false
            });

        <?php endif; ?>


        $(document).ready(function () {
            function toggleClientSelect() {
                const isClientChecked = $('#client-role').is(':checked');

                if (isClientChecked) {
                    $('#client-select-container').show();
                } else {
                    // Clear selected clients and hide the dropdown
                    $('#client_id').val(null).trigger('change');
                    $('#client-select-container').hide();
                }
            }

            // Run on page load
            toggleClientSelect();

            // Run when the checkbox is changed
            $('#client-role').on('change', function () {
                toggleClientSelect();
            });
        });
    //      function toggleManagerField() {
    //     const isManagerChecked = $('#manager').is(':checked');
    //     if (isManagerChecked) {
    //         $('#manager-name-field').show();
    //     } else {
    //         $('#manager-name-field').hide();
    //     }
    // }
function toggleManagerField() {
    const isManagerChecked = $('#manager').is(':checked');
    const $managerField = $('#manager-name-field');
    const $managerSelect = $('#manager_id');

    if (isManagerChecked) {
        $managerField.show();
        $managerSelect.prop('required', true);
    } else {
        $managerField.hide();
        $managerSelect.prop('required', false);
        $managerSelect.val(null).trigger('change');
        $('#manager_name').val('');
    }
}

    $(document).ready(function () {
        // Initial check
        toggleManagerField();

        // Trigger on change
        $('.check-input').on('change', toggleManagerField);
    });

 document.querySelector('.forms-block').addEventListener('submit', function (e) {
        const joiningDate = new Date(document.getElementById('dob').value);
        const probationDate = new Date(document.getElementById('probation_date').value);

        if (probationDate < joiningDate) {
            e.preventDefault(); // Stop form from submitting
            alert("⚠️ Probation Date cannot be earlier than Joining Date.");
        }
    });
    

$('.select2-manager').select2({
    placeholder: '-- Select Manager --',
    allowClear: true,
    width: '100%',
    ajax: {
        url: '/api/manager-names',
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term
            };
        },
        processResults: function (data) {
            return {
                results: data
            };
        },
        cache: true
    }
});

// ✅ Add event listener **after** Select2 is initialized
$('.select2-manager').on('select2:select', function (e) {
    const data = e.params.data;
    console.log('Selected Manager:', data);
    $('#manager_name').val(data.text);  // Updates hidden input
});

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\well-known\resources\views/admin/editUser.blade.php ENDPATH**/ ?>