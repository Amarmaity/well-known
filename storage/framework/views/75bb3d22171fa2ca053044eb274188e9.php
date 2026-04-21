<?php $__env->startSection('title', 'Client Review Details'); ?>

<?php $__env->startSection('breadcrumb', "Employee {$employee_id} /Client Review"); ?>

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

        .set-position tr {
            display: flex;
            gap: 100px;
        }

        .set-position td {
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

    <div class="container">

        <!-- Back Button aligned to the right -->
        <div class="text-right mb-3">
            <a href="<?php echo e(url()->previous()); ?>" class="btn btn-secondary">Back</a>
        </div>

        <h2>Client Review Details</h2>
        <!-- Client Review History Table -->
        <div class="table-container span-tage">
            <div class="table-wrapper">
                <table id="clientReviewHistoryTable" class="display table table-striped  table-bordered set-position">
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
                                <td>1. Understanding Requirements</td>
                                <td>(<?php echo e($review->understand_requirements); ?> /5)</td>
                                <td><?php echo e($review->comment_understand_requirements); ?></td>
                            </tr>
                            <tr>
                                <td>2. Business Needs</td>
                                <td>(<?php echo e($review->business_needs); ?>/5)</td>
                                <td><?php echo e($review->comments_business_needs); ?></td>
                            </tr>
                            <tr>
                                <td>3. Detailed Project Scope</td>
                                <td>(<?php echo e($review->detailed_project_scope); ?>/5)</td>
                                <td><?php echo e($review->comments_detailed_project_scope); ?></td>
                            </tr>
                            <tr>
                                <td>4. Responsive to Project Needs</td>
                                <td>(<?php echo e($review->responsive_reach_project); ?>/5)</td>
                                <td><?php echo e($review->comments_responsive_reach_project); ?></td>
                            </tr>
                            <tr>
                                <td>5. Comfortable Discussing Issues</td>
                                <td>(<?php echo e($review->comfortable_discussing); ?>/5)</td>
                                <td><?php echo e($review->comments_comfortable_discussing); ?>

                                </td>
                            </tr>
                            <tr>
                                <td>6. Regular Updates</td>
                                <td>(<?php echo e($review->regular_updates); ?> /5)</td>
                                <td><?php echo e($review->comments_regular_updates); ?></td>
                            </tr>
                            <tr>
                                <td>7. Concerns Addressed</td>
                                <td>(<?php echo e($review->concerns_addressed); ?>/5)</td>
                                <td><?php echo e($review->comments_concerns_addressed); ?></td>
                            </tr>
                            <tr>
                                <td>8. Technical Expertise</td>
                                <td>(<?php echo e($review->technical_expertise); ?>/5)</td>
                                <td><?php echo e($review->comments_technical_expertise); ?></td>
                            </tr>
                            <tr>
                                <td>9. Best Practices</td>
                                <td>(<?php echo e($review->best_practices); ?>/5)</td>
                                <td><?php echo e($review->comments_best_practices); ?></td>
                            </tr>
                            <tr>
                                <td>10. Innovation Suggestions</td>
                                <td>(<?php echo e($review->suggest_innovative); ?>/5)</td>
                                <td><?php echo e($review->comments_suggest_innovative); ?></td>
                            </tr>
                            <tr>
                                <td>11. Quality of Code</td>
                                <td>(<?php echo e($review->quality_code); ?> /5)</td>
                                <td><?php echo e($review->comments_quality_code); ?></td>
                            </tr>
                            <tr>
                                <td>12. Handling Issues</td>
                                <td>(<?php echo e($review->encounter_issues); ?> /5)</td>
                                <td><?php echo e($review->comments_encounter_issues); ?></td>
                            </tr>
                            <tr>
                                <td>13. Scalability of Code</td>
                                <td>(<?php echo e($review->code_scalable); ?> /5)</td>
                                <td><?php echo e($review->comments_code_scalable); ?></td>
                            </tr>
                            <tr>
                                <td>14. Performance of Solutions</td>
                                <td>(<?php echo e($review->solution_perform); ?>/5)</td>
                                <td><?php echo e($review->comments_solution_perform); ?></td>
                            </tr>
                            <tr>
                                <td>15. Project Delivery</td>
                                <td>(<?php echo e($review->project_delivered); ?>/5)</td>
                                <td><?php echo e($review->comments_project_delivered); ?></td>
                            </tr>
                            <tr>
                                <td>16. Communication & Handling</td>
                                <td>(<?php echo e($review->communicated_handled); ?>/5)</td>
                                <td><?php echo e($review->comments_communicated_handled); ?></td>
                            </tr>
                            <tr>
                                <td>17. Development Process</td>
                                <td>(<?php echo e($review->development_process); ?>/5)</td>
                                <td><?php echo e($review->comments_development_process); ?></td>
                            </tr>
                            <tr>
                                <td>18. Handling Unexpected Challenges</td>
                                <td>(<?php echo e($review->unexpected_challenges); ?>/5)</td>
                                <td><?php echo e($review->comments_unexpected_challenges); ?></td>
                            </tr>
                            <tr>
                                <td>19. Effective Workarounds</td>
                                <td>(<?php echo e($review->effective_workarounds); ?>/5)</td>
                                <td><?php echo e($review->comments_effective_workarounds); ?></td>
                            </tr>
                            <tr>
                                <td>20. Bug & Issue Resolution</td>
                                <td>(<?php echo e($review->bugs_issues); ?>/5)</td>
                                <td><?php echo e($review->comments_bugs_issues); ?></td>
                            </tr>
                            <tr>
                                <td>Total Client Review Score</td>
                                <td><?php echo e($review->ClientTotalReview); ?></td>
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
        $(document).ready(function () {
            $('#clientReviewHistoryTable').DataTable({
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/well-known/resources/views/reports/userDetailsClientView.blade.php ENDPATH**/ ?>