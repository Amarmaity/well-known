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

        @media (max-width: 1124px) {
            .set-position tr {
                gap: 40px;
            }
        }
</style>
<div class="container table-container span-tage">
    <div class="table-responsive table-wrapper">
        <table class="table table-bordered table-hover main-table">
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Rating</th>
                    <th>Comments</th>
                </tr>
            </thead>

            <tr>
                <td>1. Understand Requirements</td>
                <td>(<?php echo e($user->understand_requirements); ?>/5)</td>
                <td><?php echo e($user->comment_understand_requirements); ?></td>
            </tr>
            <tr>
                <td>2. Business Needs</td>
                <td>(<?php echo e($user->business_needs); ?>/5)</td>
                <td><?php echo e($user->comments_business_needs); ?></td>
            </tr>
            <tr>
                <td>3. Detailed Project Scope</td>
                <td>(<?php echo e($user->detailed_project_scope); ?>/5)</td>
                <td><?php echo e($user->comments_detailed_project_scope); ?></td>
            </tr>
            <tr>
                <td>4. Responsive to Project Reach</td>
                <td>(<?php echo e($user->responsive_reach_project); ?>/5)</td>
                <td><?php echo e($user->comments_responsive_reach_project); ?></td>
            </tr>
            <tr>
                <td>5. Comfortable Discussing Requirements</td>
                <td>(<?php echo e($user->comfortable_discussing); ?>/5)</td>
                <td><?php echo e($user->comments_comfortable_discussing); ?></td>
            </tr>
            <tr>
                <td>6. Regular Updates</td>
                <td>(<?php echo e($user->regular_updates); ?>/5)</td>
                <td><?php echo e($user->comments_regular_updates); ?></td>
            </tr>
            <tr>
                <td>7. Concerns Addressed</td>
                <td>(<?php echo e($user->concerns_addressed); ?> /5)</td>
                <td><?php echo e($user->comments_concerns_addressed); ?></td>
            </tr>
            <tr>
                <td>8. Technical Expertise</td>
                <td>(<?php echo e($user->technical_expertise); ?> /5)</td>
                <td><?php echo e($user->comments_technical_expertise); ?></td>
            </tr>
            <tr>
                <td>9. Best Practices Followed</td>
                <td >(<?php echo e($user->best_practices); ?>/5)</td>
                <td><?php echo e($user->comments_best_practices); ?></td>
            </tr>
            <tr>
                <td>10.Suggests Innovative Solutions</td>
                <td>(<?php echo e($user->suggest_innovative); ?>/5)</td>
                <td><?php echo e($user->comments_suggest_innovative); ?></td>
            </tr>
            <tr>
                <td>11. Quality of Code</td>
                <td>(<?php echo e($user->quality_code); ?>/5)</td>
                <td><?php echo e($user->comments_quality_code); ?></td>
            </tr>
            <tr>
                <td>12. Encountered Issues</td>
                <td>(<?php echo e($user->encounter_issues); ?>/5)</td>
                <td><?php echo e($user->comments_encounter_issues); ?></td>
            </tr>
            <tr>
                <td>13. Code Scalability</td>
                <td>(<?php echo e($user->code_scalable); ?>/5)</td>
                <td><?php echo e($user->comments_code_scalable); ?></td>
            </tr>
            <tr>
                <td>14. Solution Performance</td>
                <td>(<?php echo e($user->solution_perform); ?>/5)</td>
                <td><?php echo e($user->comments_solution_perform); ?></td>
            </tr>
            <tr>
                <td>15. Project Delivered</td>
                <td>(<?php echo e($user->project_delivered); ?>/5)</td>
                <td><?php echo e($user->comments_project_delivered); ?></td>
            </tr>
            <tr>
                <td>16. Communicated & Handled</td>
                <td>(<?php echo e($user->communicated_handled); ?>/5)</td>
                <td><?php echo e($user->comments_communicated_handled); ?></td>
            </tr>
            <tr>
                <td>17. Development Process</td>
                <td>(<?php echo e($user->development_process); ?>/5)</td>
                <td> <?php echo e($user->comments_development_process); ?></td>
            </tr>
            <tr>
                <td>18. Unexpected Challenges</td>
                <td>(<?php echo e($user->unexpected_challenges); ?>/5)</td>
                <td><?php echo e($user->comments_unexpected_challenges); ?></td>
            </tr>
            <tr>
                <td>19. Effective Workarounds</td>
                <td>(<?php echo e($user->effective_workarounds); ?>/5)</td>
                <td><?php echo e($user->comments_effective_workarounds); ?></td>
            </tr>
            <tr>
                <td>20. Bugs & Issues</td>
                <td>(<?php echo e($user->bugs_issues); ?>/5)</td>
                <td><?php echo e($user->comments_bugs_issues); ?></td>
            </tr>
            <tr>
                <td>Total Client Review</td>
                <td><?php echo e($user->ClientTotalReview); ?></td>
            </tr>
        </table>
    </div>
</div><?php /**PATH /opt/lampp/htdocs/well-known/resources/views/reports/clientReport.blade.php ENDPATH**/ ?>