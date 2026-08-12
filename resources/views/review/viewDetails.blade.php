@extends('layouts.app') <!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

@section('title', 'Super Admin | Employee Review') <!-- Page Title -->

@section('breadcrumb', "Super view / Employee {$emp_id}") <!-- Breadcrumb -->

@section('page-title', 'Super Admin Dashboard') <!-- Page Title in Breadcrumb -->

@section('body-class', 'special-page')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <link href="{{ asset('css/review-management.css') }}?v={{ filemtime(public_path('css/review-management.css')) }}"
            rel="stylesheet">
        <link href="{{ asset('css/review-details.css') }}?v={{ filemtime(public_path('css/review-details.css')) }}"
            rel="stylesheet">
    @endpush

    <div class="emp-page review-detail-page">
        <div class="emp-shell">
            <div class="review-detail-header">
                <div class="emp-header-text">
                    <button type="button" onclick="history.back()" class="review-back-btn">
                        Back
                    </button>
                    <h1>Employee Review Details</h1>
                    <p>Employee ID: <strong>{{ $emp_id }}</strong></p>
                </div>
            </div>

            <div class="review-control-card">
                <div>
                    <div class="review-control-label">Financial Year</div>
                    <div class="review-control-help">Choose a year to load available review scores and reports.</div>
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

                    $latestEvaluationFinancialYear = $users['evaluation']->financial_year ?? null;
                    $selectedFinancialYear =
                        $latestEvaluationFinancialYear ?: $currentFYStart . '-' . ($currentFYStart + 1);

                    $years = [
                        $currentFYStart - 1, // Previous FY
                        $currentFYStart, // Current FY
                        $currentFYStart + 1, // Next FY
                        $currentFYStart + 2, // Next +1 FY
                    ];

                    if (
                        $latestEvaluationFinancialYear &&
                        preg_match('/^(\d{4})-\d{4}$/', $latestEvaluationFinancialYear, $matches)
                    ) {
                        $years[] = (int) $matches[1];
                        $years = array_values(array_unique($years));
                        sort($years);
                    }
                @endphp

                <select id="employeeDetails" class="form-select review-year-select" name="financial_year" required>
                    <option value="" selected disabled>Financial Year</option>

                    @foreach ($years as $year)
                        @php
                            $end = $year + 1;
                            $fy = $year . '-' . $end;
                        @endphp

                        <option value="{{ $fy }}" {{ $fy === $selectedFinancialYear ? 'selected' : '' }}>
                            {{ $fy }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div id="reviewTableContainer" class="review-score-card" style="display: none;">
                <div class="review-section-title">
                    <i class="bi bi-bar-chart-line"></i>
                    Review Score Summary
                </div>
                <div class="review-score-grid">
                    <div class="review-score-item">
                        <div class="review-score-label" id="evaluationColumnHeader">Total Evaluation Score</div>
                        <div class="review-score-value" id="totalScoreCell"></div>
                    </div>

                    <div class="review-score-item" id="hrScoreBlock">
                        <div class="review-score-label" id="hrColumnHeader">HR Review Score</div>
                        <div class="review-score-value" id="hrScoreCell"></div>
                    </div>

                    <div class="review-score-item" id="adminScoreBlock">
                        <div class="review-score-label" id="adminColumnHeader">Admin Review Score</div>
                        <div class="review-score-value" id="adminScoreCell"></div>
                    </div>

                    <div class="review-score-item" id="managerScoreBlock">
                        <div class="review-score-label" id="managerColumnHeader">Manager Review Score</div>
                        <div class="review-score-value" id="managerScoreCell"></div>
                    </div>

                    <div class="review-score-item" id="clientScoreBlock" style="display: none;">
                        <div class="review-score-label" id="clientColumnHeader">Client Review Score</div>
                        <div class="review-score-value" id="clientScoreCell"></div>
                    </div>
                </div>
            </div>


            <div class="review-actions-card" id="evaluationReportActions">
                <div class="review-section-title">
                    <i class="bi bi-folder2-open"></i>
                    Available Reports
                </div>
                @php $userRoles = $user_roles ?? []; @endphp
                @if (optional($users['evaluation'])->emp_id)
                    <button class="review-action-btn"
                        onclick="loadReport('evaluation', '{{ $users['evaluation']->emp_id }}')">
                        <i class="bi bi-clipboard-check"></i>
                        Evaluation Details
                    </button>
                @else
                    <p class="review-pending">Evaluation review is pending.</p>
                @endif


                @php
                    $userRoles = $user_roles ?? [];
                @endphp

                {{-- HR --}}
                @if (in_array('hr', $userRoles))
                    @if (optional($users['hrReview'])->emp_id)
                        <button class="review-action-btn"
                            onclick="loadReport('hrReport', '{{ $users['hrReview']->emp_id }}')">
                            <i class="bi bi-people"></i>
                            View HR Review
                        </button>
                    @else
                        <p class="review-pending">HR review is pending.</p>
                    @endif
                @endif

                {{-- Admin --}}
                @if (in_array('admin', $userRoles))
                    @if (optional($users['adminReview'])->emp_id)
                        <button class="review-action-btn"
                            onclick="loadReport('adminReport', '{{ $users['adminReview']->emp_id }}')">
                            <i class="bi bi-person-check"></i>
                            View Admin Review
                        </button>
                    @else
                        <p class="review-pending">Admin review is pending.</p>
                    @endif
                @endif

                {{-- Manager --}}
                @if (in_array('manager', $userRoles))
                    @if (optional($users['managerReview'])->emp_id)
                        <button class="review-action-btn"
                            onclick="loadReport('managerReport', '{{ $users['managerReview']->emp_id }}')">
                            <i class="bi bi-diagram-3"></i>
                            View Manager Review
                        </button>
                    @else
                        <p class="review-pending">Manager review is pending.</p>
                    @endif
                @endif

                @if ($clientReviews->isNotEmpty())
                    @foreach ($clientReviews as $clientReview)
                        <button class="review-action-btn"
                            onclick="loadClientReport('{{ $clientReview->emp_id }}', '{{ $clientReview->client_id }}')">
                            <i class="bi bi-briefcase"></i>
                            View Client Review for: {{ $clientReview->client_name ?? 'Unknown Client' }}
                        </button>
                    @endforeach
                @elseif(in_array('client', $user_roles))
                    <p class="review-pending">Your client review is pending.</p>
                @endif

            </div>

            <div id="reportDetails" class="review-loaded-report"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- JavaScript for Navigation -->
    <script>
        function loadReport(reportType, empId) {
            // console.log('Employee ID:', empId);

            $('#reportDetails').empty();

            const financialYear = $('#employeeDetails').val();
            if (!financialYear) {
                $('#reportDetails').html('<p>Please select a financial year first.</p>');
                return;
            }

            let url = '';
            switch (reportType) {
                case 'evaluation':
                    url = `/employee/evaluation/${empId}`;
                    break;

                case 'managerReport':
                    url = `/manager/review/details/${empId}`;
                    break;
                case 'adminReport':
                    url = `/admin/review/details/${empId}`;
                    break;
                case 'hrReport':
                    url = `/hr/review/details/${empId}`;
                    break;
                case 'clientReport':
                    url = `/client/review/details/${empId}`;
                    break;
                default:
                    console.error('Unknown report type');
                    url = '';
                    break;
            }

            if (url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    data: {
                        financial_year: financialYear
                    },
                    success: function(response) {

                        $('#reportDetails').html(response);
                        $('#reportDetails').addClass('table-container');
                    },
                    error: function(xhr) {
                        const message = xhr.status === 404 ?
                            'No data found for this financial year.' :
                            'Sorry, there was an error loading the report.';
                        $('#reportDetails').html('<p>' + message + '</p>');
                    }
                });
            } else {
                $('#reportDetails').html('<p>Invalid report type provided.</p>');
            }
        }

        function escapeHtml(value) {
            return String(value ?? "").replace(/[&<>"'\/]/g, function(char) {
                return {
                    "&": "&amp;",
                    "<": "&lt;",
                    ">": "&gt;",
                    "\"": "&quot;",
                    "'": "&#039;"
                } [char];
            });
        }

        function renderReportActions(data) {
            const actions = document.getElementById("evaluationReportActions");
            let html = "";

            if (data.hasAnyData === false) {
                actions.innerHTML =
                    "<div class=\"review-section-title\"><i class=\"bi bi-folder2-open\"></i>Available Reports</div><p class=\"review-pending\">" +
                    escapeHtml(data.message || "No data found for this financial year.") + "</p>";
                return;
            }

            if (data.reports?.evaluation) {
                html +=
                    "<button class=\"review-action-btn\" onclick=\"loadReport('evaluation', '{{ $emp_id }}')\"><i class=\"bi bi-clipboard-check\"></i>Evaluation Details</button>";
            } else if (data.pendingReviews?.evaluation) {
                html += "<p class=\"review-pending\">Evaluation review is pending.</p>";
            }

            if (userRoles.includes("hr")) {
                if (data.reports?.hrReview) {
                    html +=
                        "<button class=\"review-action-btn\" onclick=\"loadReport('hrReport', '{{ $emp_id }}')\"><i class=\"bi bi-people\"></i>View HR Review</button>";
                } else if (data.pendingReviews?.hrReview) {
                    html += "<p class=\"review-pending\">HR review is pending.</p>";
                }
            }

            if (userRoles.includes("admin")) {
                if (data.reports?.adminReview) {
                    html +=
                        "<button class=\"review-action-btn\" onclick=\"loadReport('adminReport', '{{ $emp_id }}')\"><i class=\"bi bi-person-check\"></i>View Admin Review</button>";
                } else if (data.pendingReviews?.adminReview) {
                    html += "<p class=\"review-pending\">Admin review is pending.</p>";
                }
            }

            if (userRoles.includes("manager")) {
                if (data.reports?.managerReview) {
                    html +=
                        "<button class=\"review-action-btn\" onclick=\"loadReport('managerReport', '{{ $emp_id }}')\"><i class=\"bi bi-diagram-3\"></i>View Manager Review</button>";
                } else if (data.pendingReviews?.managerReview) {
                    html += "<p class=\"review-pending\">Manager review is pending.</p>";
                }
            }

            if (Array.isArray(data.clientReviews) && data.clientReviews.length > 0) {
                data.clientReviews.forEach(function(clientReview) {
                    html += "<button class=\"review-action-btn\" onclick=\"loadClientReport('" + clientReview
                        .emp_id + "', '" + clientReview.client_id +
                        "')\"><i class=\"bi bi-briefcase\"></i>View Client Review for: " + escapeHtml(clientReview
                            .client_name || "Unknown Client") + "</button>";
                });
            } else if (data.pendingReviews?.clientReview) {
                html += "<p class=\"review-pending\">Your client review is pending.</p>";
            }

            actions.innerHTML =
                "<div class=\"review-section-title\"><i class=\"bi bi-folder2-open\"></i>Available Reports</div>" + (html ||
                    "<p class=\"review-pending\">No data found for this financial year.</p>");
        }

        function hasPendingReview(data) {
            return Object.values(data.pendingReviews || {}).some(Boolean);
        }

        // Get employee ID and optionally default year from Blade variables
        const empId = {!! json_encode($users['evaluation']->emp_id ?? ($users['superAddUser']->employee_id ?? null)) !!};
        const defaultYear = {!! json_encode($users['evaluation']->financial_year ?? ($users['superAddUser']->financial_year ?? '')) !!};

        //Fetch client data 
        const userRoles = @json($userRoles);
        document.addEventListener('DOMContentLoaded', function() {

            const dropdown = document.getElementById('employeeDetails');

            function loadTableData(selectedYear) {
                const table = document.getElementById("reviewTableContainer");
                $("#reportDetails").empty();

                if (!selectedYear) {
                    table.style.display = 'none';
                    renderReportActions({
                        hasAnyData: false,
                        message: 'Please select a financial year first.'
                    });
                    return;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/employee/review-score/super-user', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            financial_year: selectedYear,
                            emp_id: empId
                        })
                    })
                    .then(response => {
                        if (response.status === 204) {
                            table.style.display = 'none';
                            return null;
                        }
                        if (!response.ok) {
                            throw new Error('Network error');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (!data) {
                            table.style.display = 'none';
                            renderReportActions({
                                hasAnyData: false,
                                message: 'No data found for this financial year.'
                            });
                            return;
                        }

                        renderReportActions(data);

                        if (data.hasAnyData === false) {
                            table.style.display = 'none';
                            return;
                        }


                        table.style.display = '';

                        function formatScore(value) {
                            const numeric = Number(value);
                            return Number.isInteger(numeric) ? numeric.toString() : numeric.toFixed(2);
                        }

                        function setCell(cellId, value, max) {
                            const cell = document.getElementById(cellId);
                            if (value !== null && value !== undefined && value !== '') {
                                cell.textContent = formatScore(value) + ' / ' + max;
                            } else {
                                cell.textContent = '';
                            }
                        }

                        // Always show total
                        setCell("totalScoreCell", data.total, 100);

                        // Hide all optional columns first
                        ["admin", "hr", "manager", "client"].forEach(role => {
                            const block = document.getElementById(role + "ScoreBlock");
                            if (block) {
                                block.style.display = 'none';
                            }
                        });

                        // Admin
                        if (userRoles.includes('admin') && data.adminTotal !== null) {
                            document.getElementById("adminScoreBlock").style.display = '';
                            setCell("adminScoreCell", data.adminTotal, 45);
                        }

                        // HR
                        if (userRoles.includes('hr') && data.hrTotal !== null) {
                            document.getElementById("hrScoreBlock").style.display = '';
                            setCell("hrScoreCell", data.hrTotal, 30);
                        }

                        // Manager
                        if (userRoles.includes('manager') && data.managerTotal !== null) {
                            document.getElementById("managerScoreBlock").style.display = '';
                            setCell("managerScoreCell", data.managerTotal, 35);
                        }

                        // Client
                        if (userRoles.includes('client') && data.showClient) {
                            document.getElementById("clientScoreBlock").style.display = '';
                            setCell("clientScoreCell", data.clientTotal, 100);
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching review scores:", error);
                        table.style.display = 'none';
                        renderReportActions({
                            hasAnyData: false,
                            message: 'No data found for this financial year.'
                        });
                    });
            }

            dropdown.addEventListener('change', function() {
                loadTableData(this.value);
            });

            if (dropdown.value) {
                loadTableData(dropdown.value);
            }
        });


        function loadClientReport(empId, clientId) {
            $('#reportDetails').empty();

            const financialYear = $('#employeeDetails').val();
            if (!financialYear) {
                $('#reportDetails').html('<p>Please select a financial year first.</p>');
                return;
            }

            const url = `/client/review/details/${empId}?client_id=${clientId}&financial_year=${financialYear}`;

            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {
                    $('#reportDetails').html(response);
                    $('#reportDetails').addClass('table-container');
                },
                error: function(xhr) {
                    const message = xhr.status === 404 ?
                        'No data found for this financial year.' :
                        'Sorry, there was an error loading the client review.';
                    $('#reportDetails').html('<p>' + message + '</p>');
                }
            });
        }
    </script>


@endsection
