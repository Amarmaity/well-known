@extends('layouts.app') <!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

@section('title', 'Super Admin | Employee Review') <!-- Page Title -->

@section('breadcrumb', "Super view / Employee {$emp_id}") <!-- Breadcrumb -->

@section('page-title', 'Super Admin Dashboard') <!-- Page Title in Breadcrumb -->

@section('body-class', 'special-page')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">


    {{-- <div class="container"> --}}
    <h2 class="heading">Employee Review Details:{{ $emp_id }}</h2>
    <div class="mt-3">
        <button onclick="history.back()" class="btn btn-secondary">Back</button>
    </div>
    <div class="col-12 col-sm-6 search-container forms-block">
        <label for="financialYear" class="forms-label">Financial Years:</label>
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

        <select id="employeeDetails" class="form-select client__select" name="financial_year" required>
            <option value="">Financial Year</option>

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

    </div>

    <div id="reviewTableContainer" style="display: none; margin-top: 20px;">
        <div class="table-container">
            <div class="table-wrapper">
                <table class="table table-bordered table-hover main-table table-view">
                    <thead>
                        <tr>
                            <!-- Initially hidden -->
                            <th id="evaluationColumnHeader">Total Evaluation Score</th>
                            <th id="adminColumnHeader">Admin Review Score</th>
                            <th id="hrColumnHeader">HR Review Score</th>
                            <th id="managerColumnHeader">Manager Review Score</th>
                            <th id="clientColumnHeader" style="display: none;">Client Review Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td id="totalScoreCell"></td>
                            <td id="adminScoreCell"></td>
                            <td id="hrScoreCell"></td>
                            <td id="managerScoreCell"></td>
                            <td id="clientScoreCell" style="display: none;"></td> <!-- Initially hidden -->
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="evaluation-report" id="evaluationReportActions">
        @php $userRoles = $user_roles ?? []; @endphp
        @if (optional($users['evaluation'])->emp_id)
            <button class="btn secondary-btn" onclick="loadReport('evaluation', '{{ $users['evaluation']->emp_id }}')">
                Evaluation Details
            </button>
        @else
            <p>Evaluation review is pending.</p>
        @endif


        @php
            $userRoles = $user_roles ?? [];
        @endphp

        {{-- Admin --}}
        @if (in_array('admin', $userRoles))
            @if (optional($users['adminReview'])->emp_id)
                <button class="btn secondary-btn"
                    onclick="loadReport('adminReport', '{{ $users['adminReview']->emp_id }}')">
                    View Admin Review
                </button>
            @else
                <p>Admin review is pending.</p>
            @endif
        @endif

        {{-- HR --}}
        @if (in_array('hr', $userRoles))
            @if (optional($users['hrReview'])->emp_id)
                <button class="btn secondary-btn" onclick="loadReport('hrReport', '{{ $users['hrReview']->emp_id }}')">
                    View HR Review
                </button>
            @else
                <p>HR review is pending.</p>
            @endif
        @endif

        {{-- Manager --}}
        @if (in_array('manager', $userRoles))
            @if (optional($users['managerReview'])->emp_id)
                <button class="btn secondary-btn"
                    onclick="loadReport('managerReport', '{{ $users['managerReview']->emp_id }}')">
                    View Manager Review
                </button>
            @else
                <p>Manager review is pending.</p>
            @endif
        @endif

        @if ($clientReviews->isNotEmpty())
            @foreach ($clientReviews as $clientReview)
                <button class="btn secondary-btn"
                    onclick="loadClientReport('{{ $clientReview->emp_id }}', '{{ $clientReview->client_id }}')">
                    View Client Review for: {{ $clientReview->client_name ?? 'Unknown Client' }}
                </button>
            @endforeach
        @elseif(in_array('client', $user_roles))
            <p>Your client review is pending.</p>
        @endif

        <!--@if ($clientReviews->isNotEmpty())-->
        <!--    @foreach ($clientReviews as $clientReview)-->
        <!--        <button class="btn secondary-btn"-->
        <!--            onclick="loadClientReport('{{ $clientReview->emp_id }}', '{{ $clientReview->client_id }}')">-->
        <!--            View Client Review for: {{ $clientReview->client_name ?? 'Unknown Client' }}-->
        <!--        </button>-->
        <!--    @endforeach-->
        <!--@elseif(in_array('client', $user_roles))-->
        <!--    <p>Your client review is pending.</p>-->
        <!--@endif-->

    </div>

    <div id="reportDetails" class="" style=""></div>

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
                        const message = xhr.status === 404
                            ? 'No data found for this financial year.'
                            : 'Sorry, there was an error loading the report.';
                        $('#reportDetails').html('<p>' + message + '</p>');
                    }
                });
            } else {
                $('#reportDetails').html('<p>Invalid report type provided.</p>');
            }
        }

        function escapeHtml(value) {
            return String(value ?? "").replace(/[&<>"'\/]/g, function(char) {
                return { "&": "&amp;", "<": "&lt;", ">": "&gt;", "\"": "&quot;", "'": "&#039;" }[char];
            });
        }

        function renderReportActions(data) {
            const actions = document.getElementById("evaluationReportActions");
            let html = "";

            if (data.hasAnyData === false) {
                actions.innerHTML = "<p>" + escapeHtml(data.message || "No data found for this financial year.") + "</p>";
                return;
            }

            if (data.reports?.evaluation) {
                html += "<button class=\"btn secondary-btn\" onclick=\"loadReport('evaluation', '{{ $emp_id }}')\">Evaluation Details</button>";
            } else if (data.pendingReviews?.evaluation) {
                html += "<p>Evaluation review is pending.</p>";
            }

            if (userRoles.includes("admin")) {
                if (data.reports?.adminReview) {
                    html += "<button class=\"btn secondary-btn\" onclick=\"loadReport('adminReport', '{{ $emp_id }}')\">View Admin Review</button>";
                } else if (data.pendingReviews?.adminReview) {
                    html += "<p>Admin review is pending.</p>";
                }
            }

            if (userRoles.includes("hr")) {
                if (data.reports?.hrReview) {
                    html += "<button class=\"btn secondary-btn\" onclick=\"loadReport('hrReport', '{{ $emp_id }}')\">View HR Review</button>";
                } else if (data.pendingReviews?.hrReview) {
                    html += "<p>HR review is pending.</p>";
                }
            }

            if (userRoles.includes("manager")) {
                if (data.reports?.managerReview) {
                    html += "<button class=\"btn secondary-btn\" onclick=\"loadReport('managerReport', '{{ $emp_id }}')\">View Manager Review</button>";
                } else if (data.pendingReviews?.managerReview) {
                    html += "<p>Manager review is pending.</p>";
                }
            }

            if (Array.isArray(data.clientReviews) && data.clientReviews.length > 0) {
                data.clientReviews.forEach(function(clientReview) {
                    html += "<button class=\"btn secondary-btn\" onclick=\"loadClientReport('" + clientReview.emp_id + "', '" + clientReview.client_id + "')\">View Client Review for: " + escapeHtml(clientReview.client_name || "Unknown Client") + "</button>";
                });
            } else if (data.pendingReviews?.clientReview) {
                html += "<p>Your client review is pending.</p>";
            }

            actions.innerHTML = html || "<p>No data found for this financial year.</p>";
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
                    renderReportActions({ hasAnyData: false, message: 'Please select a financial year first.' });
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
                            renderReportActions({ hasAnyData: false, message: 'No data found for this financial year.' });
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
                            document.getElementById(role + "ColumnHeader").style.display = 'none';
                            document.getElementById(role + "ScoreCell").style.display = 'none';
                        });

                        // Admin
                        if (userRoles.includes('admin') && data.adminTotal !== null) {
                            document.getElementById("adminColumnHeader").style.display = '';
                            document.getElementById("adminScoreCell").style.display = '';
                            setCell("adminScoreCell", data.adminTotal, 45);
                        }

                        // HR
                        if (userRoles.includes('hr') && data.hrTotal !== null) {
                            document.getElementById("hrColumnHeader").style.display = '';
                            document.getElementById("hrScoreCell").style.display = '';
                            setCell("hrScoreCell", data.hrTotal, 30);
                        }

                        // Manager
                        if (userRoles.includes('manager') && data.managerTotal !== null) {
                            document.getElementById("managerColumnHeader").style.display = '';
                            document.getElementById("managerScoreCell").style.display = '';
                            setCell("managerScoreCell", data.managerTotal, 35);
                        }

                        // Client
                        if (userRoles.includes('client') && data.showClient) {
                            document.getElementById("clientColumnHeader").style.display = '';
                            document.getElementById("clientScoreCell").style.display = '';
                            setCell("clientScoreCell", data.clientTotal, 100);
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching review scores:", error);
                        table.style.display = 'none';
                        renderReportActions({ hasAnyData: false, message: 'No data found for this financial year.' });
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
                    const message = xhr.status === 404
                        ? 'No data found for this financial year.'
                        : 'Sorry, there was an error loading the client review.';
                    $('#reportDetails').html('<p>' + message + '</p>');
                }
            });
        }
    </script>


@endsection
