<?php $__env->startSection('title', 'Financial Dashboard'); ?>
<?php $__env->startSection('breadcrumb', 'Financial'); ?>
<?php $__env->startSection('page-title', 'Financial-Section'); ?>
<?php $__env->startSection('content'); ?>

    <head>
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>


    <div class="client">
        <h1 class="client__heading">Employee Financial Year(%)</h1>

        
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

        <select id="financialYear" class="form-select client__select" name="financial_year" required>
            <option value="">Financial Year</option>

            <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $end = $year + 1;
                    $fy = $year . '-' . $end;
                ?>

                <option value="<?php echo e($fy); ?>" <?php echo e($year == $currentFYStart ? 'selected' : ''); ?>>
                    <?php echo e($fy); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </select>


        <div class="client___item">
            <input type="search" id="employee_search" name="search" class="form-control client__search"
                placeholder="Search" aria-label="Search">
            <button class="client__btn" type="submit">
                <img src="<?php echo e(asset('images/search.png')); ?>" alt="Search">
            </button>
        </div>
        <input type="hidden" name="emp_id" id="selectedEmpId">



    </div>
    <div class="container table-container financial-page">
        <!-- Appraisal Table -->
        <form action="<?php echo e(route('financial-data-store')); ?>" method="POST" id="financial-data" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="table-responsive table-wrapper">
                <table class="table table-bordered table-hover main-table table-view financial-table"
                    class="financial view-table table-view">
                    <thead class="table">
                        <tr>
                            <th>Employee Name</th>
                            <th>Employee ID</th>
                            <th>Evaluation Score (%)</th>
                            <th id="hr-review-header" style="display: none;">HR Review (%)</th>
                            <th id="admin-review-header" style="display: none;">Admin Review (%)</th>
                            <th id="manager-review-header" style="display: none;">Manager Review (%)</th>
                            <th id="client-review-header" style="display: none;">Client Review (%)</th>
                            <th>Appraisal Score (%)</th>
                            <th>Current Salary (₹)</th>
                            <th>Percentage (%)</th>
                            <th>Updated Salary (₹)</th>
                            <th>Final Salary (₹)</th>
                            <th>Appraisal Date</th>
                            <th>Financial Year</th>
                        </tr>
                    </thead>
                    <tbody id="appraisal-body">
                        <tr>
                            <td colspan="12" class="text-muted">Enter Employee ID or Name to view data.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary primary-btn" id="save-financial-data">Save</button>
            </div>
        </form>
    </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min"></script>

    <script>
        $(document).ready(function() {
            let clientExist = false;

            function fetchEmployeeData() {
                const employeeSearch = $('#employee_search').val().trim();
                const financialYear = $('#financialYear').val();

                if (!employeeSearch || !financialYear) {
                    $('#appraisal-body').html(
                        '<tr><td colspan="13" class="text-muted">Enter Employee ID/Name and select Financial Year to view data.</td></tr>'
                    );
                    return;
                }

                $.ajax({
                    url: "<?php echo e(route('financial.data')); ?>",
                    method: "GET",
                    data: {
                        search: employeeSearch,
                        financial_year: financialYear
                    },
                    success: function(response) {
                        let tableRows = '';
                        let userType = response.user_type;

                        // Parse values FIRST
                        const employeeName = response.employee_name || 'N/A';
                        const employeeId = response.employee_id || 'N/A';
                        const evaluationScore = parseFloat(response.evaluationScore) || 0;

                        const hrReview = parseFloat(response.hrReviewData?.[0] || 0);
                        const adminReview = parseFloat(response.adminReviewData?.[0] || 0);
                        const managerReview = parseFloat(response.managerReviewData || 0);
                        const clientReviewValue = parseFloat(response.clientReviewData || 0);

                        const baseSalary = parseFloat(response.salary) || 0;
                        const percentage = parseFloat(response.company_percentage) || 0;

                        // Show conditions (role + data)
                        let showHRReview = userType !== 'hr' && hrReview > 0;
                        let showAdminReview = userType !== 'admin' && adminReview > 0;
                        let showManagerReview = !(userType === 'hr' || userType === 'admin' ||
                            userType === 'manager') && managerReview > 0;
                        let showClientReview = clientReviewValue > 0;

                        // Toggle headers
                        $('#hr-review-header').toggle(showHRReview);
                        $('#admin-review-header').toggle(showAdminReview);
                        $('#manager-review-header').toggle(showManagerReview);
                        $('#client-review-header').toggle(showClientReview);

                        // ✅ Dynamic appraisal calculation
                        let total = 0;
                        let count = 0;

                        total += evaluationScore;
                        count++;

                        if (showHRReview) {
                            total += hrReview;
                            count++;
                        }

                        if (showAdminReview) {
                            total += adminReview;
                            count++;
                        }

                        if (showManagerReview) {
                            total += managerReview;
                            count++;
                        }

                        if (showClientReview) {
                            total += clientReviewValue;
                            count++;
                        }

                        const avgReviewPercentage = count > 0 ? total / count : 0;

                        // Salary calculations
                        const updatedSalary = baseSalary * (percentage / 100);
                        const appraisalBonus = (avgReviewPercentage * updatedSalary) / 100;
                        const finalSalary = baseSalary + appraisalBonus;

                        // Build row
                        tableRows += `<tr>
                                <td class="employeeName">${employeeName}</td>
                                <td class="employeeId">${employeeId}</td>
                                <td class="EvaluationScore">${evaluationScore.toFixed(2)}%</td>

                                ${showHRReview ? `<td class="hrReview">${hrReview.toFixed(2)}%</td>` : ''}
                                ${showAdminReview ? `<td class="adminReview">${adminReview.toFixed(2)}%</td>` : ''}
                                ${showManagerReview ? `<td class="managerReview">${managerReview.toFixed(2)}%</td>` : ''}
                                ${showClientReview ? `<td class="clientReview">${clientReviewValue.toFixed(2)}%</td>` : ''}

                                <td class="avgReview">${avgReviewPercentage.toFixed(2)}%</td>
                                <td class="currentSalary">₹${Math.floor(baseSalary)}</td>
                                <td class="percentage">${percentage.toFixed(2)}%</td>
                                <td class="updated-salary">₹${Math.floor(updatedSalary)}</td>
                                <td class="final-salary">₹${Math.floor(finalSalary)}</td>
                                <td class="appraisal-date">${response.appraisalDate || 'N/A'}</td>
                                <td class="financial-year">${$('#financialYear').val()}</td>
                            </tr>`;

                        $('#appraisal-body').html(tableRows);
                    },

                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON?.message || 'Error fetching data';
                        $('#appraisal-body').html(`<tr><td colspan="13">${errorMsg}</td></tr>`);
                    }
                });
            }

            $('#employee_search').on('input', fetchEmployeeData);
            $('#financialYear').on('change', fetchEmployeeData);

            $('#save-financial-data').click(function(e) {
                e.preventDefault();
                const button = $(this);
                button.prop('disabled', true).text('Saving...');

                const selectedFinancialYear = $('#financialYear').val();
                if (!selectedFinancialYear) {
                    alert("Please select a financial year.");
                    button.prop('disabled', false).text('Save');
                    return;
                }

                const employees = [];
                $('#appraisal-body tr').each(function() {
                    const row = $(this);
                    const employee = {
                        employee_name: row.find(".employeeName").text().trim(),
                        emp_id: row.find(".employeeId").text().trim(),
                        evaluation_score: parseFloat(row.find(".EvaluationScore").text()) || 0,
                        hr_review: parseFloat(row.find(".hrReview").text()) || 0,
                        admin_review: parseFloat(row.find(".adminReview").text()) || 0,
                        manager_review: parseFloat(row.find(".managerReview").text()) || 0,
                        client_review: parseFloat(row.find(".clientReview").text()) || 0,
                        apprisal_score: parseFloat(row.find(".avgReview").text()) || 0,
                        current_salary: parseFloat(row.find(".currentSalary").text().replace(
                            '₹', '').trim()) || 0,
                        percentage_given: parseFloat(row.find(".percentage").text()) || 0,
                        update_salary: parseFloat(row.find(".updated-salary").text().replace(
                            '₹', '').trim()) || 0,
                        final_salary: parseFloat(row.find(".final-salary").text().replace('₹',
                            '').trim()) || 0,
                        apprisal_date: row.find(".appraisal-date").text() || 'N/A',
                        financial_year: selectedFinancialYear || 'N/A'
                    };
                    employees.push(employee);
                });

                if (employees.length === 0) {
                    alert("No employee data to save!");
                    button.prop('disabled', false).text('Save');
                    return;
                }

                $.ajax({
                    url: '<?php echo e(route('financial-data-store')); ?>',
                    method: 'POST',
                    contentType: "application/json",
                    dataType: 'json',
                    data: JSON.stringify({
                        _token: '<?php echo e(csrf_token()); ?>',
                        employees: employees
                    }),
                    success: function(response) {
                        alert('Data saved successfully!');
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred. Please try again.';
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMessage = response.message;
                            }
                        } catch (e) {
                            console.error("Failed to parse error JSON:", e);
                        }
                        alert(errorMessage);
                        button.prop('disabled', false).text('Save');
                    }
                });
            });
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /opt/lampp/htdocs/well-known/resources/views/admin/financial.blade.php ENDPATH**/ ?>