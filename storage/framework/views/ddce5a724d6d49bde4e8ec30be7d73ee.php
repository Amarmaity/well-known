<?php $__env->startSection('title', 'Manager Review Details'); ?>

<?php $__env->startSection('breadcrumb', "Employee {$employee_id} / View Manager Review"); ?>

<?php $__env->startSection('body-class', 'special-page'); ?>


<?php $__env->startSection('content'); ?>

    <style>
        .span-tage .span-data {
            display: flex;
            justify-content: space-between;
            padding-right: 60px;
        }

        .span-tage tr {
            /* border-bottom: 1px solid #000; */
            margin-bottom: 30px;
        }
    </style>



    <!-- Back Button aligned to the right -->
    <div class="text-right">
        <a href="<?php echo e(url()->previous()); ?>" class="btn btn-secondary">Back</a>
    </div>



    <h2 class="heading">Manager Review Details: <?php echo e($employee_id); ?></h2>
    <!-- Manager Review History Table -->
    <div class="table-container">
        <div class="table-wrapper">
            <table id="managerReviewHistoryTable" class="table  table-bordered table-hover main-table">
                <thead>
                    <tr>
                        <th>Field</th>
                        <th>Rating</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>1. How would you rate the employee’s quality of work, including accuracy, neatness, and timeliness?</td>
                            <td>(<?php echo e($review->rate_employee_quality); ?>/5)</td>
                            <td><?php echo e($review->comments_rate_employee_quality); ?></td>
                        </tr>
                        <tr>
                            <td>2. Does the employee align their work with the organization's goals and objectives?</td>
                            <td>(<?php echo e($review->organizational_goals); ?> /5)</td>
                            <td><?php echo e($review->comments_organizational_goals); ?></td>
                        </tr>
                        <tr>
                            <td>3. How effectively does the employee contribute to team efforts and collaborate with colleagues?</td>
                            <td>(<?php echo e($review->collaborate_colleagues); ?> /5)</td>
                            <td><?php echo e($review->comments_collaborate_colleagues); ?></td>
                        </tr>
                        <tr>
                            <td>4. Has the employee shown leadership potential or accepted additional responsibilities?</td>
                            <td>(<?php echo e($review->leadership_responsibilities); ?>/5)</td>
                            <td><?php echo e($review->comments_leadership_responsibilities); ?></td>
                        </tr>
                        <tr>
                            <td>5. Can you provide an example of when the employee demonstrated problem-solving skills?</td>
                            <td>(<?php echo e($review->demonstrated); ?> /5)</td>
                            <td><?php echo e($review->comments_demonstrated); ?></td>
                        </tr>
                        <tr>
                            <td>6. How would you rate the employee’s innovative thinking and contribution to team success?</td>
                            <td>(<?php echo e($review->demonstrated); ?> /5)</td>
                            <td><?php echo e($review->comments_thinking_contribution); ?></td>
                        </tr>
                        <tr>
                            <td>7. Does the employee effectively keep you informed about work progress and issues?</td>
                            <td>(<?php echo e($review->informed_progress); ?> /5)</td>
                            <td><?php echo e($review->comments_comments_informed_progress); ?></td>
                        </tr>
                        <tr>
                            <td>Total Manager Review Score</td>
                            <td><?php echo e($review->ManagerTotalReview); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#managerReviewHistoryTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": false,  // Disable ordering
                "info": true,
                "lengthMenu": [5, 10, 25, 50],  // Allow different page lengths
                "columnDefs": [
                    { "targets": [0, 1], "searchable": true }  // Enable search on the first two columns
                ]
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/well-known/resources/views/reports/userDetailsManagerView.blade.php ENDPATH**/ ?>