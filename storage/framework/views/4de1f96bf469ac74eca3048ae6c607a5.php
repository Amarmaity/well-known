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
<div class="table-responsive">
    <table class="table table-bordered">
        
        <thead>
            <tr>
                <th>Field</th>
                <th>Rating</th>
                <th>Comments</th>
            </tr>
        </thead>
        <tr>
            <td>1. How would you rate the employee’s adherence to company policies and procedures?</td>
            <td>(<?php echo e($user->adherence_hr); ?>/5)</td>
            <td><?php echo e($user->comments_adherence_hr); ?></td>
        </tr>
        <tr>
            <td>2. Does the employee maintain professionalism and a positive attitude in the workplace? </td>
            <td>(<?php echo e($user->professionalism_positive); ?>/5)</td>
            <td><?php echo e($user->comments_professionalism); ?></td>
        </tr>
        <tr>
            <td>3. How well does the employee respond to feedback or suggestions for improvement from colleagues?</td>
            <td>(<?php echo e($user->respond_feedback); ?>/5)</td>
            <td><?php echo e($user->comments_respond_feedback); ?></td>
        </tr>
        <tr>
            <td>4. Does the employee take the initiative to seek feedback and act on it?</td>
            <td> (<?php echo e($user->initiative); ?>/5)</td>
            <td><?php echo e($user->comments_initiative); ?></td>
        </tr>
        <tr>
            <td>5. Has the employee shown interest in learning and participating in training programs?</td>
            <td>(<?php echo e($user->interest_learning); ?>/5)</td>
            <td><?php echo e($user->comments_interest_learning); ?></td>
        </tr>
        <tr>
            <td>6. Does the employee consistently adhere to the company's leave policy? </td>
            <td>(<?php echo e($user->company_leave_policy); ?>/5)</td>
            <td><?php echo e($user->comments_company_leave_policy); ?></td>
        </tr>
        <tr>
            <th>Total HR Review</th>
            <td><?php echo e($user->HrTotalReview); ?></td>
        </tr>
    </table>
</div><?php /**PATH /home3/delose1a/evalon.delostylestudio.co.in/resources/views/reports/hrReport.blade.php ENDPATH**/ ?>