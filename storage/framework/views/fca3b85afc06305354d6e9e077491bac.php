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
<div class="table-responsive span-tage">
    <table class="table table-bordered">
        
        <thead>
            <tr>
                <th class="span-data">Field <span>Rating</span></th>
                <th>Comments</th>
            </tr>
        </thead>
        <tr>
            <th class="span-data">1. How would you rate the employee’s adherence to company policies and procedures?
                <span>(<?php echo e($user->adherence_hr); ?>/5)</span></th>
            <td><?php echo e($user->comments_adherence_hr); ?></td>
        </tr>
        <tr>
            <th class="span-data">2. Does the employee maintain professionalism and a positive attitude in the
                workplace? <span>(<?php echo e($user->professionalism_positive); ?>/5)</span></th>
            <td><?php echo e($user->comments_professionalism); ?></td>
        </tr>
        <tr>
            <th class="span-data">3. How well does the employee respond to feedback or suggestions for improvement from
                colleagues? <span>(<?php echo e($user->respond_feedback); ?>/5)</span></th>
            <td><?php echo e($user->comments_respond_feedback); ?></td>
        </tr>
        <tr>
            <th class="span-data">4. Does the employee take the initiative to seek feedback and act on it?
                <span>(<?php echo e($user->initiative); ?>/5)</span></th>
            <td><?php echo e($user->comments_initiative); ?></td>
        </tr>
        <tr>
            <th class="span-data">5. Has the employee shown interest in learning and participating in training programs?
                <span>(<?php echo e($user->interest_learning); ?>/5)</span></th>
            <td><?php echo e($user->comments_interest_learning); ?></td>
        </tr>
        <tr>
            <th class="span-data">6. Does the employee consistently adhere to the company's leave policy?
                <span>(<?php echo e($user->company_leave_policy); ?>/5)</span></th>
            <td><?php echo e($user->comments_company_leave_policy); ?></td>
        </tr>
        <tr>
            <th>Total HR Review</th>
            <td><?php echo e($user->HrTotalReview); ?></td>
        </tr>

        <!-- Created At & Updated At -->
        
    </table>
</div><?php /**PATH /var/www/vhosts/relaxed-tereshkova.74-208-156-247.plesk.page/modest-gagarin.74-208-156-247.plesk.page/resources/views/reports/hrReport.blade.php ENDPATH**/ ?>