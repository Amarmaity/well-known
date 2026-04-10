<?php $__env->startSection('title', 'HR Review Details'); ?>

<?php $__env->startSection('breadcrumb', "Employee {$employee_id} / View Evaluation"); ?>

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



    <div class="text-right mb-3">
        <a href="<?php echo e(url()->previous()); ?>" class="btn btn-secondary">Back</a>
    </div>

    <h2 class="heading">Employee Evaluation Details: <?php echo e($employee_id); ?></h2>

    <!-- Employee Evaluation History Table -->
    <div class="table-container ">
        <div class="table-wrapper">
            <table id="employeeEvaluationTable" class="table table-bordered table-hover main-table">
                <tbody>
                    <?php $__currentLoopData = $eval; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evaluation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>Designation:</td>
                            <td><?php echo e($evaluation->designation); ?></td>
                        </tr>
                        <tr>
                            <td>Salary Grade/Band:</td>
                            <td><?php echo e($evaluation->salary_grade); ?></td>
                        </tr>
                        <tr>
                            <td>Name of Employee:</td>
                            <td><?php echo e($evaluation->employee_name); ?>

                        </tr>
                        <tr>
                            <td>Employee Id:</td>
                            <td><?php echo e($evaluation->emp_id); ?>

                        </tr>

                        <tr>
                            <td>Division:</td>
                            <td><?php echo e($evaluation->division); ?>

                        </tr>
                        <tr>
                            <td>Manager Name:</td>
                            <td><?php echo e($evaluation->manager_name); ?>

                        </tr>
                        <tr>
                            <td>Joining Date:</td>
                            <td><?php echo e($evaluation->joining_date); ?>

                        </tr>
                        <tr>
                            <td>Evaluation Purpose:</td>
                            <td><?php echo e($evaluation->evaluation_purpose); ?>

                        </tr>
                        <tr>
                            <td>Review Period:</td>
                            <td><?php echo e($evaluation->review_period); ?>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <div class="mt-5 second-table span-tage">
            <div class="table-wrapper">
                <table id="employeeEvaluationTable" class="table table-bordered table-hover main-table">
                    <thead>
                        <tr>
                            <th class="span-data">Field <span>Rating</span></th>
                            <th> Comments</th>
                        </tr>
                    </thead>

                    <?php $__currentLoopData = $eval; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evaluation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="span-data">1. Accuracy, neatness and timeliness of work
                                <span>(<?php echo e($evaluation->accuracy_neatness); ?> /5)</span></td>
                            <td><?php echo e($evaluation->comments_accuracy); ?></td>
                        </tr>
                        <tr>
                            <td class="span-data">2. Adherence to duties and procedures in Job Description and Work Instructions
                                <span>(<?php echo e($evaluation->adherence); ?>/5)</span></td>
                            <td><?php echo e($evaluation->comments_adherence); ?></td>
                        </tr>
                        <tr>
                            <td class="span-data">3. Synchronization with organizations/functional goals
                                <span>(<?php echo e($evaluation->synchronization); ?> /5)</span></td>
                            <td><?php echo e($evaluation->comments_synchronization); ?></td>
                        </tr>
                        <tr>
                            <td>Quality of Work Total Rating</td>
                            <td><?php echo e($evaluation->qualityworktotalrating); ?></td>
                        </tr>
                        <tr>
                            <td class="span-data">1. Punctuality to workplace <span>(<?php echo e($evaluation->punctuality); ?> /5)</span>
                            </td>
                            <td><?php echo e($evaluation->comments_punctuality); ?></td>
                        </tr>
                        <tr>
                            <td class="span-data">2. Attendance <span>(<?php echo e($evaluation->attendance); ?> /5)</span></td>
                            <td><?php echo e($evaluation->comments_attendance); ?></td>
                        </tr>
                        <tr>
                            <td class="span-data">3. Does the employee stay busy, look for things to do, take initiatives at
                                workplace <span>(<?php echo e($evaluation->initiatives_at_workplace); ?> /5)</span></td>
                            <td><?php echo e($evaluation->comments_initiatives); ?></td>
                        </tr>
                        <tr>
                            <td class="span-data">4. Submits reports on time and meets deadlines
                                <span>(<?php echo e($evaluation->submits_reports); ?> /5)</span></td>
                            <td><?php echo e($evaluation->comments_submits_reports); ?></td>
                        </tr>
                        <tr>
                            <td>Work Habits Total Rating</td>
                            <td><?php echo e($evaluation->work_habits_rating); ?></td>
                        </tr>
                        <tr>
                            <td class="span-data">1. Skill and ability to perform job satisfactorily
                                <span>(<?php echo e($evaluation->skill_ability); ?>/5)</span></td>
                            <td><?php echo e($evaluation->comments_skill_ability); ?></td>
                        </tr>
                        <tr>
                            <td class="span-data">2. Shown interest in learning and improving
                                <span>(<?php echo e($evaluation->learning_improving); ?>/5)</span></td>
                            <td> <?php echo e($evaluation->comments_learning_improving); ?></td>
                        </tr>
                        <tr>
                            <td class="span-data">3. Problem solving ability
                                <span>(<?php echo e($evaluation->problem_solving_ability); ?>/5)</span></td>
                            <td><?php echo e($evaluation->comments_problem_solving); ?></td>
                        </tr>
                        <tr>
                            <td>Job Knowledge Total Rating</td>
                            <td><?php echo e($evaluation->jk_total_rating); ?></td>
                        </tr>
                        
                        <tr>
                            <td>Evaluator's Name</td>
                            <td><?php echo e($evaluation->evalutors_name); ?></td>
                        </tr>
                        <tr>
                            <td>Evaluator's Signature</td>
                            <td><img src="<?php echo e(asset('storage/' . $evaluation->evaluator_signatur)); ?>" alt="Evaluator's Signature"
                                    style="width: 100px; height: 120px; object-fit: cover;"></td>
                        </tr>
                        <tr>
                            <td>Evaluation Date</td>
                            <td><?php echo e($evaluation->evaluator_signatur_date); ?></td>
                        </tr>
                        <tr>
                            <td class="span-data">1. Responds and contributes to team efforts
                                <span>(<?php echo e($evaluation->respond_contributes); ?>/5)</span></td>
                            <td><?php echo e($evaluation->comments_respond_contributes); ?>

                        </tr>
                        <tr>
                            <td class="span-data">2. Responds positively to suggestions, instructions, and criticism
                                <span>(<?php echo e($evaluation->responds_positively); ?>/5)</span></td>
                            <td><?php echo e($evaluation->comments_responds_positively); ?></td>
                        </tr>
                        <tr>
                            <td class="span-data">3. Keeps supervisor informed of all details <span>(<?php echo e($evaluation->supervisor); ?>

                                    /5)</span></td>
                            <td><?php echo e($evaluation->comments_supervisor); ?></td>
                        </tr>
                        <tr>
                            <td class="span-data">4. Adapts well to changing circumstances
                                <span>(<?php echo e($evaluation->adapts_changing); ?>/5)</span></td>
                            <td><?php echo e($evaluation->comments_adapts_changing); ?></td>
                        </tr>
                        <tr>
                            <td class="span-data">5. Seeks feedback to improve <span>(<?php echo e($evaluation->seeks_feedback); ?>/5)</span>
                            </td>
                            <td><?php echo e($evaluation->comments_seeks_feedback); ?></td>
                        </tr>
                        <tr>
                            <td>Interpersonal Relations Total Rating</td>
                            <td><?php echo e($evaluation->ir_total_rating); ?></td>
                        </tr>
                        <tr>
                            <td class="span-data">1. Aspirant to climb up the ladder, accepts challenges, new responsibilities,
                                and roles <span>(<?php echo e($evaluation->challenges); ?>/10)</span></td>
                            <td><?php echo e($evaluation->comments_challenges); ?></td>
                        </tr>
                        <tr>
                            <td class="span-data">2. Innovative thinking - contribution to organizations, functions, and
                                personal growth <span>(<?php echo e($evaluation->personal_growth); ?>/10)</span></td>
                            <td><?php echo e($evaluation->comments_personal_growth); ?></td>
                        </tr>
                        <tr>
                            <td>3. Work motivation <span>(<?php echo e($evaluation->work_motivation); ?> /5)</span></td>
                            <td><?php echo e($evaluation->comments_work_motivation); ?>


                        </tr>
                        <tr>
                            <td>Leadership Skill Total Rating</td>
                            <td><?php echo e($evaluation->leadership_rating); ?></td>
                        </tr>
                        <tr>
                            <td class="span-data">1. Employee performance and learning is unsatisfactory and is failing to
                                improve at a
                                satisfactory rate <span>(<?php echo e($evaluation->progress_unsatisfactory); ?>)</span>
                            </td>
                            <td><?php echo e($evaluation->comments_unsatisfactory); ?></td>
                        </tr>
                        <tr>
                            <td class="span-data">2. Employee performance and learning is acceptable and is improving at a
                                satisfactory rate <span>(<?php echo e($evaluation->progress_acceptable); ?>)</span></td>
                            <td><?php echo e($evaluation->comments_acceptable); ?></td>
                        </tr>
                        <tr>
                            <td class="span-data">3. Employee has successfully demonstrated outstanding overall performance
                                <span>(<?php echo e($evaluation->progress_outstanding); ?>)</span></td>
                            <td> <?php echo e($evaluation->comments_outstanding); ?></td>
                        </tr>
                        <tr>
                            <td>Total Scoring System</td>
                            <td><?php echo e($evaluation->total_scoring_system); ?></td>
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
                $('#employeeEvaluationTable').DataTable({
                    "paging": true,
                    "searching": true,
                    "ordering": false,
                    "info": true,
                    "lengthMenu": [5, 10, 25, 50],
                    "columnDefs": [
                        { "targets": [0, 1], "searchable": true }
                    ]
                });
            });
        </script>
    <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/relaxed-tereshkova.74-208-156-247.plesk.page/modest-gagarin.74-208-156-247.plesk.page/resources/views/reports/userEvaluationDetails.blade.php ENDPATH**/ ?>