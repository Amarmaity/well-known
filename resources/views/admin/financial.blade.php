@extends('layouts.app')
@section('title', 'Financial Dashboard')
@section('breadcrumb', 'Financial')
@section('page-title', 'Financial-Section')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <link href="{{ asset('css/financial-management.css') }}?v={{ filemtime(public_path('css/financial-management.css')) }}" rel="stylesheet">
        <link href="{{ asset('css/financial-extra.css') }}?v={{ filemtime(public_path('css/financial-extra.css')) }}" rel="stylesheet">
    @endpush

    @php
        $currentMonth = date('m');
        $currentYear = date('Y');

        if ($currentMonth < 4) {
            $currentFYStart = $currentYear - 1;
        } else {
            $currentFYStart = $currentYear;
        }

        $years = [
            $currentFYStart - 1,
            $currentFYStart,
            $currentFYStart + 1,
            $currentFYStart + 2,
        ];
    @endphp

    <div class="emp-page financial-page">
        <div class="emp-shell">
            <div class="emp-header">
                <div class="emp-header-text">
                    <h1>Employee Financial Year</h1>
                    <p>Search employee appraisal data, review salary calculations and save financial year records.</p>
                </div>

                <div class="emp-header-actions financial-control">
                    <select id="financialYear" class="form-select financial-year-select" name="financial_year" required>
                        <option value="" selected disabled>Financial Year</option>

                        @foreach ($years as $year)
                            @php
                                $end = $year + 1;
                                $fy = $year . '-' . $end;
                            @endphp
                            <option value="{{ $fy }}" {{ $year == $currentFYStart ? 'selected' : '' }}>
                                {{ $fy }}
                            </option>
                        @endforeach
                    </select>

                    <div class="emp-search">
                        <input type="text" id="employee_search" name="search" placeholder="Search employee ID or name"
                            aria-label="Search">
                        <i class="bi bi-search"></i>
                    </div>
                </div>
            </div>

            <input type="hidden" name="emp_id" id="selectedEmpId">

            <div class="emp-card">
                <form action="{{ route('financial-data-store') }}" method="POST" id="financial-data"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="emp-table-scroll">
                        <table class="emp-table financial-table" id="financialTable">
                            <thead>
                                <tr>
                                    <th style="width:260px;">Employee</th>
                                    <th>Employee ID</th>
                                    <th>Evaluation Score</th>
                                    <th id="hr-review-header" style="display: none;">HR Review</th>
                                    <th id="admin-review-header" style="display: none;">Admin Review</th>
                                    <th id="manager-review-header" style="display: none;">Manager Review</th>
                                    <th id="client-review-header" style="display: none;">Client Review</th>
                                    <th>Appraisal Score</th>
                                    <th>Current Salary</th>
                                    <th>Percentage</th>
                                    <th>Increment Amount</th>
                                    <th>Final Salary</th>
                                    <th>Appraisal Date</th>
                                    <th>Financial Year</th>
                                </tr>
                            </thead>
                            <tbody id="appraisal-body">
                                <tr>
                                    <td colspan="14">
                                        <div class="financial-message">
                                            <i class="bi bi-search"></i>
                                            <h5>Search for an employee</h5>
                                            <p>Enter an employee ID or name and select a financial year to view data.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="financial-savebar">
                        <button type="submit" class="financial-save-btn" id="save-financial-data">
                            <i class="bi bi-save"></i>
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            $(document).ready(function() {
                function showSweetAlert(icon, title, text) {
                    if (typeof Swal !== 'undefined') {
                        return Swal.fire({
                            icon: icon,
                            title: title,
                            text: text
                        });
                    }

                    alert(text);
                    return Promise.resolve();
                }

                function escapeHtml(value) {
                    return String(value ?? '').replace(/[&<>"']/g, function(char) {
                        return {
                            '&': '&amp;',
                            '<': '&lt;',
                            '>': '&gt;',
                            '"': '&quot;',
                            "'": '&#039;'
                        }[char];
                    });
                }

                function getInitials(name) {
                    return name
                        .split(' ')
                        .filter(Boolean)
                        .slice(0, 2)
                        .map((part) => part.charAt(0).toUpperCase())
                        .join('') || 'U';
                }

                function getAvatarColor(name) {
                    const palette = [
                        '#6c8bf5',
                        '#5cb896',
                        '#e0995f',
                        '#c97fd1',
                        '#5fb0c9',
                        '#e08a8a',
                        '#8f8ff0',
                        '#7bbf6a',
                    ];
                    let hash = 0;

                    for (let i = 0; i < name.length; i++) {
                        hash = name.charCodeAt(i) + ((hash << 5) - hash);
                    }

                    return palette[Math.abs(hash) % palette.length];
                }

                function renderMessage(icon, title, message) {
                    $('#appraisal-body').html(`
                        <tr>
                            <td colspan="14">
                                <div class="financial-message">
                                    <i class="bi ${icon}"></i>
                                    <h5>${escapeHtml(title)}</h5>
                                    <p>${escapeHtml(message)}</p>
                                </div>
                            </td>
                        </tr>
                    `);
                }

                function scoreCell(className, value) {
                    const displayValue = `${Number(value).toFixed(2)}%`;
                    return `<span class="${className} financial-score">${displayValue}</span>`;
                }

                function moneyCell(className, value) {
                    const displayValue = `₹${Math.floor(Number(value) || 0)}`;
                    return `<span class="${className} financial-money">${displayValue}</span>`;
                }

                function fetchEmployeeData() {
                    const employeeSearch = $('#employee_search').val().trim();
                    const financialYear = $('#financialYear').val();

                    if (!employeeSearch || !financialYear) {
                        renderMessage('bi-search', 'Search for an employee',
                            'Enter Employee ID/Name and select Financial Year to view data.');
                        return;
                    }

                    renderMessage('bi-hourglass-split', 'Loading financial data',
                        'Please wait while the salary and appraisal data are loaded.');

                    $.ajax({
                        url: "{{ route('financial.data') }}",
                        method: "GET",
                        data: {
                            search: employeeSearch,
                            financial_year: financialYear
                        },
                        success: function(response) {
                            let tableRows = '';
                            let userType = response.user_type;

                            const employeeName = response.employee_name || 'N/A';
                            const employeeId = response.employee_id || 'N/A';
                            const safeEmployeeName = escapeHtml(employeeName);
                            const safeEmployeeId = escapeHtml(employeeId);
                            const evaluationScore = parseFloat(response.evaluationScore) || 0;

                            const hrReview = parseFloat(response.hrReviewData?.[0] || 0);
                            const adminReview = parseFloat(response.adminReviewData?.[0] || 0);
                            const managerReview = parseFloat(response.managerReviewData || 0);
                            const clientReviewValue = parseFloat(response.clientReviewData || 0);

                            const baseSalary = parseFloat(response.salary) || 0;
                            const percentage = parseFloat(response.company_percentage) || 0;

                            let showHRReview = userType !== 'hr' && hrReview > 0;
                            let showAdminReview = userType !== 'admin' && adminReview > 0;
                            let showManagerReview = !(userType === 'hr' || userType === 'admin' ||
                                userType === 'manager') && managerReview > 0;
                            let showClientReview = clientReviewValue > 0;

                            $('#hr-review-header').toggle(showHRReview);
                            $('#admin-review-header').toggle(showAdminReview);
                            $('#manager-review-header').toggle(showManagerReview);
                            $('#client-review-header').toggle(showClientReview);

                            const avgReviewPercentage = parseFloat(response.appraisalScore) || 0;
                            const updatedSalary = parseFloat(response.updatedSalary) || 0;
                            const finalSalary = parseFloat(response.finalSalary) || 0;
                            const appraisalDate = response.appraisalDate || 'N/A';
                            const selectedYear = $('#financialYear').val();
                            const initials = escapeHtml(getInitials(employeeName));
                            const avatarColor = getAvatarColor(employeeName);

                            tableRows += `<tr class="emp-row">
                                <td>
                                    <div class="emp-person">
                                        <div class="emp-avatar" style="background:${avatarColor};">${initials}</div>
                                        <div>
                                            <div class="emp-person-name employeeName">${safeEmployeeName}</div>
                                            <div class="emp-person-meta">${safeEmployeeId}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="employeeId financial-muted">${safeEmployeeId}</span></td>
                                <td>${scoreCell('EvaluationScore', evaluationScore)}</td>
                                ${showHRReview ? `<td>${scoreCell('hrReview', hrReview)}</td>` : ''}
                                ${showAdminReview ? `<td>${scoreCell('adminReview', adminReview)}</td>` : ''}
                                ${showManagerReview ? `<td>${scoreCell('managerReview', managerReview)}</td>` : ''}
                                ${showClientReview ? `<td>${scoreCell('clientReview', clientReviewValue)}</td>` : ''}
                                <td>${scoreCell('avgReview', avgReviewPercentage)}</td>
                                <td>${moneyCell('currentSalary', baseSalary)}</td>
                                <td><span class="percentage financial-percent">${percentage.toFixed(2)}%</span></td>
                                <td>${moneyCell('updated-salary', updatedSalary)}</td>
                                <td>${moneyCell('final-salary', finalSalary)}</td>
                                <td><span class="appraisal-date financial-muted">${escapeHtml(appraisalDate)}</span></td>
                                <td><span class="financial-year financial-muted">${escapeHtml(selectedYear)}</span></td>
                            </tr>`;
                            $('#appraisal-body').html(tableRows);
                        },
                        error: function(xhr) {
                            const errorMsg = xhr.responseJSON?.message || 'Error fetching data';
                            renderMessage('bi-exclamation-circle', 'Unable to load financial data', errorMsg);
                        }
                    });
                }
                $('#employee_search').on('input', fetchEmployeeData);
                $('#financialYear').on('change', fetchEmployeeData);
                $('#save-financial-data').click(function(e) {
                    e.preventDefault();
                    const button = $(this);
                    button.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Saving...');
                    const selectedFinancialYear = $('#financialYear').val();
                    if (!selectedFinancialYear) {
                        showSweetAlert('error', 'Validation Error', 'Please select a financial year.');
                        button.prop('disabled', false).html('<i class="bi bi-save"></i> Save');
                        return;
                    }

                    const employees = [];
                    $('#appraisal-body tr.emp-row').each(function() {
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
                        showSweetAlert('error', 'Validation Error', 'No employee data to save!');
                        button.prop('disabled', false).html('<i class="bi bi-save"></i> Save');
                        return;
                    }

                    $.ajax({
                        url: '{{ route('financial-data-store') }}',
                        method: 'POST',
                        contentType: "application/json",
                        dataType: 'json',
                        data: JSON.stringify({
                            _token: '{{ csrf_token() }}',
                            employees: employees
                        }),
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message || 'Data saved successfully!',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
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
                            showSweetAlert('error', 'Error', errorMessage);
                            button.prop('disabled', false).html('<i class="bi bi-save"></i> Save');
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
