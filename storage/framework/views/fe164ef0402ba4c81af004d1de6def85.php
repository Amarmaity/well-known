<?php $__env->startSection('title', 'HR Review Details'); ?>

<?php $__env->startSection('breadcrumb', "Employee {$employee_id} / View Hr Review"); ?>

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




    <div class="container">
        
        <!-- Back Button aligned to the right -->
        <div class="text-right mb-3">
            <a href="<?php echo e(url()->previous()); ?>" class="btn btn-secondary">Back</a>
        </div>
        
        <h2 class="heading">HR Review Details: <?php echo e($employee_id); ?></h2>
        <!-- HR Review History Table -->
         <div class="table-container span-tage">
            <div class="table-wrapper">
        <table id="hrReviewHistoryTable" class="table table-bordered table-hover main-table">
            <thead>
                <tr>
                    <th class="span-data">Field <span>Rating</span></th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td  class="span-data">1. How would you rate the employee’s adherence to company policies and procedures? <span>(<?php echo e($review->adherence_hr); ?>/5)</span></td>
                        <td><?php echo e($review->comments_adherence_hr); ?></td>
                    </tr>
                    <tr>
                        <td class="span-data">2. Does the employee maintain professionalism and a positive attitude in the workplace? <span>(<?php echo e($review->professionalism_positive); ?> /5)</span></td>
                        <td><?php echo e($review->comments_professionalism); ?></td>
                    </tr>
                    <tr>
                        <td class="span-data">3. How well does the employee respond to feedback or suggestions for improvement from colleagues? <span>(<?php echo e($review->respond_feedback); ?> /5)</span></td>
                        <td><?php echo e($review->comments_respond_feedback); ?></td>
                    </tr>
                    <tr>
                        <td class="span-data">3. Does the employee take the initiative to seek feedback and act on it? <span>(<?php echo e($review->initiative); ?> /5)</span></td>
                        <td><?php echo e($review->comments_initiative); ?></td>
                    </tr>
                    <tr>
                        <td class="span-data">4. Has the employee shown interest in learning and participating in training programs? <span>(<?php echo e($review->interest_learning); ?> /5)</span></td>
                        <td><?php echo e($review->comments_interest_learning); ?></td>
                    </tr>
                    <tr>
                        <td class="span-data">5. Does the employee consistently adhere to the company's leave policy? <span>(<?php echo e($review->company_leave_policy); ?> /5)</span></td>
                        <td><?php echo e($review->comments_company_leave_policy); ?></td>
                    </tr>
                    <tr>
                        <td>Total HR Review Score</td>
                        <td><?php echo e($review->HrTotalReview); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        </div>
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
        $(document).ready(function() {
            $('#hrReviewHistoryTable').DataTable({
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/relaxed-tereshkova.74-208-156-247.plesk.page/modest-gagarin.74-208-156-247.plesk.page/resources/views/reports/userDetailsHrView.blade.php ENDPATH**/ ?>