@extends('layouts.app')

@section('title', 'Hr Review Dashboard')
@section('breadcrumb', 'Hr Review List')
@section('page-title', 'Hr-Review-Section')

@section('content')
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <link href="{{ asset('css/employee-review-list.css') }}?v={{ filemtime(public_path('css/employee-review-list.css')) }}"
            rel="stylesheet">
    @endpush

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Employee Review Table</title>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
            }

            table,
            th,
            td {
                border: 1px solid black;
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



    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Employee Review Table</title>

        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>

        <style>
            table {
                width: 100%;
                max-width: 1500px;
                border-collapse: collapse;
                margin: 0 auto;
            }

            table,
            th,
            td {
                border: 1px solid black;
            }

            th,
            td {
                padding: 10px;
                text-align: left;
            }
        </style>

    </head>

    <body>

        <div class="review-list-page">
            <div class="client clients-block review-list-header">
                <div class="review-list-title">
                    <i class="bi bi-card-checklist"></i>
                    <div>
                        <h1 class="client__heading">View Employee Reviews</h1>
                        <p class="review-list-subtitle">Search employees and open review details by financial year.</p>
                    </div>
                </div>
                <div class="client___item">
                    <input type="search" id="employee_search" name="search" class="form-control client__search"
                        placeholder="search employee" aria-label="Search">
                    <button class="client__btn" type="submit">
                        <img src="{{ asset('images/search.png') }}" alt="Search">
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
                            @foreach ($superAddUser as $user)
                                <tr>
                                    <td>{{ $user->fname }} {{ $user->lname }}</td>
                                    <td>{{ $user->employee_id }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
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
                                            $currentFinancialYear = $currentFYStart . '-' . ($currentFYStart + 1);
                                            $latestEvaluationFinancialYear = optional(
                                                $latestEvaluationYearsByEmployee ?? collect(),
                                            )->get($user->employee_id);
                                            $selectedFinancialYear =
                                                $latestEvaluationFinancialYear ?: $currentFinancialYear;

                                            if (
                                                $latestEvaluationFinancialYear &&
                                                preg_match(
                                                    '/^(\d{4})-\d{4}$/',
                                                    $latestEvaluationFinancialYear,
                                                    $matches,
                                                )
                                            ) {
                                                $years[] = (int) $matches[1];
                                                $years = array_values(array_unique($years));
                                                sort($years);
                                            }

                                            $hrReviewYears = optional($hrReviewYearsByEmployee ?? collect())
                                                ->get($user->employee_id, collect())
                                                ->values()
                                                ->all();
                                            $hasSelectedHrReview = in_array(
                                                $selectedFinancialYear,
                                                $hrReviewYears,
                                                true,
                                            );
                                        @endphp

                                        <select id="financial_year" class="form-control financial-year input-block"
                                            required>
                                            <option value="" selected disabled>Financial Year</option>

                                            @foreach ($years as $year)
                                                @php
                                                    $end = $year + 1;
                                                    $fy = $year . '-' . $end;
                                                @endphp

                                                <option value="{{ $fy }}"
                                                    {{ $fy === $selectedFinancialYear ? 'selected' : '' }}>
                                                    {{ $fy }}
                                                </option>
                                            @endforeach

                                        </select>
                                        <div class="btn-block">
                                            @php
                                                $sessionUserType = session()->get('user_type');
                                                $sessionEmployeeId = session()->get('employee_id');
                                            @endphp

                                            @if (!($sessionUserType === 'hr' && $sessionEmployeeId == $user->employee_id))
                                                <a href="{{ route('user-hr-details', $user->employee_id) }}"
                                                    class="btn btn-primary view-details {{ $hasSelectedHrReview ? '' : 'disabled' }}"
                                                    data-hr-review-years='@json($hrReviewYears)'
                                                    aria-disabled="{{ $hasSelectedHrReview ? 'false' : 'true' }}"
                                                    @unless ($hasSelectedHrReview) tabindex="-1" @endunless>
                                                    View Details
                                                </a>
                                            @endif
                                            <a href="{{ route('user-report-view-evaluation', $user->employee_id) }}"
                                                class="btn btn-primary view-evaluation">View Evaluation</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
    <script>
        function getHrReviewYears($button) {
            try {
                return JSON.parse($button.attr('data-hr-review-years') || '[]');
            } catch (error) {
                return [];
            }
        }

        function updateHrDetailsButton($row) {
            const selectedYear = $row.find('.financial-year').val();
            const $button = $row.find('.view-details');

            if (!$button.length) {
                return;
            }

            const hrReviewYears = getHrReviewYears($button);
            const isEnabled = selectedYear && hrReviewYears.includes(selectedYear);

            $button.toggleClass('disabled', !isEnabled)
                .attr('aria-disabled', isEnabled ? 'false' : 'true')
                .attr('tabindex', isEnabled ? '0' : '-1');
        }

        function updateAllHrDetailsButtons() {
            $('#employeeReviewTable tbody tr').each(function() {
                updateHrDetailsButton($(this));
            });
        }

        $(document).ready(function() {
            var table = $('#employeeReviewTable').DataTable({
                "paging": true,
                "pageLength": 15,
                "lengthChange": false,
                "searching": true, // keep this true to allow external filtering
                "ordering": false,
                "info": true
            });

            // Bind the custom search input
            $('#employee_search').on('keyup', function() {
                table.search(this.value).draw();
            });

            updateAllHrDetailsButtons();

            $('#employeeReviewTable').on('change', '.financial-year', function() {
                updateHrDetailsButton($(this).closest('tr'));
            });
        });


        $(document).ready(function() {
            $('#employeeReviewTable').on('click', '.view-details', function(e) {
                e.preventDefault();

                if ($(this).hasClass('disabled')) {
                    return;
                }

                const financialYear = $(this).closest('tr').find('.financial-year').val();
                const baseUrl = $(this).attr('href');

                if (!financialYear) {
                    alert('Please select a financial year!');
                    return;
                }

                $.ajax({
                    url: baseUrl + '?financial_year=' + financialYear,
                    method: 'GET',
                    success: function(response) {
                        // If the response is JSON with a "message", show alert
                        if (response.message) {
                            alert(response.message);
                        } else {
                            // If not, assume it's the HTML of the details page
                            window.location.href = baseUrl + '?financial_year=' + financialYear;
                        }
                    },
                    error: function(xhr) {
                        alert('Something went wrong.');
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



@endsection
