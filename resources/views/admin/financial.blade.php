@extends('layouts.app')
@section('title', 'Financial Dashboard')
@section('breadcrumb', 'Financial')
@section('page-title', 'Financial-Section')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <link href="{{ asset('css/financial-management.css') }}?v={{ filemtime(public_path('css/financial-management.css')) }}"
            rel="stylesheet">
        <link href="{{ asset('css/financial-extra.css') }}?v={{ filemtime(public_path('css/financial-extra.css')) }}"
            rel="stylesheet">
    @endpush

    @php
        $currentMonth = date('m');
        $currentYear = date('Y');

        if ($currentMonth < 4) {
            $currentFYStart = $currentYear - 1;
        } else {
            $currentFYStart = $currentYear;
        }

        $years = [$currentFYStart - 1, $currentFYStart, $currentFYStart + 1, $currentFYStart + 2];
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
                    enctype="multipart/form-data"
                    data-financial-data-url="{{ route('financial.data') }}"
                    data-financial-store-url="{{ route('financial-data-store') }}">
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

        <script src="{{ asset('js/financial.js') }}?v={{ filemtime(public_path('js/financial.js')) }}"></script>
    @endpush
@endsection
