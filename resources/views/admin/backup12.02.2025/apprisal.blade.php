@extends('layouts.app')

@section('title', 'Appraisal Dashboard')
@section('breadcrumb', 'Appraisal Table')
@section('page-title', 'Appraisal Section')

@section('content')

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        .search-container {
            display: flex;
            justify-content: space-between;
            padding: 10px;
        }

        .search-container input {
            padding: 5px;
            width: 200px;
        }

        .client {
            margin-bottom: 20px;
        }

        .client__heading {
            font-size: 24px;
            font-weight: bold;
        }

        .client__select,
        .client__search {
            margin-right: 10px;
        }

        .client___item {
            display: flex;
            align-items: center;
        }

        .client__btn {
            background: none;
            border: none;
            padding: 0;
        }
    </style>

    <div class="client">
        <h1 class="client__heading">Appraisal Table</h1>
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

        <div class="client___item">
            <input type="search" id="employee_search" name="search" class="form-control client__search"
                placeholder="Search" aria-label="Search">
            <button class="client__btn" type="submit">
                <img src="{{ asset('images/search.png') }}" alt="Search">
            </button>
        </div>
        <input type="hidden" name="emp_id" id="selectedEmpId">
    </div>

    <div class="container table-container appraisal-page">
        <div class="table-wrapper">
            <table class="table table-bordered table-hover main-table table-view apprisal-table">
                <thead id="table-header">
                    <tr>
                        <th>Employee Name</th>
                        <th>Evaluation Score (%)</th>
                        <th id="hr-column-header" style="display: none;">HR Review (%)</th>
                        <th id="admin-column-header" style="display: none;">Admin Review (%)</th>
                        <th id="manager-column-header" style="display: none;">Manager Review (%)</th>
                        <th id="client-column-header" style="display: none;">Client Review (%)</th>
                        <th>Appraisal Score (%)</th>
                    </tr>
                </thead>
                <tbody id="appraisal-body">
                    <tr>
                        <td colspan="7">Enter Employee ID or Name to view data.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            let debounceTimer;

            function fetchEmployeeData() {
                const employeeQuery = $('#employee_search').val().trim();
                const financialYear = $('#financialYear').val().trim();

                if (!financialYear) {
                    $('#appraisal-body').html(
                        '<tr><td colspan="7">Please select a valid financial year.</td></tr>');
                    return;
                }

                if (!employeeQuery) {
                    $('#appraisal-body').html(
                        '<tr><td colspan="7">Enter Employee ID or Name to view data.</td></tr>');
                    return;
                }

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
                            $('#appraisal-body').html(
                                `<tr><td colspan="7">${response.message}</td></tr>`);
                            return;
                        }

                        const maxLength = Math.max(
                            response.adminReviewData?.length || 0,
                            response.hrReviewData?.length || 0,
                            response.managerReviewData?.length || 0,
                            response.clientReviewData?.length || 0,
                            response.evaluationScore?.length || 0
                        );

                        for (let i = 0; i < maxLength; i++) {
                            const name = response.employee_name || 'N/A';

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

                            const scores = [evaluation]
                                .concat(showHr ? [hr] : [])
                                .concat(showAdmin ? [admin] : [])
                                .concat(showManager ? [manager] : [])
                                .concat(showClient ? [client] : []);

                            const total = scores.reduce((sum, val) => sum + val, 0);
                            const average = scores.length ? total / scores.length : 0;
                            const status = average >= 80 ? 'Excellent' : average >= 60 ? 'Good' :
                                'Needs Improvement';

                            rows += `
                <tr>
                    <td>${name}</td>
                    <td>${evaluation.toFixed(2)}%</td>
                    ${showHr ? `<td>${hr.toFixed(2)}%</td>` : ''}
                    ${showAdmin ? `<td>${admin.toFixed(2)}%</td>` : ''}
                    ${showManager ? `<td>${manager.toFixed(2)}%</td>` : ''}
                    ${showClient ? `<td>${client.toFixed(2)}%</td>` : ''}
                    <td>${status} (${average.toFixed(2)}%)</td>
                </tr>`;
                        }

                        $('#appraisal-body').html(rows);
                    },


                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON?.message || 'Error fetching data';
                        $('#appraisal-body').html(`<tr><td colspan="7">${errorMsg}</td></tr>`);
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

@endsection
