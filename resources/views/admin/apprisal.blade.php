@extends('layouts.app')

@section('title', 'Appraisal Dashboard')
@section('breadcrumb', 'Appraisal Table')
@section('page-title', 'Appraisal Section')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <link href="{{ asset('css/appraisal-management.css') }}?v={{ filemtime(public_path('css/appraisal-management.css')) }}" rel="stylesheet">
        <link href="{{ asset('css/appraisal-extra.css') }}?v={{ filemtime(public_path('css/appraisal-extra.css')) }}" rel="stylesheet">
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

    <div class="emp-page appraisal-page">
        <div class="emp-shell">
            <div class="emp-header">
                <div class="emp-header-text">
                    <h1>Appraisal Table</h1>
                    <p>Search an employee and review appraisal scores for the selected financial year.</p>
                </div>

                <div class="emp-header-actions appraisal-control">
                    <select id="financialYear" class="form-select appraisal-year-select" name="financial_year" required>
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
                        <input type="text" id="employee_search" name="search"
                            placeholder="Search employee ID or name" aria-label="Search">
                        <i class="bi bi-search"></i>
                    </div>
                </div>
            </div>

            <input type="hidden" name="emp_id" id="selectedEmpId">

            <div class="emp-card">
                <div class="emp-table-scroll">
                    <table class="emp-table" id="appraisalTable">
                        <thead id="table-header">
                            <tr>
                                <th style="width:260px;">Employee</th>
                                <th>Evaluation Score</th>
                                <th id="hr-column-header" style="display: none;">HR Review</th>
                                <th id="admin-column-header" style="display: none;">Admin Review</th>
                                <th id="manager-column-header" style="display: none;">Manager Review</th>
                                <th id="client-column-header" style="display: none;">Client Review</th>
                                <th>Appraisal Score</th>
                            </tr>
                        </thead>
                        <tbody id="appraisal-body">
                            <tr>
                                <td colspan="7">
                                    <div class="appraisal-message">
                                        <i class="bi bi-search"></i>
                                        <h5>Search for an employee</h5>
                                        <p>Enter an employee ID or name to view appraisal data.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            $(document).ready(function() {
                let debounceTimer;

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

                function renderMessage(icon, title, message) {
                    $('#appraisal-body').html(`
                        <tr>
                            <td colspan="7">
                                <div class="appraisal-message">
                                    <i class="bi ${icon}"></i>
                                    <h5>${escapeHtml(title)}</h5>
                                    <p>${escapeHtml(message)}</p>
                                </div>
                            </td>
                        </tr>
                    `);
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

                function getAppraisalStatus(score) {
                    if (score >= 80) {
                        return {
                            label: 'Excellent',
                            className: 'is-excellent'
                        };
                    }

                    if (score >= 60) {
                        return {
                            label: 'Good',
                            className: 'is-good'
                        };
                    }

                    return {
                        label: 'Needs Improvement',
                        className: 'is-needs'
                    };
                }

                function scoreCell(value) {
                    return `<span class="appraisal-score">${Number(value).toFixed(2)}%</span>`;
                }

                function fetchEmployeeData() {
                    const employeeQuery = $('#employee_search').val().trim();
                    const financialYear = $('#financialYear').val().trim();

                    if (!financialYear) {
                        renderMessage('bi-calendar-x', 'Select a financial year', 'Please select a valid financial year.');
                        return;
                    }

                    if (!employeeQuery) {
                        renderMessage('bi-search', 'Search for an employee', 'Enter an employee ID or name to view appraisal data.');
                        return;
                    }

                    renderMessage('bi-hourglass-split', 'Loading appraisal data', 'Please wait while the scores are loaded.');

                    $.ajax({
                        url: "/apprisal-data",
                        method: "GET",
                        data: {
                            employee_query: employeeQuery,
                            financial_year: financialYear
                        },
                        success: function(response) {
                            let rows = '';
                            const hasPercentageData = (values) => Array.isArray(values)
                                ? values.some((value) => Number(value) > 0)
                                : Number(values) > 0;

                            const showHr = hasPercentageData(response.hrReviewData);
                            const showAdmin = hasPercentageData(response.adminReviewData);
                            const showManager = hasPercentageData(response.managerReviewData);
                            const showClient = hasPercentageData(response.clientReviewData);

                            $('#client-column-header').toggle(showClient);
                            $('#manager-column-header').toggle(showManager);
                            $('#hr-column-header').toggle(showHr);
                            $('#admin-column-header').toggle(showAdmin);

                            if (response.status === 'error') {
                                renderMessage('bi-exclamation-circle', 'No appraisal data', response.message);
                                return;
                            }

                            const maxLength = Math.max(
                                response.adminReviewData?.length || 0,
                                response.hrReviewData?.length || 0,
                                response.managerReviewData?.length || 0,
                                response.clientReviewData?.length || 0,
                                response.evaluationScore?.length || 0
                            );

                            if (maxLength === 0) {
                                renderMessage('bi-clipboard-x', 'No scores found', 'No appraisal scores are available for this employee and financial year.');
                                return;
                            }

                            for (let i = 0; i < maxLength; i++) {
                                const name = response.employee_name || 'N/A';
                                const safeName = escapeHtml(name);
                                const initials = escapeHtml(getInitials(name));
                                const avatarColor = getAvatarColor(name);

                                const evaluation = isNaN(Number(response.evaluationScore?.[i])) ? 0 :
                                    Number(response.evaluationScore[i]);
                                const hr = isNaN(Number(response.hrReviewData?.[i])) ? 0 : Number(response
                                    .hrReviewData[i]);
                                const admin = isNaN(Number(response.adminReviewData?.[i])) ? 0 : Number(
                                    response.adminReviewData[i]);
                                const manager = showManager ? (isNaN(Number(response.managerReviewData?.[
                                    i
                                ])) ? 0 : Number(response.managerReviewData[i])) : 0;
                                const client = showClient ? (isNaN(Number(response.clientReviewData?.[i])) ?
                                    0 : Number(response.clientReviewData[i])) : 0;

                                const appraisalScore = Number(response.appraisal_score) || 0;
                                const status = getAppraisalStatus(appraisalScore);

                                rows += `
                                    <tr class="emp-row">
                                        <td>
                                            <div class="emp-person">
                                                <div class="emp-avatar" style="background:${avatarColor};">${initials}</div>
                                                <div>
                                                    <div class="emp-person-name">${safeName}</div>
                                                    <div class="emp-person-meta">${escapeHtml(financialYear)}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>${scoreCell(evaluation)}</td>
                                        ${showHr ? `<td>${scoreCell(hr)}</td>` : ''}
                                        ${showAdmin ? `<td>${scoreCell(admin)}</td>` : ''}
                                        ${showManager ? `<td>${scoreCell(manager)}</td>` : ''}
                                        ${showClient ? `<td>${scoreCell(client)}</td>` : ''}
                                        <td>
                                            <span class="appraisal-pill ${status.className}">${status.label} (${appraisalScore.toFixed(2)}%)</span>
                                        </td>
                                    </tr>`;
                            }

                            $('#appraisal-body').html(rows);
                        },

                        error: function(xhr) {
                            const errorMsg = xhr.responseJSON?.message || 'Error fetching data';
                            renderMessage('bi-exclamation-circle', 'Unable to load appraisal data', errorMsg);
                            console.error("Error fetching data", xhr.responseJSON);
                        }
                    });
                }

                $('#employee_search').on('keyup', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(fetchEmployeeData, 300);
                });

                $('#financialYear').on('change', function() {
                    fetchEmployeeData();
                });
            });
        </script>
    @endpush
@endsection
