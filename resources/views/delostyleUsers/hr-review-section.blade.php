@extends('layouts.app')

@section('title', 'Hr Review')

@section('breadcrumb', 'Hr')

@section('page-title', 'Hr-Review Dashboard')

@section('content')
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <link href="{{ asset('css/hr-review-section.css') }}?v={{ filemtime(public_path('css/hr-review-section.css')) }}" rel="stylesheet">
    @endpush

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <body>
        <form action="{{route('hr.review.submit')}}" method="post" id="HrReviewSubmit" class="form-inline client__form hr-review-page">
            @csrf
            <div class="client hr-review-header">
                <div class="hr-review-title">
                    <i class="bi bi-people"></i>
                    <div>
                        <h1 class="client__heading">HR Review</h1>
                        <p class="hr-review-subtitle">Search employee and complete HR policy review.</p>
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
                    <input type="search" id="employee_search" name="search" class="form-control client__search"
                        placeholder="search employee" aria-label="Search">
                    <button class="client__btn" type="button">
                        <img src="{{ asset('images/search.png') }}" alt="Search">
                    </button>
                </div>
            </div>

            <!-- Search Results Table -->
            <div class="container employee-table" id="employeeDetails" style="display:none;">
                <div class="table-wrapper">
                    <!-- <div id="selectLabel" class="hidden-label" style="margin-bottom: 10px; font-weight: bold; display: none;">
                        Select the employee:
                    </div> -->
                    <table class="table table-bordered table-hover client-table">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Employee Name</th>
                                <th>Designation</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody id="employeeTableBody">
                            {{-- <tr id="selectLabelRow">
                                <td colspan="4"><strong>Select the employee:</strong></td>
                            </tr> --}}
                            <tr>
                                <td colspan="4">Start typing to search...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="form-section">

                <div>
                            <input type="hidden" id="emp_id_input" name="emp_id" placeholder="Enter Employee Id" required>
                            </input>
                    <div id="reviewExistsError" class="review-exists-error">Already done review for this financial year.</div>
                    <div class="accordion">
                        <div class="content-block">
                            <input type="checkbox" id="section1">
                            <label for="section1" class="main-label">A. Professional Conduct and Policy Compliance</label>
                            <div class="content">
                                <label for="adherence_hr" class="second-label">1. How would you rate the employee’s
                                    adherence
                                    to company policies and
                                    procedures?:</label>
                                <select class="form-select" aria-label="multiple select example" name="adherence_hr"
                                    id="hr1" required>
                                    <option value="" selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>

                                <div class="review-block">
                                    <label for="comments_adherence" class="third-label">Justify Your Review:</label>
                                    <textarea name="comments_adherence_hr" id="comments" class="form-control" rows="1" required
                                        cols="50" maxlength="1500" placeholder="Write your justification here..."></textarea>
                                </div>


                                <div>
                                    <label for="professionalism_positive" class="second-label">2. Does the employee
                                        maintain professionalism and a positive
                                        attitude in the workplace?:</label>
                                    <select class="form-select" aria-label="multiple select example"
                                        name="professionalism_positive" id="hr1" required>
                                        <option value="" selected disabled>Rate</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>

                                    <div class="review-block">
                                        <label for="comments_professionalism_positive" class="third-label">Justify Your
                                            Review:</label>
                                    <textarea name="comments_professionalism" id="comments" class="form-control" required
                                        rows="1" cols="50" maxlength="1500"
                                        placeholder="Write your justification here..."></textarea>
                                </div>
                                </div>
                                <div>
                                    <label for="respond_feedback" class="second-label">3. How well does the employee
                                        respond to feedback or
                                        suggestions for improvement from colleagues?:</label>
                                    <select class="form-select" aria-label="multiple select example" id="hr1"
                                        name="respond_feedback" required>
                                        <option value="" selected disabled>Rate</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <div class="review-block">
                                    <label for="comments_respond_feedback" class="third-label">Justify Your Review:</label>
                                <textarea name="comments_respond_feedback" id="comments" class="form-control" rows="1" required
                                        cols="50" maxlength="1500" placeholder="Write your justification here..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="content-block">
                            <input type="checkbox" id="section2">
                            <label for="section2" class="main-label">B. Initiative, Learning Engagement, and Policy
                                Adherence</label>
                            <div class="content">
                                <label for="initiative" class="second-label">1. Does the employee take the initiative to
                                    seek
                                    feedback and
                                    act
                                    on it?:</label>
                                <select class="form-select" aria-label="multiple select example" id="hr1" name="initiative"
                                    required>
                                    <option value="" selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>

                                <div class="review-block">
                                    <label for="comments_initiative" class="third-label">Justify Your Review:</label>
                                    <textarea name="comments_initiative" id="comments" class="form-control" rows="1" required
                                        cols="50" maxlength="1500" placeholder="Write your justification here..."></textarea>
                                </div>

                                <div>
                                    <label for="comfortable_discussing" class="second-label">2. Has the employee shown
                                        interest
                                        in learning and
                                        participating in training programs?</label>
                                    <select class="form-select" aria-label="multiple select example" id="hr1"
                                        name="interest_learning" required>
                                        <option value="" selected disabled>Rate</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <div class="review-block">
                                    <label for="comments_interest_learning" class="third-label">Justify Your Review:</label>
                                    <textarea name="comments_interest_learning" id="comments" class="form-control" rows="1" required
                                        cols="50" maxlength="1500" placeholder="Write your justification here..."></textarea>
                                </div>


                                <div>
                                    <label for="company_leave_policy" class="second-label">3. Does the employee consistently
                                        adhere to the
                                        company's
                                        leave policy?</label>
                                    <select class="form-select" aria-label="multiple select example" id="hr1"
                                        name="company_leave_policy" required>
                                        <option value="" selected disabled>Rate</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <div class="review-block">
                                    <label for="comments_company_leave_policy" class="third-label">Justify Your
                                        Review:</label>
                                    <textarea name="comments_company_leave_policy" id="comments" class="form-control" required
                                        rows="1" cols="50" maxlength="1500"
                                        placeholder="Write your justification here..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-section form-section--altered total-block">
                            <label for="HrTotalReview">Total Score:</label>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item" name="HrTotalReview" id="HrTotalReview" readonly></li>
                                <li class="breadcrumb-item">30</li>
                            </ol>
                        </div>

                        <div class="form-section mt-4">
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </body>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function () {
        let timeout = null;

        function searchUser() {
            const keyword = $('#employee_search').val().trim();
            const financialYear = $('#financialYear').val();

            if (keyword.length < 2) {
                $('#employeeDetails').hide();
                $('#selectLabel').hide(); // Hide label when input is too short
                return;
            }

            $('#employeeDetails').show();
            $('#employeeTableBody').html('<tr><td colspan="4">Searching...</td></tr>');
            $('#selectLabel').hide(); // Hide while searching

            clearTimeout(timeout);

            timeout = setTimeout(function () {
                $.ajax({
                    url: '{{ route("user-search") }}',
                    type: 'GET',
                    data: {
                        keyword: keyword,
                        financial_year: financialYear
                    },
                    success: function (response) {
                        $('#employeeTableBody').empty();

                        if (response.success && response.users.length > 0) {
                            $('#selectLabel').show(); // Show label before user selects

                            response.users.forEach(function (user) {
                                const reviewedYears = JSON.stringify(user.hr_reviewed_financial_years || []);
                                $('#employeeTableBody').append(`
                                    <tr class="selectable-row" data-emp-id="${user.employee_id}" data-hr-reviewed-years='${reviewedYears}'>
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
                                `<tr><td colspan="4">${response.message || 'No users found'}</td></tr>`
                            );
                        }
                    },
                    error: function () {
                        alert("An error occurred. Please try again.");
                    }
                });
            }, 1000); // Debounce delay
        }

        // Trigger search on keyup
        $('#employee_search').on('keyup', function () {
            $('#emp_id_input').val('').removeData('hr-reviewed-years');
            syncHrReviewFormState();
            searchUser();
        });

        // Handle row selection
        $(document).on('click', '.selectable-row', function () {
            var empId = $(this).data('emp-id');
            var reviewedYears = $(this).data('hr-reviewed-years') || [];
            $('#emp_id_input').val(empId);
            $('#emp_id_input').data('hr-reviewed-years', reviewedYears);

            var selectedRow = $(this).clone().addClass('table-active');
            $('#employeeTableBody').empty().append(selectedRow);

            $('#selectLabel').hide(); // Hide label after user selects
            syncHrReviewFormState();
        });

        $('#financialYear').on('change', function () {
            $('#emp_id_input').val('').removeData('hr-reviewed-years');
            syncHrReviewFormState();
            searchUser();
        });
        $('#HrReviewSubmit').on('input change', 'input, select, textarea', syncHrReviewFormState);
        $('#HrReviewSubmit').on('input', 'textarea', function () {
            updateHrCommentCounter(this);
        });

        function updateHrCommentCounter(textarea) {
            const limit = parseInt(textarea.getAttribute('maxlength'), 10) || 1500;
            const count = textarea.value.length;
            const counter = textarea.parentElement.querySelector('.hr-char-counter');

            if (counter) {
                counter.textContent = `${count}/${limit}`;
                counter.classList.toggle('is-limit', count >= limit);
            }
        }

        function initHrCommentCounters() {
            $('#HrReviewSubmit textarea').each(function () {
                this.setAttribute('maxlength', '1500');

                if (!this.parentElement.querySelector('.hr-char-counter')) {
                    $(this).after('<div class="hr-char-counter" aria-live="polite">0/1500</div>');
                }

                updateHrCommentCounter(this);
            });
        }

        function isHrReviewFormComplete() {
            if (!$('#financialYear').val()) {
                return false;
            }

            if (!$('#employee_search').val().trim() || !$('#emp_id_input').val()) {
                return false;
            }

            let complete = true;

            $('#HrReviewSubmit').find('select[required], textarea[required], input[required]').each(function () {
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

        function syncHrReviewFormState() {
            const financialYear = $('#financialYear').val();
            const reviewedYears = getHrReviewedYears();
            const reviewExists = Boolean(financialYear && reviewedYears.includes(financialYear));

            $('#reviewExistsError').toggle(reviewExists);
            $('#HrReviewSubmit')
                .find('select:not(#financialYear), textarea')
                .prop('disabled', reviewExists);
            $('#submitBtn').prop('disabled', reviewExists || !isHrReviewFormComplete());
        }

        function getHrReviewedYears() {
            const reviewedYears = $('#emp_id_input').data('hr-reviewed-years') || [];

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
        initHrCommentCounters();
        syncHrReviewFormState();
    });

        document.addEventListener("DOMContentLoaded", function () {
            const hrForm = document.getElementById("HrReviewSubmit");

            const showValidationError = (message, focusSelector = null) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: message
                }).then(() => {
                    if (focusSelector) {
                        const field = hrForm.querySelector(focusSelector);
                        if (field) field.focus();
                    }
                });
            };

            const extractErrorMessage = (xhr) => {
                const response = xhr?.responseJSON;
                if (!response) return 'Error submitting HR review.';

                if (response.message && response.errors) {
                    const firstKey = Object.keys(response.errors)[0];
                    const firstError = firstKey ? response.errors[firstKey][0] : null;
                    return firstError || response.message;
                }

                if (response.message) return response.message;

                return 'Error submitting HR review.';
            };

            if (hrForm) {
                hrForm.addEventListener("submit", function (event) {
                    event.preventDefault();

                    const employeeSearch = document.getElementById('employee_search');
                    const empId = document.getElementById('emp_id_input');
                    const financialYear = document.getElementById('financialYear');
                    const firstInvalid = hrForm.querySelector('select[required]:invalid, input[required]:invalid, textarea[required]:invalid');

                    if ($('#reviewExistsError').is(':visible')) {
                        showValidationError('Already done review for this financial year.');
                        return;
                    }

                    if (!financialYear.value) {
                        financialYear.classList.add('is-invalid');
                        showValidationError('Please select a financial year.', '#financialYear');
                        return;
                    }

                    if (!employeeSearch.value.trim()) {
                        employeeSearch.classList.add('is-invalid');
                        showValidationError('Please search and select an employee.', '#employee_search');
                        return;
                    }

                    if (!empId.value) {
                        empId.classList.add('is-invalid');
                        showValidationError('Please select an employee first.', '#emp_id_input');
                        return;
                    }

                    if (firstInvalid) {
                        firstInvalid.classList.add('is-invalid');
                        showValidationError('Please complete all required fields.');
                        firstInvalid.focus();
                        return;
                    }

                    // Calculate total HR rating
                    let totalRating = 0;
                    document.querySelectorAll("select[id^='hr']").forEach(select => {
                        const value = parseInt(select.value);
                        if (!isNaN(value)) {
                            totalRating += value;
                        }
                    });

                    // Create FormData and append the total rating
                    const formData = new FormData(hrForm);
                    formData.append("HrTotalReview", totalRating);

                    $.ajax({
                        url: "{{ route('hr.review.submit') }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        },
                        success: function (response) {
                            console.log("Success:", response);
                            if (response && response.success === false) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message || 'Error submitting HR review.'
                                });
                                return;
                            }

                            const successMessage = (response && response.message)
                                ? response.message
                                : 'HR Review submitted successfully!';

                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: successMessage
                            }).then(() => {
                                hrForm.reset();

                                const totalDisplay = document.getElementById("HrTotalReview");
                                if (totalDisplay) {
                                    totalDisplay.textContent = "";
                                }

                                document.querySelectorAll("select[id^='hr']").forEach(select => {
                                    select.selectedIndex = 0;
                                });

                                document.querySelectorAll("#HrReviewSubmit textarea").forEach(textarea => {
                                    textarea.value = "";
                                    textarea.classList.remove('is-invalid');
                                    textarea.dispatchEvent(new Event('input', { bubbles: true }));
                                });

                                setTimeout(() => {
                                    location.reload();
                                }, 250);
                            });
                        },
                        error: function (xhr) {
                            console.error("Error:", xhr.responseJSON);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: extractErrorMessage(xhr)
                            });
                        }
                    });
                });
            }
        });


        document.addEventListener("DOMContentLoaded", function () {
            function HrTotalReview() {
                let totalRating = 0;

                // Loop through all select elements starting with id="hr"
                document.querySelectorAll("select[id^='hr']").forEach(select => {
                    const value = parseInt(select.value);
                    if (!isNaN(value)) {
                        totalRating += value;
                    }
                });

                console.log("Total Rating:", totalRating); // For debugging

                // Update the total in the breadcrumb
                const totalField = document.getElementById("HrTotalReview");
                if (totalField) {
                    totalField.textContent = totalRating;
                }
            }

            // Attach event listeners to each select element
            document.querySelectorAll("select[id^='hr']").forEach(select => {
                select.addEventListener("input", HrTotalReview);
            });

            window.HrTotalReview = HrTotalReview;
        });

    </script>
@endsection
