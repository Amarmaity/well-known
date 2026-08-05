@extends('layouts.app')

@section('title', 'User Review Report')

@section('breadcrumb', "User Review Report / Employee $emp_id")

@section('page-title', 'User Review Report')

@section('body-class', 'special-page')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <link href="{{ asset('css/review-management.css') }}?v={{ filemtime(public_path('css/review-management.css')) }}" rel="stylesheet">
        <link href="{{ asset('css/review-details.css') }}?v={{ filemtime(public_path('css/review-details.css')) }}" rel="stylesheet">
    @endpush

    <div class="emp-page review-detail-page">
        <div class="emp-shell">
            <div class="review-detail-header">
                <div class="emp-header-text">
                    <h1>User Review Report</h1>
                    <p>Employee ID: <strong>{{ $emp_id }}</strong></p>
                </div>

                <button type="button" onclick="history.back()" class="review-back-btn">
                    <i class="bi bi-arrow-left"></i>
                    Back
                </button>
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

                $years = [
                    $currentFYStart - 1, // Previous FY
                    $currentFYStart, // Current FY
                    $currentFYStart + 1, // Next FY
                    $currentFYStart + 2, // Next +1 FY
                ];
            @endphp

                <select id="employeeDetails" class="form-select review-year-select" name="financial_year" required>
                    <option value="" selected disabled>Financial Year</option>

                    @foreach ($years as $year)
                        @php
                            $end = $year + 1;
                            $fy = $year . '-' . $end;
                        @endphp

                        <option value="{{ $fy }}" {{ ($selectedFinancialYear ?? '') === $fy ? 'selected' : '' }}>
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
                    <div class="review-score-item" id="totalScoreBlock">
                        <div class="review-score-label" id="evaluationColumnHeader">Total Evaluation Score</div>
                        <div class="review-score-value" id="totalScoreCell"></div>
                    </div>

                    <div class="review-score-item" id="adminScoreBlock">
                        <div class="review-score-label" id="adminColumnHeader">Admin Review Score</div>
                        <div class="review-score-value" id="adminScoreCell"></div>
                    </div>

                    <div class="review-score-item" id="hrScoreBlock">
                        <div class="review-score-label" id="hrColumnHeader">HR Review Score</div>
                        <div class="review-score-value" id="hrScoreCell"></div>
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
                @if ($userData['evaluation'] !== null)
                    <button class="review-action-btn" onclick="loadReport('evaluation', '{{ $emp_id }}')">
                        <i class="bi bi-clipboard-check"></i>
                        Evaluation Details
                    </button>
                @elseif($pendingReviews['evaluation'] ?? false)
                    <p class="review-pending">Review your self first.</p>
                @endif

                @if ($userData['adminReview'] !== null)
                    <button class="review-action-btn" onclick="loadReport('adminReport', '{{ $emp_id }}')">
                        <i class="bi bi-person-check"></i>
                        Admin Report
                    </button>
                @elseif($pendingReviews['adminReview'] ?? false)
                    <p class="review-pending">Admin review is pending.</p>
                @endif

                @if ($userData['hrReview'] !== null)
                    <button class="review-action-btn" onclick="loadReport('hrReport', '{{ $emp_id }}')">
                        <i class="bi bi-people"></i>
                        HR Report
                    </button>
                @elseif($pendingReviews['hrReview'] ?? false)
                    <p class="review-pending">HR review is pending.</p>
                @endif

                @if ($userData['managerReview'] !== null)
                    <button class="review-action-btn" onclick="loadReport('managerReport', '{{ $emp_id }}')">
                        <i class="bi bi-diagram-3"></i>
                        Manager Report
                    </button>
                @elseif($pendingReviews['managerReview'] ?? false)
                    <p class="review-pending">Manager review is pending.</p>
                @endif

                {{-- @if ($userData['clientReview'] !== null)
                <div class="client-report">
                    <button class="review-action-btn" onclick="loadReport('clientReport', '{{ $emp_id }}')">Client
                        Report</button>
                </div>
                @endif --}}

                @if($clientReviews->isNotEmpty())
                    @foreach($clientReviews as $clientReview)
                        <button class="review-action-btn"
                            onclick="loadClientReport('{{ $clientReview->emp_id }}', '{{ $clientReview->client_id }}')">
                            <i class="bi bi-briefcase"></i>
                            View Client Review for: {{ $clientReview->client_name ?? 'Unknown Client' }}
                        </button>
                    @endforeach
                @elseif($pendingReviews['clientReview'] ?? false)
                    <p class="review-pending">Client review is pending.</p>
                @endif

            </div>

            <div id="reportDetails" class="review-loaded-report"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        function loadReport(reportType, empId) {
            $('#reportDetails').empty();

            const financialYear = $('#employeeDetails').val();
            if (!financialYear) {
                $('#reportDetails').html('<p>Please select a financial year first.</p>');
                return;
            }

            let url = '';
            switch (reportType) {
                case 'evaluation': url = `/evaluation/details/${empId}`; break;
                case 'managerReport': url = `/manager/report/${empId}`; break;
                case 'adminReport': url = `/admin/report/${empId}`; break;
                case 'hrReport': url = `/hr/report/${empId}`; break;
                case 'clientReport': url = `/client/report/${empId}`; break;
                default: console.error('Unknown report type'); return;
            }

            $.ajax({
                url: url,
                method: 'GET',
                data: {
                    financial_year: financialYear,
                    emp_id: empId,
                    employee_id: empId
                },
                success: function (response) {
                    $('#reportDetails').html(response).addClass('table-container');
                },
                error: function (xhr) {
                    const message = xhr.status === 404
                        ? 'No data found for this financial year.'
                        : 'Sorry, there was an error loading the report.';
                    $('#reportDetails').html('<p>' + message + '</p>');
                }
            });
        }

        function escapeHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, function (char) {
                return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[char];
            });
        }

        function renderReportActions(data) {
            const actions = document.getElementById('evaluationReportActions');
            let html = '';

            if (data.hasAnyData === false) {
                actions.innerHTML = '<div class="review-section-title"><i class="bi bi-folder2-open"></i>Available Reports</div><p class="review-pending">' + escapeHtml(data.message || "No data found for this financial year.") + "</p>";
                return;
            }

            if (data.reports?.evaluation) {
                html += `<button class="review-action-btn" onclick="loadReport('evaluation', '{{ $emp_id }}')"><i class="bi bi-clipboard-check"></i>Evaluation Details</button>`;
            } else if (data.pendingReviews?.evaluation) {
                html += '<p class="review-pending">Review your self first.</p>';
            }

            if (data.reports?.adminReview) {
                html += `<button class="review-action-btn" onclick="loadReport('adminReport', '{{ $emp_id }}')"><i class="bi bi-person-check"></i>Admin Report</button>`;
            } else if (data.pendingReviews?.adminReview) {
                html += '<p class="review-pending">Admin review is pending.</p>';
            }

            if (data.reports?.hrReview) {
                html += `<button class="review-action-btn" onclick="loadReport('hrReport', '{{ $emp_id }}')"><i class="bi bi-people"></i>HR Report</button>`;
            } else if (data.pendingReviews?.hrReview) {
                html += '<p class="review-pending">HR review is pending.</p>';
            }

            if (data.reports?.managerReview) {
                html += `<button class="review-action-btn" onclick="loadReport('managerReport', '{{ $emp_id }}')"><i class="bi bi-diagram-3"></i>Manager Report</button>`;
            } else if (data.pendingReviews?.managerReview) {
                html += '<p class="review-pending">Manager review is pending.</p>';
            }

            if (Array.isArray(data.clientReviews) && data.clientReviews.length > 0) {
                data.clientReviews.forEach(function (clientReview) {
                    html += `<button class="review-action-btn" onclick="loadClientReport('${clientReview.emp_id}', '${clientReview.client_id}')"><i class="bi bi-briefcase"></i>View Client Review for: ${escapeHtml(clientReview.client_name || 'Unknown Client')}</button>`;
                });
            } else if (data.pendingReviews?.clientReview) {
                html += '<p class="review-pending">Client review is pending.</p>';
            }

            actions.innerHTML = '<div class="review-section-title"><i class="bi bi-folder2-open"></i>Available Reports</div>' + (html || '<p class="review-pending">No review data found for this financial year.</p>');
        }

        document.getElementById('employeeDetails').addEventListener('change', function () {
            const selectedYear = this.value;
            const table = document.getElementById('reviewTableContainer');
            const empId = "{{ $emp_id }}";

            $('#reportDetails').empty();

            if (!selectedYear) {
                table.style.display = 'none';
                renderReportActions({});
                return;
            }

            fetch(`/employee/review-scores?financial_year=${selectedYear}&emp_id=${empId}&employee_id=${empId}`)
                .then(response => {
                    if (response.status === 204) {
                        table.style.display = 'none';
                        return null;
                    }
                    if (!response.ok) throw new Error('Network error');
                    return response.json();
                })
                .then(data => {
                    if (!data) {
                        table.style.display = 'none';
                        renderReportActions({});
                        return;
                    }

                    renderReportActions(data);

                    const totalCell = document.getElementById("totalScoreCell");
                    const adminCell = document.getElementById("adminScoreCell");
                    const hrCell = document.getElementById("hrScoreCell");
                    const managerCell = document.getElementById("managerScoreCell");
                    const clientCell = document.getElementById("clientScoreCell");

                    const totalBlock = document.getElementById("totalScoreBlock");
                    const adminBlock = document.getElementById("adminScoreBlock");
                    const hrBlock = document.getElementById("hrScoreBlock");
                    const managerBlock = document.getElementById("managerScoreBlock");
                    const clientBlock = document.getElementById("clientScoreBlock");

                    function formatScore(value) {
                        const numeric = Number(value);
                        return Number.isInteger(numeric) ? numeric.toString() : numeric.toFixed(2);
                    }

                    function set(cell, block, val, max) {
                        if (val !== null && val !== undefined && val !== '') {
                            cell.textContent = `${formatScore(val)} / ${max}`;
                            block.style.display = '';
                            return true;
                        } else {
                            cell.textContent = '';
                            block.style.display = 'none';
                            return false;
                        }
                    }

                    const hasTotal = set(totalCell, totalBlock, data.total, 100);
                    const hasAdmin = set(adminCell, adminBlock, data.admin, 45);
                    const hasHr = set(hrCell, hrBlock, data.hr, 30);
                    const hasManager = set(managerCell, managerBlock, data.managerTotal, 35);

                    let hasClient = false;
                    if (data.showClient) {
                        hasClient = set(clientCell, clientBlock, data.clientTotal, 100);
                    } else {
                        clientCell.textContent = '';
                        clientBlock.style.display = 'none';
                    }

                    const anyData = hasTotal || hasAdmin || hasHr || hasManager || hasClient;
                    table.style.display = anyData ? '' : 'none';
                })
                .catch(error => {
                    console.error("Error fetching review scores:", error);
                    table.style.display = 'none';
                    renderReportActions({});
                });
        });

        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('employeeDetails').dispatchEvent(new Event('change'));
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
                success: function (response) {
                    $('#reportDetails').html(response);
                    $('#reportDetails').addClass('table-container');
                },
                error: function (xhr) {
                    const message = xhr.status === 404
                        ? 'No data found for this financial year.'
                        : 'Sorry, there was an error loading the client review.';
                    $('#reportDetails').html('<p>' + message + '</p>');
                }
            });
        }

    </script>

@endsection
