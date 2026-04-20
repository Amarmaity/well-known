<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Side by Side Form</title>

    <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding-top: 30px;
        }

        .table-responsive {
            margin-bottom: 30px;
        }

        .table th {
            background-color: #007bff;
            color: white;
        }

        .table td {
            background-color: white;
        }

        .form-control {
            border-radius: 5px;
        }

        .form-group label {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .form-section {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-section h3 {
            margin-bottom: 20px;
        }

        .form-section .row {
            margin-bottom: 15px;
        }

        .evaluation__form {
            margin-top: 30px;
        }

        .evaluation__form textarea {
            resize: none;
        }

        .btn-clear {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-clear:hover {
            background-color: #007bff;
            border-color: #007bff;
        }

        .span-tage .span-data {
            display: flex;
            justify-content: space-between;
            padding-right: 105px !important;
        }

        .span-tage tr {
            /* border-bottom: 1px solid #000; */
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <?php if(Session::get('user_type') === 'Super User'): ?>
                    <tr>
                        <th>Employee Name</th>
                        <td><?php echo e($user->employee_name); ?></td>
                    </tr>
                    <tr>
                        <th>Employee ID</th>
                        <td><?php echo e($user->emp_id); ?></td>
                    </tr>
                    <tr>
                        <th>Designation</th>
                        <td><?php echo e($user->designation); ?></td>
                    </tr>
                    <tr>
                        <th>Salary Grade</th>
                        <td><?php echo e($user->salary_grade); ?></td>
                    </tr>
                    <tr>
                        <th>Evaluation Purpose</th>
                        <td><?php echo e($user->evaluation_purpose); ?></td>
                    </tr>
                    <tr>
                        <th>Division</th>
                        <td><?php echo e($user->division); ?></td>
                    </tr>
                    <tr>
                        <th>Manager Name</th>
                        <td><?php echo e($user->manager_name); ?></td>
                    </tr>
                    <tr>
                        <th>Joining Date</th>
                        <td><?php echo e(\Carbon\Carbon::parse($user->joining_date)->format('d M, Y')); ?></td>
                    </tr>
                    <tr>
                        <th>Review Period</th>
                        <td><?php echo e($user->review_period); ?></td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>

        <!-- Ratings and Comments -->
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
                    <td>1. Accuracy, neatness and timeliness of work</td>
                    <td>(<?php echo e($user->accuracy_neatness); ?>/5)</td>
                    <td><?php echo e($user->comments_accuracy); ?></td>
                </tr>
                <tr>
                    <td>2. Adherence to duties and procedures in Job Description and Work Instructions </td>
                    <td>(<?php echo e($user->adherence); ?>/5)</td>
                    <td><?php echo e($user->comments_adherence); ?></td>
                </tr>
                <tr>
                    <td>3. Synchronization with organizations/functional goals </td>
                    <td>(<?php echo e($user->synchronization); ?>/5)</td>
                    <td><?php echo e($user->comments_synchronization); ?></td>
                </tr>
                <tr>
                    <td>Quality of Work Total Rating </td>
                    <td><?php echo e($user->qualityworktotalrating); ?></td>
                </tr>
                <tr>
                    <td>1. Punctuality to workplace </td>
                    <td>(<?php echo e($user->punctuality); ?>/5)</td>
                    <td><?php echo e($user->comments_punctuality); ?></td>
                </tr>
                <tr>
                    <td>2. Attendance </td>
                    <td>(<?php echo e($user->attendance); ?>/5)</td>
                    <td><?php echo e($user->comments_attendance); ?></td>
                </tr>
                <tr>
                    <td>3. Does the employee stay busy, look for things to do, take initiatives at workplace </td>
                    <td>(<?php echo e($user->initiatives_at_workplace); ?>/5)</td>
                    <td><?php echo e($user->comments_initiatives); ?></td>
                </tr>
                <tr>
                    <td>4. Submits reports on time and meets deadlines </td>
                    <td>(<?php echo e($user->submits_reports); ?>/5)</td>
                    <td><?php echo e($user->comments_submits_reports); ?></td>
                </tr>
                <tr>
                    <td>Work Habits Total Rating </td>
                    <td><?php echo e($user->work_habits_rating); ?></td>
                </tr>
                <tr>
                    <td>1. Skill and ability to perform job satisfactorily </td>
                    <td>(<?php echo e($user->skill_ability); ?>/5)</td>
                    <td><?php echo e($user->comments_skill_ability); ?></td>
                </tr>
                <tr>
                    <td>2. Shown interest in learning and improving </td>
                    <td>(<?php echo e($user->learning_improving); ?>/5)</td>
                    <td><?php echo e($user->comments_learning_improving); ?></td>
                </tr>
                <tr>
                    <td>3. Problem solving ability</td>
                    <td>(<?php echo e($user->problem_solving_ability); ?>/5)</td>
                    <td><?php echo e($user->comments_problem_solving); ?></td>
                </tr>
                <tr>
                    <td>Job Knowledge Total Rating </td>
                    <td><?php echo e($user->jk_total_rating); ?></td>
                </tr>
                <tr>
                    <td>Evaluator's Name</td>
                    <td><?php echo e($user->evalutors_name); ?></td>
                </tr>
                <tr>
                    <td>Evaluator's Signature</td>
                    <td>
                        <img src="<?php echo e(asset('storage/' . $user->evaluator_signatur)); ?>" alt="Evaluator's Signature"
                            style="width: 100px; height: 120px; object-fit: cover;">
                    </td>
                </tr>

                <tr>
                    <td>Evaluation Date</td>
                    <td><?php echo e($user->evaluator_signatur_date); ?></td>
                </tr>
                <tr>
                    <td>1. Responds and contributes to team efforts </td>
                    <td>(<?php echo e($user->respond_contributes); ?>/5)</td>
                    <td><?php echo e($user->comments_respond_contributes); ?></td>
                </tr>
                <tr>
                    <td>2. Responds positively to suggestions, instructions, and criticism </td>
                    <td>(<?php echo e($user->responds_positively); ?>/5)</td>
                    <td><?php echo e($user->comments_responds_positively); ?></td>
                </tr>
                <tr>
                    <td>3. Keeps supervisor informed of all details </td>
                    <td>(<?php echo e($user->supervisor); ?>/5)</td>
                    <td><?php echo e($user->comments_supervisor); ?></td>
                </tr>
                <tr>
                    <td>4. Adapts well to changing circumstances </td>
                    <td>(<?php echo e($user->adapts_changing); ?>/5)</td>
                    <td><?php echo e($user->comments_adapts_changing); ?></td>
                </tr>
                <tr>
                    <td>5. Seeks feedback to improve </td>
                    <td>(<?php echo e($user->seeks_feedback); ?>/5)</td>
                    <td><?php echo e($user->comments_seeks_feedback); ?></td>
                </tr>
                <tr>
                    <td>Interpersonal Relations Total Rating </td>
                    <td><?php echo e($user->ir_total_rating); ?></td>
                </tr>
                <tr>
                    <td>1. Aspirant to climb up the ladder, accepts challenges, new responsibilities, and roles</td>
                    <td>(<?php echo e($user->challenges); ?>/10)</td>
                    <td><?php echo e($user->comments_challenges); ?></td>
                </tr>
                <tr>
                    <td>2. Innovative thinking - contribution to organizations, functions, and personal growth </td>
                    <td>(<?php echo e($user->personal_growth); ?>/10)</td>
                    <td><?php echo e($user->comments_personal_growth); ?></td>
                </tr>
                <tr>
                    <td>3. Work motivation </td>
                    <td>(<?php echo e($user->work_motivation); ?>/5)</td>
                    <td><br> <?php echo e($user->comments_work_motivation); ?></td>
                </tr>
                <tr>
                    <td>Leadership Skill Total Rating </td>
                    <td><?php echo e($user->leadership_rating); ?></td>
                </tr>
                <tr>
                    <td>1. Employee performance and learning is unsatisfactory and is failing to improve at a
                        satisfactory
                        rate</td>
                    <td><span><?php echo e($user->progress_unsatisfactory); ?></span></td>
                    <td><?php echo e($user->comments_unsatisfactory); ?></td>
                </tr>
                <tr>
                    <td>2. Employee performance and learning is acceptable and is improving at a satisfactory rate
                    </td>
                    <td> <span><?php echo e($user->progress_acceptable); ?></span></td>
                    <td> <?php echo e($user->comments_acceptable); ?></td>
                </tr>
                <tr>
                    <td>3. Employee has successfully demonstrated outstanding overall performance </td>
                    <td><span><?php echo e($user->progress_outstanding); ?></span></td>
                    <td><?php echo e($user->comments_outstanding); ?></td>
                </tr>
                <tr>
                    <td>Total score in evaluation: </td>
                    <td><?php echo e($user->total_scoring_system); ?></td>
                </tr>
                
            </table>
        </div>
        
</body>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // document.getElementById('evaluationSubmit').addEventListener('submit', function (e) {
    //     e.preventDefault();


    //     var formData = new FormData(this);


    //     var emp_id = '<?php echo e($user->emp_id ?? ''); ?>';


    //     if (!emp_id) {
    //         alert('Employee ID is missing. Cannot submit the form.');
    //         return;
    //     }


    //     fetch(`/evaluation-report-submit/${emp_id}`, {
    //         method: 'POST',
    //         body: formData,
    //         headers: {
    //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    //         }
    //     })
    //         .then(response => response.json())
    //         .then(data => {
    //             if (data.success) {
    //                 alert('Director evaluation submitted successfully!');
    //                 document.getElementById('evaluationSubmit').reset(); // Reset the form after submission
    //                 location.reload();
    //             } else {
    //                 alert(data.message || 'There was an error submitting the form.');
    //             }
    //         })
    //         .catch(error => {
    //             console.error('Error:', error);
    //             alert('There was an error submitting the form. Please try again.');
    //         });
    // });


    const form = document.getElementById('evaluationSubmit');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var emp_id = '<?php echo e($user->emp_id ?? ''); ?>';

            if (!emp_id) {
                alert('Employee ID is missing.');
                return;
            }

            fetch(`/evaluation-report-submit/${emp_id}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('Submitted!');
                        form.reset();
                        location.reload();
                    } else {
                        alert(data.message || 'Error');
                    }
                })
                .catch(err => {
                    console.error(err);
                });
        });
    }
</script>
<?php /**PATH /opt/lampp/htdocs/well-known/resources/views/reports/evaluationReport.blade.php ENDPATH**/ ?>