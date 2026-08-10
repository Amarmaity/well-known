@extends('layouts.app')

@section('title', 'Appraisal Done')
@section('breadcrumb', 'Appraisal Done')
@section('page-title', 'Financial Section')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $formatPercent = static function ($value) {
            if ($value === null || $value === '') {
                return '-';
            }

            if (is_string($value) && str_contains($value, '%')) {
                return $value;
            }

            if (!is_numeric($value)) {
                return $value;
            }

            $number = (float) $value;
            $formatted =
                floor($number) === $number
                    ? number_format($number, 0)
                    : rtrim(rtrim(number_format($number, 2, '.', ''), '0'), '.');

            return $formatted . '%';
        };
        $hasReviewRole = static function ($financial, string $role) {
            $roles = json_decode($financial->employee?->user_roles ?? '[]', true);
            $roles = is_array($roles) ? $roles : [];

            return in_array($role, $roles, true);
        };
        $formatReviewPercent = static function ($financial, string $role, $value) use ($formatPercent, $hasReviewRole) {
            return $hasReviewRole($financial, $role) ? $formatPercent($value) : '-';
        };
    @endphp

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <link
            href="{{ asset('css/appraisal-done-management.css') }}?v={{ filemtime(public_path('css/appraisal-done-management.css')) }}"
            rel="stylesheet">
        <link href="{{ asset('css/appraisal-done-extra.css') }}?v={{ filemtime(public_path('css/appraisal-done-extra.css')) }}"
            rel="stylesheet">
    @endpush

    <div class="emp-page appraisal-done-page">
        <div class="emp-shell">
            <div class="emp-header">
                <div class="emp-header-text">
                    <h1>Appraisal Done</h1>
                    <p>Review saved appraisal and salary records across completed financial years.</p>
                </div>

                <div class="emp-header-actions">
                    <div class="emp-search">
                        <input type="text" id="employee_search" name="search" placeholder="Search employee, ID, year..."
                            aria-label="Search">
                        <i class="bi bi-search"></i>
                    </div>
                </div>
            </div>

            <input type="hidden" name="emp_id" id="selectedEmpId">

            <div class="emp-card">
                <div class="emp-table-scroll">
                    <table class="emp-table financial-table" id="employeeDetails">
                        <thead>
                            <tr>
                                <th style="width:260px;">Employee</th>
                                <th>Employee ID</th>
                                <th>Evaluation Score</th>
                                <th>HR Review</th>
                                <th>Admin Review</th>
                                <th>Manager Review</th>
                                <th>Client Review</th>
                                <th>Appraisal Score</th>
                                <th>Current Salary</th>
                                <th>Percentage</th>
                                <th>Financial Year</th>
                                <th>Increment Amount</th>
                                <th>Final Salary</th>
                                <th>Appraisal Date</th>
                            </tr>
                        </thead>
                        <tbody id="employeeList" data-search-url="{{ route('super.user.search.bar') }}">
                            @forelse ($financialData as $financial)
                                @php
                                    $employeeName = $financial->employee_name ?? '-';
                                    $initials = strtoupper(
                                        collect(explode(' ', trim($employeeName)))
                                            ->filter()
                                            ->take(2)
                                            ->map(fn($part) => substr($part, 0, 1))
                                            ->implode(''),
                                    );

                                    if ($initials == '') {
                                        $initials = 'U';
                                    }

                                    $avatarPalette = [
                                        '#6c8bf5',
                                        '#5cb896',
                                        '#e0995f',
                                        '#c97fd1',
                                        '#5fb0c9',
                                        '#e08a8a',
                                        '#8f8ff0',
                                        '#7bbf6a',
                                    ];
                                    $avatarColor = $avatarPalette[crc32($employeeName ?: 'U') % count($avatarPalette)];
                                @endphp

                                <tr class="emp-row">
                                    <td>
                                        <div class="emp-person">
                                            <div class="emp-avatar" style="background:{{ $avatarColor }};">
                                                {{ $initials }}
                                            </div>
                                            <div>
                                                <div class="emp-person-name">{{ $employeeName }}</div>
                                                <div class="emp-person-meta">{{ $financial->financial_year ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="appraisal-done-muted">{{ $financial->emp_id }}</span></td>
                                    <td><span
                                            class="appraisal-done-score">{{ $formatPercent($financial->evaluation_score) }}</span>
                                    </td>
                                    <td><span
                                            class="appraisal-done-score">{{ $formatReviewPercent($financial, 'hr', $financial->hr_review) }}</span>
                                    </td>
                                    <td><span
                                            class="appraisal-done-score">{{ $formatReviewPercent($financial, 'admin', $financial->admin_review) }}</span>
                                    </td>
                                    <td><span
                                            class="appraisal-done-score">{{ $formatReviewPercent($financial, 'manager', $financial->manager_review) }}</span>
                                    </td>
                                    <td><span
                                            class="appraisal-done-score">{{ $formatReviewPercent($financial, 'client', $financial->clint_review) }}</span>
                                    </td>
                                    <td><span
                                            class="appraisal-done-score">{{ $formatPercent($financial->apprisal_score) }}</span>
                                    </td>
                                    <td><span class="appraisal-done-money">{{ $financial->current_salary ?? '-' }}</span>
                                    </td>
                                    <td><span
                                            class="appraisal-done-score">{{ $formatPercent($financial->percentage_given) }}</span>
                                    </td>
                                    <td><span class="appraisal-done-muted">{{ $financial->financial_year ?? '-' }}</span>
                                    </td>
                                    <td><span class="appraisal-done-money">{{ $financial->update_salary ?? '-' }}</span>
                                    </td>
                                    <td><span class="appraisal-done-money">{{ $financial->final_salary ?? '-' }}</span>
                                    </td>
                                    <td><span class="appraisal-done-muted">{{ $financial->apprisal_date ?? '-' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr id="appraisalDoneEmpty">
                                    <td colspan="14">
                                        <div class="appraisal-done-message">
                                            <i class="bi bi-clipboard-data"></i>
                                            <h5>No appraisal done yet</h5>
                                            <p>Completed appraisal records will appear here.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                            @if ($financialData->count() > 0)
                                <tr id="appraisalDoneNoResults" hidden>
                                    <td colspan="14">
                                        <div class="appraisal-done-message">
                                            <i class="bi bi-search"></i>
                                            <h5>No matching appraisal records</h5>
                                            <p>Try a different search term.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                @if ($financialData->count() > 0)
                    <div class="emp-footer" id="appraisalDoneFooter">
                        <div class="emp-footer-count" id="appraisalDonePaginationInfo"></div>
                        <div class="emp-pagination" id="appraisalDonePagination"></div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('js/appraisal-done.js') }}?v={{ filemtime(public_path('js/appraisal-done.js')) }}"></script>
    @endpush
@endsection
