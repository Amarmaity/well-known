<?php $__env->startSection('title', 'Admin Employee Review'); ?>
<?php $__env->startSection('breadcrumb', 'Admin Review List'); ?>
<?php $__env->startSection('page-title', 'Admin-Review-Section'); ?>

<?php $__env->startSection('content'); ?>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Employee Review Table</title>

        <!-- Include CSS for DataTables -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">

        <!-- Include jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Include DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    </head>

    <style>
        table {
            width: 100%;
            max-width: 1606px;
            /* Set the maximum width */
            border-collapse: collapse;
            margin: 0 auto;
            /* This will center the table horizontally */
        }

        table,
        th,
        td {
            /* border: 1px solid black; */
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        .dataTables_filter {
            display: none;
        }
    </style>

    </head>

    <body>
        <div class="client clients-block">
            <h1 class="client__heading">Employee Review Table</h1>
            <div class="client___item">
                <input type="search" id="employee_search" name="search" class="form-control client__search"
                    placeholder="Search" aria-label="Search">
                <button class="client__btn" type="submit">
                    <img src="<?php echo e(asset('images/search.png')); ?>" alt="Search">
                </button>
            </div>
        </div>
        <div class="container table-container">
            <div class="table-wrapper">
                <table class="table table-bordered table-hover main-table" id="employeeReviewTable">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Employee Id</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $superAddUser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($user->fname); ?> <?php echo e($user->lname); ?></td>
                                <td><?php echo e($user->employee_id); ?></td>
                                <td><?php echo e($user->email); ?></td>
                                <td>
                                    
                                    <?php
                                        $currentMonth = date('m');
                                        $currentYear = date('Y');

                                        // Indian FY logic (April start)
                                        if ($currentMonth < 4) {
                                            $currentFYStart = $currentYear - 1;
                                        } else {
                                            $currentFYStart = $currentYear;
                                        }

                                        $years = [
                                            $currentFYStart - 1, // Previous FY
                                            $currentFYStart, // Current FY
                                            $currentFYStart + 1, // Next FY
                                            $currentFYStart + 2, // Next +1 FY
                                        ];
                                    ?>

                                    <select id="financial_year" class="form-control financial-year input-block" required>
                                        <option value="">Financial Year</option>

                                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $end = $year + 1;
                                                $fy = $year . '-' . $end;
                                            ?>

                                            <option value="<?php echo e($fy); ?>"
                                                <?php echo e($year == $currentFYStart ? 'selected' : ''); ?>>
                                                <?php echo e($fy); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </select>
                                    <div class="btn-block">
                                        <?php
                                            $sessionUserType = session()->get('user_type');
                                            $sessionEmployeeId = session()->get('employee_id');

                                            if (
                                                !(
                                                    $sessionUserType === 'admin' &&
                                                    $sessionEmployeeId == $user->employee_id
                                                )
                                            ) {
                                                echo '<a href="' .
                                                    route('user-admin-details', $user->employee_id) .
                                                    '"
                                    class="btn btn-primary view-admin-details">View Details</a>';
                                            }
                                        ?>


                                        <a href="<?php echo e(route('user-report-view-evaluation', $user->employee_id)); ?>"
                                            class="btn btn-primary view-evaluation">View
                                            Evaluation</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

    </body>

    <!-- Include CSS for DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>



    <script>
        $(document).ready(function() {
            var table = $('#employeeReviewTable').DataTable({
                "paging": false,
                "searching": true, // keep this true to allow external filtering
                "ordering": false,
                "info": false
            });

            // Bind the custom search input
            $('#employee_search').on('keyup', function() {
                table.search(this.value).draw();
            });
        });


        $(document).ready(function() {
            $('#employeeReviewTable').DataTable();

            $('.view-admin-details').click(function(e) {
                e.preventDefault();

                let $row = $(this).closest('tr');
                let financialYear = $row.find('.financial-year').val();
                let baseUrl = $(this).attr('href');

                if (!financialYear) {
                    alert('Please select a financial year!');
                    return;
                }

                $.ajax({
                    url: baseUrl + '?financial_year=' + financialYear,
                    type: 'GET',
                    success: function(response) {
                        if (response.message) {
                            alert(response.message);
                        } else {
                            window.location.href = baseUrl + '?financial_year=' + financialYear;
                        }
                    },
                    error: function() {
                        alert('Something went wrong. Please try again.');
                    }
                });
            });
        });

        $(document).ready(function() {
            $('.view-evaluation').click(function(e) {
                e.preventDefault();

                const $row = $(this).closest('tr');
                const financialYear = $row.find('.financial-year').val();
                const baseUrl = $(this).attr('href');

                if (!financialYear) {
                    alert('Please select a financial year!');
                    return;
                }

                $.ajax({
                    url: baseUrl + '?financial_year=' + financialYear,
                    method: 'GET',
                    success: function(response) {
                        if (response.message) {
                            alert(response.message); // You can use SweetAlert here if preferred
                        } else {
                            window.location.href = baseUrl + '?financial_year=' + financialYear;
                        }
                    },
                    error: function() {
                        alert('Something went wrong. Please try again.');
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/well-known/resources/views/reports/adminReportView.blade.php ENDPATH**/ ?>