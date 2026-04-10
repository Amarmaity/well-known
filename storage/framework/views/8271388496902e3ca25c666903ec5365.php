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
            <td>1. Has the employee demonstrated regular attendance and punctuality?</td>
            <td>(<?php echo e($user->demonstrated_attendance); ?>/5)</td>
            <td><?php echo e($user->comments_demonstrated_attendance); ?></td>
        </tr>
        <tr>
            <td>2. How well does the employee manage time within the shift?</td>
            <td>(<?php echo e($user->employee_manage_shift); ?> /5)</td>
            <td><?php echo e($user->comments_employee_manage_shift); ?></td>
        </tr>
        <tr>
            <td>3. How would you rate the employee’s accuracy and neatness in reports and documentation?</td>
            <td>(<?php echo e($user->documentation_neatness); ?> /5)</td>
            <td><?php echo e($user->comments_documentation_neatness); ?></td>
        </tr>
        <tr>
            <td>4. Has the employee followed administrative procedures and job instructions properly?</td>
            <td>(<?php echo e($user->followed_instructions); ?> /5)</td>
            <td><?php echo e($user->comments_followed_instructions); ?></td>
        </tr>
        <tr>
            <td>5. Does the employee effectively manage time and stay productive during working hours?</td>
            <td>(<?php echo e($user->productive); ?>/5)</td>
            <td><?php echo e($user->comments_productive); ?></td>
        </tr>
        <tr>
            <td>6. How well does the employee handle changes in schedules or assignments?</td>
            <td>(<?php echo e($user->changes_schedules); ?>/5)</td>
            <td><?php echo e($user->comments_changes_schedules); ?></td>
        </tr>
        <tr>
            <td>7. Does the employee consistently adhere to the company's leave policy?</td>
            <td>(<?php echo e($user->leave_policy); ?> /5)</td>
            <td><?php echo e($user->comments_leave_policy); ?></td>
        </tr>
        <tr>
            <td>8. Has there been any salary deduction due to the employee's leave?</td>
            <td>(<?php echo e($user->salary_deduction); ?>/5)</td>
            <td><?php echo e($user->comments_salary_deduction); ?></td>
        </tr>
        <tr>
            <td>9. How well does the employee interact with the housekeeping staff?</td>
            <td>(<?php echo e($user->interact_housekeeping); ?>/5)</td>
            <td><?php echo e($user->comments_interact_housekeeping); ?></td>
        </tr>
        <tr>
            <th>Total Admin Review</th>
            <td><?php echo e($user->AdminTotalReview); ?></td>
        </tr>
    </table>
</div><?php /**PATH /home3/delose1a/evalon.delostylestudio.co.in/resources/views/reports/adminReport.blade.php ENDPATH**/ ?>