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
    .set-position tr{
            display: flex;
            gap: 100px;
           
        }
        .set-position tr, .set-position th,  .set-position td,  .set-position tbody{
             cursor: auto !important;
            }

        .set-position td , .set-position th{
            width: 100%;
        }

        .set-position thead tr {
            display: flex;
            justify-content: space-between;
        }

        .set-position thead tr th {
            display: flex;
            width: 100%;
            justify-content: flex-start;
        }
        .set-position .span-data , .set-position th{
            color: #000000 !important;
        }

        @media (max-width: 1124px) {
            .set-position tr {
                gap: 40px;
            }
        }
</style>
<div class="container table-container span-tage">
    <div class="table-responsive table-wrapper">
        <table class="table table-bordered table-hover main-table set-position">
            
            <thead>
                <tr>
                    <th class="span-data">Field <span>Rating</span></th>
                    <th>Comments</th>
                </tr>
            </thead>

            <tr>
                <th class="span-data">1. Understand Requirements <span>(<?php echo e($user->understand_requirements); ?>/5)</span></th>
                <td><?php echo e($user->comment_understand_requirements); ?></td>
            </tr>
            <tr>
                <th class="span-data">2. Business Needs <span>(<?php echo e($user->business_needs); ?>/5)</span></th>
                <td><?php echo e($user->comments_business_needs); ?></td>
            </tr>
            <tr>
                <th class="span-data">3. Detailed Project Scope <span>(<?php echo e($user->detailed_project_scope); ?>/5)</span>
                </th>
                <td><?php echo e($user->comments_detailed_project_scope); ?></td>
            </tr>
            <tr>
                <th class="span-data">4. Responsive to Project Reach  <span>(<?php echo e($user->responsive_reach_project); ?>/5)</span>
                </th>
                <td><?php echo e($user->comments_responsive_reach_project); ?></td>
            </tr>
            <tr>
                <th class="span-data">5. Comfortable Discussing Requirements <span>(<?php echo e($user->comfortable_discussing); ?>/5)</span></th>
                <td><?php echo e($user->comments_comfortable_discussing); ?></td>
            </tr>
            <tr>
                <th class="span-data">6. Regular Updates <span>(<?php echo e($user->regular_updates); ?>/5)</span></th>
                <td><?php echo e($user->comments_regular_updates); ?></td>
            </tr>
            <tr>
                <th class="span-data">7. Concerns Addressed <span>(<?php echo e($user->concerns_addressed); ?> /5)</span></th>
                <td><?php echo e($user->comments_concerns_addressed); ?></td>
            </tr>
            <tr>
                <th class="span-data">8. Technical Expertise <span>(<?php echo e($user->technical_expertise); ?> /5)</span></th>
                <td><?php echo e($user->comments_technical_expertise); ?></td>
            </tr>
            <tr>
                <th class="span-data">9. Best Practices Followed <span>(<?php echo e($user->best_practices); ?>/5)</span></th>
                <td><?php echo e($user->comments_best_practices); ?></td>
            </tr>
            <tr>
                <th class="span-data">10.Suggests Innovative Solutions <span>(<?php echo e($user->suggest_innovative); ?>/5)</span>
                </th>
                <td><?php echo e($user->comments_suggest_innovative); ?></td>
            </tr>
            <tr>
                <th class="span-data">11. Quality of Code <span>(<?php echo e($user->quality_code); ?>/5)</span></th>
                <td><?php echo e($user->comments_quality_code); ?></td>
            </tr>
            <tr>
                <th class="span-data">12. Encountered Issues <span>(<?php echo e($user->encounter_issues); ?>/5)</span></th>
                <td><?php echo e($user->comments_encounter_issues); ?></td>
            </tr>
            <tr>
                <th class="span-data">13. Code Scalability <span>(<?php echo e($user->code_scalable); ?>/5)</span></th>
                <td><?php echo e($user->comments_code_scalable); ?></td>
            </tr>
            <tr>
                <th class="span-data">14. Solution Performance <span>(<?php echo e($user->solution_perform); ?>/5)</span></th>
                <td><?php echo e($user->comments_solution_perform); ?></td>
            </tr>
            <tr>
                <th class="span-data">15. Project Delivered <span>(<?php echo e($user->project_delivered); ?>/5)</span></th>
                <td><?php echo e($user->comments_project_delivered); ?></td>
            </tr>
            <tr>
                <th class="span-data">16. Communicated & Handled <span>(<?php echo e($user->communicated_handled); ?>/5)</span></th>
                <td><?php echo e($user->comments_communicated_handled); ?></td>
            </tr>
            <tr>
                <th class="span-data">17. Development Process <span>(<?php echo e($user->development_process); ?>/5)</span></th>
                <td> <?php echo e($user->comments_development_process); ?></td>
            </tr>
            <tr>
                <th class="span-data">18. Unexpected Challenges <span>(<?php echo e($user->unexpected_challenges); ?>/5)</span></th>
                <td><?php echo e($user->comments_unexpected_challenges); ?></td>
            </tr>
            <tr>
                <th class="span-data">19. Effective Workarounds <span>(<?php echo e($user->effective_workarounds); ?>/5)</span></th>
                <td><?php echo e($user->comments_effective_workarounds); ?></td>
            </tr>
            <tr>
                <th class="span-data">20. Bugs & Issues <span>(<?php echo e($user->bugs_issues); ?>/5)</span></th>
                <td><?php echo e($user->comments_bugs_issues); ?></td>
            </tr>
            <tr>
                <th class="span-data">Total Client Review <span><?php echo e($user->ClientTotalReview); ?></span></th>
                <td></td>
            </tr>

            <!-- Created At & Updated At -->
            
        </table>
    </div>
</div><?php /**PATH /var/www/vhosts/relaxed-tereshkova.74-208-156-247.plesk.page/modest-gagarin.74-208-156-247.plesk.page/resources/views/reports/clientReport.blade.php ENDPATH**/ ?>