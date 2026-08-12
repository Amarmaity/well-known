@extends('layouts.app')

@section('title', 'Client Review Dashboard')
@section('breadcrumb', 'Client Review List')
@section('page-title', 'Client-Review-Section')

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
        </style>
    </head>



    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Review Table</title>

    <!-- Include CSS for DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>

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
                        placeholder="Search" aria-label="Search">
                    <button class="client__btn" type="submit">
                        <img src="{{ asset('images/search.png') }}" alt="Search">
                    </button>
                </div>
                {{-- <input type="hidden" name="emp_id" id="selectedEmpId"> --}}
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
                            <!-- Example data, replace it with dynamic data from PHP -->
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

                                            $clientReviewYears = optional($clientReviewYearsByEmployee ?? collect())
                                                ->get($user->employee_id, collect())
                                                ->values()
                                                ->all();
                                            $hasSelectedClientReview = in_array(
                                                $selectedFinancialYear,
                                                $clientReviewYears,
                                                true,
                                            );
                                        @endphp

                                        <select class="form-control financial-year input-block" required>
                                            <option value="" selected disabled>Financial Year</option>

                                            @foreach ($years as $year)
                                                @php
                                                    $fy = $year . '-' . ($year + 1);
                                                @endphp

                                                <option value="{{ $fy }}"
                                                    {{ $fy === $selectedFinancialYear ? 'selected' : '' }}>
                                                    {{ $fy }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <div class="btn-block">
                                            <a href="{{ route('user-client-details', $user->employee_id) }}"
                                                class="btn btn-primary view-client-details {{ $hasSelectedClientReview ? '' : 'disabled' }}"
                                                data-client-review-years='@json($clientReviewYears)'
                                                aria-disabled="{{ $hasSelectedClientReview ? 'false' : 'true' }}"
                                                @unless ($hasSelectedClientReview) tabindex="-1" @endunless>
                                                View Details
                                            </a>

                                            <a href="{{ route('user-report-view-evaluation', $user->employee_id) }}"
                                                class="btn btn-primary view-evaluation">
                                                View Evaluation
                                            </a>
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

    <!-- Initialize DataTables with search functionality -->
    <script>
        function getClientReviewYears($button) {
            try {
                return JSON.parse($button.attr('data-client-review-years') || '[]');
            } catch (error) {
                return [];
            }
        }

        function updateClientDetailsButton($row) {
            const selectedYear = $row.find('.financial-year').val();
            const $button = $row.find('.view-client-details');

            if (!$button.length) {
                return;
            }

            const clientReviewYears = getClientReviewYears($button);
            const isEnabled = selectedYear && clientReviewYears.includes(selectedYear);

            $button.toggleClass('disabled', !isEnabled)
                .attr('aria-disabled', isEnabled ? 'false' : 'true')
                .attr('tabindex', isEnabled ? '0' : '-1');
        }

        function updateAllClientDetailsButtons() {
            $('#employeeReviewTable tbody tr').each(function() {
                updateClientDetailsButton($(this));
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

            updateAllClientDetailsButtons();

            $('#employeeReviewTable').on('change', '.financial-year', function() {
                updateClientDetailsButton($(this).closest('tr'));
            });
        });



        $(document).ready(function() {
            $('#employeeReviewTable').on('click', '.view-client-details', function(e) {
                e.preventDefault();

                if ($(this).hasClass('disabled')) {
                    return;
                }

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
    </script>

    </body>
    </html>
@endsection
