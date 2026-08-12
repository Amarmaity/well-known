@extends('layouts.app')
<!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

@section('title', 'Manager Review')
<!-- Page Title -->

@section('breadcrumb', 'Manager')
<!-- Breadcrumb -->

@section('page-title', 'Manager Dashboard')
<!-- Page Title in Breadcrumb -->

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link
        href="{{ asset('css/manager-review-section.css') }}?v={{ filemtime(public_path('css/manager-review-section.css')) }}"
        rel="stylesheet">
@endpush

@section('content')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <body>
        <form action="{{ route('manager.review.submit') }}" method="post" id="ManagerReviewSubmit" class="manager-review-page"
            novalidate>
            @csrf
            <div class="client manager-review-header">
                <div class="manager-review-title">
                    <i class="bi bi-diagram-3"></i>
                    <div>
                        <h1 class="client__heading">Manager Review</h1>
                        <p class="manager-review-subtitle">Search employee and complete manager review.</p>
                    </div>
                </div>
                @php
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
                @endphp

                <select id="financialYear" class="form-select client__select" name="financial_year" required>
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

                <div class="client___item">
                    <input type="search" id="employee_search" name="search" class="form-control client__search" required
                        placeholder="search employee" aria-label="Search">
                    <button class="client__btn" type="button" id="employeeSearchBtn">
                        <img src="{{ asset('images/search.png') }}" alt="Search">
                    </button>
                </div>

            </div>
            <div class="container table-container" id="employeeDetails" style="display:none;">
                <div class="table-wrapper">
                    <!-- <div id="selectLabel" class="hidden-label" style="margin-bottom: 10px; font-weight: bold; display: none;">
                        </div> -->
                    <table class="table  table-bordered table-hover client-table">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Employee ID</th>
                                <th>Designation</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody id="employeeTableBody">
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Form Section -->
            <div class="form-section accordion">
                <div class="container">
                    <input type="hidden" name="emp_id" id="emp_id_input" placeholder="Enter Employee Id" required>
                    </input>
                    <div id="reviewExistsError" class="review-exists-error">Already done review for this financial year.
                    </div>
                    <div class="content-block">
                        <input type="checkbox" name="" id="content8">
                        <label for="content8" class="main-label">A. Understanding Requirements</label>
                        <div class="content">
                            <label for="employee_work_quality" class="second-label">1. How would you rate the employee’s
                                quality
                                of
                                work, including
                                accuracy, neatness, and timeliness?:</label>
                            <select class="form-select" aria-label="multiple select example" name="rate_employee_quality"
                                id="mrs" required>
                                <option value="" selected disabled>Rate</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>

                            <div class="review-block">
                                <label for="comments_employee_work_quality" class="third-label">Tell us more about your
                                    experience:</label>
                                <textarea name="comments_rate_employee_quality" id="comments" class="form-control" rows="1" required
                                    minlength="1" cols="50" maxlength="1500"
                                    placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                            </div>
                            <label for="organizational_goals" class="second-label">2. Does the employee align their work
                                with
                                the
                                organization's
                                goals and objectives?:</label>
                            <select class="form-select" aria-label="multiple select example" name="organizational_goals"
                                id="mrs" required>
                                <option value="" selected disabled>Rate</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>

                            <div class="review-block">
                                <label for="comments_organizational_goals" class="third-label">Tell us more about your
                                    experience:</label>
                                <textarea name="comments_organizational_goals" id="comments" class="form-control" rows="1" required
                                    minlength="1" cols="50" maxlength="1500"
                                    placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                            </div>

                            <div>
                                <label for="collaborate_colleagues" class="second-label">3. How effectively does the
                                    employee
                                    contribute to team
                                    efforts and collaborate with colleagues?:</label>
                                <select class="form-select" aria-label="multiple select example"
                                    name="collaborate_colleagues" id="mrs" required>
                                    <option value="" selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <div class="review-block">
                                <label for="comments_collaborate_colleagues" class="third-label">Tell us more about your
                                    experience:</label>
                                <textarea name="comments_collaborate_colleagues" id="comments" class="form-control" rows="1" required
                                    minlength="1" cols="50" maxlength="1500"
                                    placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                            </div>

                            <div>
                                <label for="demonstrated" class="second-label">4. Can you provide an example of when the
                                    employee
                                    demonstrated
                                    problem-solving skills?:</label>
                                <select class="form-select" aria-label="multiple select example" name="demonstrated"
                                    id="mrs" required>
                                    <option value="" selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <div class="review-block">
                                <label for="comments_demonstrated" class="third-label">Tell us more about your
                                    experience:</label>
                                <textarea name="comments_demonstrated" id="comments" class="form-control" rows="1" cols="50" required
                                    minlength="1" maxlength="1500"
                                    placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                            </div>

                            <div>
                                <label for="leadership_responsibilities" class="second-label">5. Has the employee shown
                                    leadership
                                    potential or
                                    accepted additional responsibilities?:</label>
                                <select class="form-select" aria-label="multiple select example"
                                    name="leadership_responsibilities" id="mrs" required>
                                    <option value="" selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <div class="review-block">
                                <label for="comments_leadership_responsibilities" class="third-label">Tell us more about
                                    your
                                    experience:</label>
                                <textarea name="comments_leadership_responsibilities" id="comments" class="form-control" required minlength="1"
                                    rows="1" cols="50" maxlength="1500"
                                    placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                            </div>

                            <div>
                                <label for="thinking_contribution" class="second-label">6. How would you rate the
                                    employee’s
                                    innovative
                                    thinking
                                    and contribution to team success?:</label>
                                <select class="form-select" aria-label="multiple select example"
                                    name="thinking_contribution" id="mrs" required>
                                    <option value="" selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <div class="review-block">
                                <label for="comments_thinking_contribution" class="third-label">Tell us more about your
                                    experience:</label>
                                <textarea name="comments_thinking_contribution" id="comments" class="form-control" rows="1" required
                                    minlength="1" cols="50" maxlength="1500"
                                    placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                            </div>

                            <div>
                                <label for="informed_progress" class="second-label">7. Does the employee effectively keep
                                    you
                                    informed
                                    about
                                    work progress and issues?:</label>
                                <select class="form-select" aria-label="multiple select example" name="informed_progress"
                                    id="mrs1" required>
                                    <option value="" selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <div class="review-block">
                                <label for="comments_informed_progress" class="third-label">Tell us more about your
                                    experience:</label>
                                <textarea name="comments_comments_informed_progress" id="comments" class="form-control" required minlength="1"
                                    rows="1" cols="50" maxlength="1500"
                                    placeholder="What made you give this rating? Share specific examples or feedback to help us improve."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-section form-section--altered total-block">
                        <label for="ClientTotalReview">Total Score:</label>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item" name="ManagerTotalReview" id="managerTotalReview" readonly></li>
                            <li class="breadcrumb-item">35</li>
                        </ol>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                        <button type="reset" class="btn btn-outlined d-none">Clear</button>
                    </div>
                </div>
            </div>
        </form>
    </body>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/reviewValidation.js') }}"></script>

    <script>
        $(function() {
            let timeout = null;

            function searchUser() {
                const keyword = $('#employee_search').val().trim();

                if (keyword.length < 2) {
                    $('#employeeDetails').hide();
                    $('#selectLabel').hide(); // Hide label when input is too short
                    return;
                }

                $('#employeeDetails').show();
                $('#employeeTableBody').html('<tr><td colspan="4">Searching...</td></tr>');
                $('#selectLabel').hide(); // Hide while searching

                clearTimeout(timeout);

                timeout = setTimeout(function() {
                    $.ajax({
                        url: '{{ route('user-search') }}',
                        type: 'GET',
                        data: {
                            keyword: keyword
                        },
                        success: function(response) {
                            $('#employeeTableBody').empty();

                            if (response.success && response.users.length > 0) {
                                $('#selectLabel').show(); // Show label when results found

                                response.users.forEach(function(user) {
                                    const reviewedYears = JSON.stringify(user
                                        .manager_reviewed_financial_years || []);
                                    $('#employeeTableBody').append(`
                                        <tr class="selectable-row" data-emp-id="${user.employee_id}" data-manager-reviewed-years='${reviewedYears}'>
                                            <td>${user.employee_id}</td>
                                            <td>${user.fname} ${user.lname}</td>
                                            <td>${user.designation}</td>
                                            <td>${user.email}</td>
                                        </tr>
                                    `);
                                });
                            } else {
                                $('#selectLabel').hide(); // Hide label if no results
                                $('#employeeTableBody').html(
                                    '<tr><td colspan="4">No users found</td></tr>'
                                );
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred. Please try again.'
                            });
                        }
                    });
                }, 1000); // Debounce time
            }

            // Handle input typing
            $('#employee_search').on('keyup', function() {
                $('#emp_id_input').val('').removeData('manager-reviewed-years');
                syncManagerReviewFormState();
                searchUser();
            });
            $('#employeeSearchBtn').on('click', function() {
                searchUser();
            });

            // Handle row click
            $(document).on('click', '.selectable-row', function() {
                var empId = $(this).data('emp-id');
                var reviewedYears = $(this).data('manager-reviewed-years') || [];
                $('#emp_id_input').val(empId);
                $('#emp_id_input').data('manager-reviewed-years', reviewedYears);

                var selectedRow = $(this).clone().addClass('table-active');
                $('#employeeTableBody').empty().append(selectedRow);

                $('#selectLabel').hide(); // Hide label after selection
                syncManagerReviewFormState();
            });

            $('#financialYear').on('change', syncManagerReviewFormState);
            $('#ManagerReviewSubmit').on('input change', 'input, select, textarea', syncManagerReviewFormState);
            $('#ManagerReviewSubmit').on('input', 'textarea', function() {
                updateManagerCommentCounter(this);
            });

            function updateManagerCommentCounter(textarea) {
                const limit = parseInt(textarea.getAttribute('maxlength'), 10) || 1500;
                const count = textarea.value.length;
                const counter = textarea.parentElement.querySelector('.manager-char-counter');

                if (counter) {
                    counter.textContent = `${count}/${limit}`;
                    counter.classList.toggle('is-limit', count >= limit);
                }
            }

            function initManagerCommentCounters() {
                $('#ManagerReviewSubmit textarea').each(function() {
                    this.setAttribute('maxlength', '1500');

                    if (!this.parentElement.querySelector('.manager-char-counter')) {
                        $(this).after('<div class="manager-char-counter" aria-live="polite">0/1500</div>');
                    }

                    updateManagerCommentCounter(this);
                });
            }

            function getManagerReviewedYears() {
                const reviewedYears = $('#emp_id_input').data('manager-reviewed-years') || [];

                if (Array.isArray(reviewedYears)) {
                    return reviewedYears;
                }

                if (typeof reviewedYears === 'string') {
                    try {
                        return JSON.parse(reviewedYears);
                    } catch (error) {
                        return [];
                    }
                }

                return [];
            }

            function isManagerReviewFormComplete() {
                if (!$('#financialYear').val()) {
                    return false;
                }

                if (!$('#employee_search').val().trim() || !$('#emp_id_input').val()) {
                    return false;
                }

                let complete = true;

                $('#ManagerReviewSubmit').find('select[required], textarea[required], input[required]').each(
                    function() {
                        if (!complete) {
                            return;
                        }

                        const $field = $(this);
                        const type = ($field.attr('type') || '').toLowerCase();

                        if ($field.is(':disabled') || type === 'hidden') {
                            return;
                        }

                        if ($field.is('select')) {
                            const selectedOption = this.options[this.selectedIndex];
                            if (!$field.val() || (selectedOption && selectedOption.disabled)) {
                                complete = false;
                            }
                            return;
                        }

                        if (!$field.val() || !$field.val().trim()) {
                            complete = false;
                        }
                    });

                return complete;
            }

            function syncManagerReviewFormState() {
                const financialYear = $('#financialYear').val();
                const reviewedYears = getManagerReviewedYears();
                const reviewExists = Boolean(financialYear && reviewedYears.includes(financialYear));

                $('#reviewExistsError').toggle(reviewExists);
                $('#ManagerReviewSubmit')
                    .find('select:not(#financialYear), textarea')
                    .prop('disabled', reviewExists);
                $('#submitBtn').prop('disabled', reviewExists || !isManagerReviewFormComplete());
            }

            initManagerCommentCounters();
            syncManagerReviewFormState();
        });

        document.getElementById("ManagerReviewSubmit").addEventListener("submit", function(event) {
            event.preventDefault();

            let form = this;
            let formData = new FormData(form);
            const employeeSearch = document.getElementById('employee_search');
            const requiredFields = Array.from(form.querySelectorAll(
                    'select[required], input[required], textarea[required]'))
                .filter(field => field.id !== 'employee_search');

            const fieldLabelMap = {
                financialYear: 'Financial Year',
                employee_search: 'Employee Search',
                emp_id_input: 'Selected Employee',
                rate_employee_quality: 'Question 1 rating',
                comments_rate_employee_quality: 'Question 1 comment',
                organizational_goals: 'Question 2 rating',
                comments_organizational_goals: 'Question 2 comment',
                collaborate_colleagues: 'Question 3 rating',
                comments_collaborate_colleagues: 'Question 3 comment',
                demonstrated: 'Question 4 rating',
                comments_demonstrated: 'Question 4 comment',
                leadership_responsibilities: 'Question 5 rating',
                comments_leadership_responsibilities: 'Question 5 comment',
                thinking_contribution: 'Question 6 rating',
                comments_thinking_contribution: 'Question 6 comment',
                informed_progress: 'Question 7 rating',
                comments_comments_informed_progress: 'Question 7 comment',
            };

            const getFieldLabel = (field) => {
                if (!field) return 'this field';

                const byName = fieldLabelMap[field.name];
                if (byName) return byName;

                const byId = fieldLabelMap[field.id];
                if (byId) return byId;

                const label = form.querySelector(`label[for="${field.id}"]`);
                if (label?.innerText?.trim()) return label.innerText.trim();

                const nearbyLabel = field.closest('.review-block, div, .client___item')?.querySelector('label');
                if (nearbyLabel?.innerText?.trim()) return nearbyLabel.innerText.trim();

                return field.getAttribute('name') || field.id || 'this field';
            };

            const showError = (message, focusEl = null) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: message
                }).then(() => {
                    if (focusEl) {
                        focusEl.focus();
                    }
                });
            };

            const extractErrorMessage = (xhr) => {
                const response = xhr?.responseJSON;
                if (!response) return 'An error occurred while submitting the review.';
                if (response.errors) {
                    const firstKey = Object.keys(response.errors)[0];
                    if (firstKey && response.errors[firstKey] && response.errors[firstKey][0]) {
                        const targetField = form.querySelector(`[name="${firstKey}"]`);
                        const fieldLabel = getFieldLabel(targetField);
                        return `${fieldLabel}: ${response.errors[firstKey][0]}`;
                    }
                }
                return response.message || 'An error occurred while submitting the review.';
            };

            const empId = document.getElementById('emp_id_input');
            const financialYear = document.getElementById('financialYear');
            const validateField = (field) => {
                if (!field) return true;
                if (field.type === 'checkbox' || field.type === 'radio') {
                    return field.checked;
                }
                return String(field.value ?? '').trim().length > 0;
            };

            if ($('#reviewExistsError').is(':visible')) {
                showError('Already done review for this financial year.');
                return;
            }

            if (!financialYear.value) {
                financialYear.classList.add('is-invalid');
                showError('Please select a financial year.', financialYear);
                return;
            }

            if (!employeeSearch.value.trim()) {
                employeeSearch.classList.add('is-invalid');
                showError('Please search for an employee.', employeeSearch);
                return;
            }

            if (!empId.value) {
                empId.classList.add('is-invalid');
                showError('Please select an employee first.', empId);
                return;
            }

            for (const field of requiredFields) {
                if (!validateField(field)) {
                    field.classList.add('is-invalid');
                    showError(`Please complete ${getFieldLabel(field)}.`, field);
                    return;
                }
            }

            const firstInvalid = form.querySelector(
                'select[required]:invalid, input[required]:invalid, textarea[required]:invalid');
            if (firstInvalid) {
                firstInvalid.classList.add('is-invalid');
                showError('Please complete all required fields.', firstInvalid);
                return;
            }

            // Get manager's total rating
            let totalRating = document.getElementById("managerTotalReview").textContent.trim();
            formData.append("ManagerTotalReview", totalRating);

            Swal.fire({
                icon: 'question',
                title: 'Are you sure?',
                text: 'Do you want to submit this Manager review?',
                showCancelButton: true,
                confirmButtonText: 'Yes, submit',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({
                    url: "{{ route('manager.review.submit') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]')
                            .getAttribute("content")
                    },
                    success: function(response) {
                        console.log("Success:", response);
                        Swal.fire({
                            icon: 'success',
                            title: 'Submitted',
                            text: response.message ||
                                "Manager Review submitted successfully!"
                        }).then(() => {
                            form.reset();

                            const totalDisplay = document.getElementById(
                                "managerTotalReview");
                            if (totalDisplay) {
                                totalDisplay.textContent = "";
                            }

                            document.querySelectorAll("select[id^='mrs']").forEach(
                                select => {
                                    select.selectedIndex = 0;
                                });

                            document.querySelectorAll("textarea").forEach(textarea => {
                                textarea.value = "";
                            });
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        console.error("Error:", xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: extractErrorMessage(xhr)
                        });
                    }
                });
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            function managerTotalReview() {
                let totalRating = 0;

                // Loop through all select elements starting with id="cli"
                document.querySelectorAll("select[id^='mrs']").forEach(select => {
                    const value = parseInt(select.value);
                    if (!isNaN(value)) {
                        totalRating += value;
                    }
                });

                // Display the total inside a <li> element
                const totalField = document.getElementById("managerTotalReview");
                if (totalField) {
                    totalField.textContent = totalRating;
                }
            }

            // Attach event listeners to each select
            document.querySelectorAll("select[id^='mrs']").forEach(select => {
                select.addEventListener("change", () => select.classList.remove('is-invalid'));
                select.addEventListener("input", managerTotalReview);
            });
            document.querySelectorAll("input, textarea").forEach(el => el.addEventListener("input", () => el
                .classList.remove('is-invalid')));
        });
    </script>
@endsection
