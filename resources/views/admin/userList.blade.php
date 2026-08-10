@extends('layouts.app')

@section('title', 'User Management')
@section('breadcrumb', 'User Listing')
@section('page-title', 'Apprisal-Section')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

        <style>
            /* =====================================================
                    EMPLOYEE MANAGEMENT — SCOPED DESIGN SYSTEM
                    All rules are namespaced under .emp-page to avoid
                    leaking into / colliding with global layout styles.
                    ===================================================== */

            .emp-page {
                --emp-primary: #3b5bdb;
                --emp-primary-soft: #eef1fd;
                --emp-primary-dark: #2f46ad;

                --emp-success: #1a7f4e;
                --emp-success-soft: #e9f7f0;

                --emp-danger: #c92a2a;
                --emp-danger-soft: #fdeeee;

                --emp-ink-900: #14161f;
                --emp-ink-700: #3f4354;
                --emp-ink-500: #6b7080;
                --emp-ink-300: #a4a8b5;

                --emp-line: #e7e8ee;
                --emp-line-soft: #f0f1f5;
                --emp-surface: #ffffff;
                --emp-canvas: #f6f7fb;

                --emp-radius-sm: 6px;
                --emp-radius-md: 10px;
                --emp-radius-lg: 14px;

                font-family: 'Inter', system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
                color: var(--emp-ink-700);
                -webkit-font-smoothing: antialiased;
            }

            .emp-page * {
                box-sizing: border-box;
            }

            .emp-page .emp-shell {
                max-width: 1440px;
                margin: 0 auto;
            }

            /* ---------- Compact header ---------- */

            .emp-page .emp-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 20px;
                padding: 16px 4px 18px;
                flex-wrap: wrap;
            }

            .emp-page .emp-header-text h1 {
                font-size: 1.28rem;
                font-weight: 650;
                letter-spacing: -0.01em;
                color: var(--emp-ink-900);
                margin: 0;
                line-height: 1.25;
            }

            .emp-page .emp-header-text p {
                font-size: .8125rem;
                color: var(--emp-ink-500);
                margin: 2px 0 0;
            }

            .emp-page .emp-header-actions {
                display: flex;
                align-items: center;
                gap: 10px;
                flex-wrap: wrap;
            }

            .emp-page .emp-search {
                position: relative;
                width: 300px;
            }

            .emp-page .emp-search i {
                position: absolute;
                left: 13px;
                top: 50%;
                transform: translateY(-50%);
                font-size: 14px;
                color: var(--emp-ink-300);
                pointer-events: none;
                transition: color .15s ease;
            }

            .emp-page .emp-search input {
                width: 100%;
                height: 38px;
                border-radius: 999px;
                border: 1px solid var(--emp-line);
                background: var(--emp-surface);
                padding: 0 14px 0 36px;
                font-size: .8375rem;
                color: var(--emp-ink-900);
                outline: none;
                transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
            }

            .emp-page .emp-search input::placeholder {
                color: var(--emp-ink-300);
            }

            .emp-page .emp-search input:focus {
                border-color: var(--emp-primary);
                background: var(--emp-surface);
                box-shadow: 0 0 0 3px rgba(59, 91, 219, .12);
            }

            .emp-page .emp-search input:focus+i {
                color: var(--emp-primary);
            }

            .emp-page .emp-add-btn {
                height: 38px;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 0 15px;
                border-radius: var(--emp-radius-md);
                background: #274167CF;
                color: #fff;
                border: 1px solid #274167CF;
                font-size: .8125rem;
                font-weight: 600;
                text-decoration: none;
                white-space: nowrap;
                transition: background .15s ease, transform .05s ease;
            }

            .emp-page .emp-add-btn:hover {
                background: var(--bs-teal);
                color: #fff;
            }

            .emp-page .emp-add-btn:active {
                transform: translateY(1px);
            }

            .emp-page .emp-add-btn i {
                font-size: 14px;
            }

            .emp-page .emp-card {
                background: var(--emp-surface);
                border: 1px solid var(--emp-line);
                border-radius: var(--emp-radius-lg);
                overflow: hidden;
            }

            .emp-page .emp-table-scroll {
                overflow-x: auto;
                scrollbar-width: thin;
                scrollbar-color: #d3d5e0 transparent;
            }

            .emp-page .emp-table-scroll::-webkit-scrollbar {
                height: 8px;
            }

            .emp-page .emp-table-scroll::-webkit-scrollbar-thumb {
                background: #d3d5e0;
                border-radius: 999px;
            }

            .emp-page .emp-table-scroll::-webkit-scrollbar-track {
                background: transparent;
            }

            .emp-page table.emp-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
                min-width: 1080px;
            }

            .emp-page table.emp-table thead th {
                position: sticky;
                top: 0;
                z-index: 2;
                background: var(--emp-canvas);
                border-bottom: 1px solid var(--emp-line);
                text-transform: uppercase;
                font-size: .68rem;
                letter-spacing: .05em;
                font-weight: 700;
                color: var(--emp-ink-500);
                padding: 11px 16px;
                white-space: nowrap;
                text-align: left;
            }

            .emp-page table.emp-table thead th.emp-th-right {
                text-align: right;
            }

            .emp-page table.emp-table tbody td {
                padding: 12px 16px;
                border-bottom: 1px solid var(--emp-line-soft);
                vertical-align: middle;
                font-size: .8375rem;
                color: var(--emp-ink-700);
            }

            .emp-page table.emp-table tbody tr.emp-row {
                height: 60px;
                transition: background .12s ease;
            }

            .emp-page table.emp-table tbody tr.emp-row:hover {
                background: #fafbfd;
            }

            .emp-page table.emp-table tbody tr.emp-row:last-child td {
                border-bottom: none;
            }

            .emp-page table.emp-table tbody tr.emp-row:hover .emp-copy-btn {
                opacity: 1;
            }

            /* Employee column */

            .emp-page .emp-person {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .emp-page .emp-avatar {
                width: 36px;
                height: 36px;
                min-width: 36px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: .74rem;
                font-weight: 700;
                color: #fff;
                letter-spacing: .02em;
            }

            .emp-page .emp-person-name {
                font-size: .85rem;
                font-weight: 600;
                color: var(--emp-ink-900);
                line-height: 1.25;
                white-space: nowrap;
            }

            .emp-page .emp-person-meta {
                font-size: .715rem;
                color: var(--emp-ink-500);
                margin-top: 1px;
                white-space: nowrap;
            }

            /* Copy value rows */

            .emp-page .emp-copy-row {
                display: flex;
                align-items: center;
                gap: 4px;
                white-space: nowrap;
            }

            .emp-copy-row i::before {
                color: grey;
            }

            .emp-page .emp-copy-row+.emp-copy-row {
                margin-top: 3px;
            }

            .emp-page .emp-copy-row i.emp-inline-icon {
                font-size: 11px;
                color: var(--emp-ink-300);
                min-width: 12px;
            }

            .emp-page .emp-copy-row .emp-copy-text {
                font-size: .78rem;
                color: var(--emp-ink-700);
                max-width: 170px;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .emp-page .emp-copy-btn {
                width: 18px;
                height: 18px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border: 0;
                background: transparent;
                padding: 0;
                color: var(--emp-ink-300);
                opacity: 0;
                cursor: pointer;
                border-radius: 4px;
                transition: opacity .12s ease, color .12s ease, background .12s ease;
                flex: none;
            }

            .emp-page .emp-copy-btn:hover {
                color: var(--emp-primary);
                background: var(--emp-primary-soft);
            }

            .emp-page .emp-copy-btn.is-copied {
                opacity: 1;
                color: var(--emp-success);
            }

            .emp-page .emp-copy-btn i {
                font-size: 12px;
            }

            /* Salary */

            .emp-page .emp-salary {
                font-size: .85rem;
                font-weight: 650;
                color: var(--emp-ink-900);
                white-space: nowrap;
            }

            .emp-page .emp-salary-grade {
                font-size: .7rem;
                color: var(--emp-ink-500);
                margin-top: 1px;
            }

            .emp-page .emp-salary-grade .emp-grade-pill {
                display: inline-block;
                padding: 1px 7px;
                border-radius: 999px;
                background: var(--emp-primary-soft);
                color: var(--emp-primary);
                font-weight: 600;
                font-size: .68rem;
            }

            /* Designation */

            .emp-page .emp-designation {
                font-size: .825rem;
                font-weight: 550;
                color: var(--emp-ink-700);
            }

            /* Status */

            .emp-page .emp-status {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                font-size: .78rem;
                font-weight: 600;
            }

            .emp-page .emp-status-dot {
                width: 7px;
                height: 7px;
                border-radius: 50%;
                display: inline-block;
            }

            .emp-page .emp-status.is-active {
                color: var(--emp-success);
            }

            .emp-page .emp-status.is-active .emp-status-dot {
                background: var(--emp-success);
                box-shadow: 0 0 0 3px var(--emp-success-soft);
            }

            .emp-page .emp-status.is-inactive {
                color: var(--emp-danger);
            }

            .emp-page .emp-status.is-inactive .emp-status-dot {
                background: var(--emp-danger);
                box-shadow: 0 0 0 3px var(--emp-danger-soft);
            }

            /* Action column */

            .emp-page .emp-action-btn {
                width: 32px;
                height: 32px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border: 1px solid var(--emp-line);
                background: var(--emp-canvas);
                color: var(--emp-ink-900);
                border-radius: 8px;
                cursor: pointer;
                transition: .15s ease;
            }

            .emp-page .emp-action-btn:hover {
                background: #eef2ff;
                border-color: #cbd5e1;
            }

            .emp-page .emp-action-btn i {
                font-size: 16px;
            }

            .emp-page .emp-action-btn i::before {
                color: var(--emp-ink-700);
            }

            .emp-page .emp-menu {
                border: 1px solid var(--emp-line);
                border-radius: var(--emp-radius-md);
                box-shadow: 0 12px 28px rgba(20, 22, 31, .10);
                padding: 6px;
                min-width: 220px;
                font-size: .8125rem;
            }

            .emp-page .emp-menu .dropdown-item {
                border-radius: var(--emp-radius-sm);
                padding: 8px 10px;
                display: flex;
                align-items: center;
                gap: 9px;
                color: var(--emp-ink-700);
                font-size: .8125rem;
            }

            .emp-page .emp-menu .dropdown-item:hover {
                background: var(--emp-canvas);
                color: var(--emp-ink-900);
            }

            .emp-page .emp-menu .dropdown-item i {
                font-size: 14px;
                color: var(--emp-ink-500);
                min-width: 15px;
            }

            .emp-page .emp-menu .dropdown-divider {
                margin: 5px 2px;
                border-color: var(--emp-line-soft);
            }

            .emp-page .emp-menu-label {
                font-size: .68rem;
                text-transform: uppercase;
                letter-spacing: .04em;
                font-weight: 700;
                color: var(--emp-ink-300);
                padding: 6px 10px 4px;
            }

            .emp-page .emp-menu-toggle-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 4px 10px 8px;
            }

            .emp-page .emp-menu-toggle-row span {
                font-size: .8125rem;
                color: var(--emp-ink-700);
                font-weight: 500;
            }

            /* Empty state */

            .emp-page .emp-empty {
                padding: 72px 20px;
                text-align: center;
            }

            .emp-page .emp-empty i {
                font-size: 40px;
                color: var(--emp-line);
            }

            .emp-page .emp-empty h5 {
                font-size: .95rem;
                font-weight: 650;
                color: var(--emp-ink-900);
                margin: 14px 0 4px;
            }

            .emp-page .emp-empty p {
                font-size: .8125rem;
                color: var(--emp-ink-500);
                margin: 0;
            }

            /* Footer / pagination */

            .emp-page .emp-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                padding: 12px 16px;
                border-top: 1px solid var(--emp-line);
                background: var(--emp-surface);
                flex-wrap: wrap;
            }

            .emp-page .emp-footer-count {
                font-size: .78rem;
                color: var(--emp-ink-500);
            }

            .emp-page .emp-footer-count strong {
                color: var(--emp-ink-900);
                font-weight: 650;
            }

            .emp-page .emp-pagination {
                display: flex;
                align-items: center;
                gap: 4px;
                flex-wrap: wrap;
            }

            .emp-page .emp-page-btn {
                border: 0;
                background: transparent;
                color: var(--emp-ink-500);
                border-radius: var(--emp-radius-sm);
                font-size: .78rem;
                padding: 5px 10px;
                min-height: 30px;
                cursor: pointer;
                transition: background .15s ease, color .15s ease;
            }

            .emp-page .emp-page-btn:hover {
                background: var(--emp-canvas);
                color: var(--emp-ink-900);
            }

            .emp-page .emp-page-btn.is-active {
                background: var(--emp-primary);
                color: #fff;
            }

            .emp-page .emp-page-btn:disabled {
                color: var(--emp-ink-300);
                cursor: not-allowed;
            }

            .emp-page .emp-page-btn:disabled:hover {
                background: transparent;
                color: var(--emp-ink-300);
            }

            /* ---------- Responsive ---------- */

            @media (max-width: 991px) {
                .emp-page .emp-kpis {
                    grid-template-columns: repeat(2, 1fr);
                }

                .emp-page .emp-search {
                    width: 100%;
                }

                .emp-page .emp-header-actions {
                    width: 100%;
                }
            }

            @media (max-width: 767px) {
                .emp-page .emp-kpis {
                    grid-template-columns: repeat(2, 1fr);
                }

                .emp-page table.emp-table {
                    min-width: 920px;
                }

                .emp-page .emp-footer {
                    flex-direction: column;
                    align-items: flex-start;
                }
            }

            .form-check-input.status-switch {
                cursor: pointer;
                width: 2.5rem;
                height: 1.35rem;
            }

            .form-check-input.status-switch:focus {
                box-shadow: none;
            }

            .form-check-input.status-switch:checked {
                background-color: #22c55e;
                border-color: #22c55e;
            }
        </style>
    @endpush

    <div class="emp-page">
        <div class="emp-shell">

            {{-- ============== HEADER ============== --}}
            <div class="emp-header">
                <div class="emp-header-text">
                    <h1>Employee Management</h1>
                    <p>Manage employee records, contact details and account status.</p>
                </div>

                <div class="emp-header-actions">
                    <div class="emp-search">
                        <i class="bi bi-search"></i>
                        <input type="text" id="employeeSearch" placeholder="Search employee, email, mobile...">
                    </div>

                    @if (canAccess(2))
                        <a href="{{ route('add-user') }}" class="emp-add-btn">
                            <i class="nav-icon bi bi-person-plus"></i>
                            Add Employee
                        </a>
                    @endif
                </div>
            </div>

            {{-- ============== KPI STRIP ============== --}}
            {{-- <div class="emp-kpis">
                <div class="emp-kpi">
                    <div class="emp-kpi-icon"><i class="bi bi-people-fill"></i></div>
                    <div>
                        <div class="emp-kpi-value">{{ $users->total() }}</div>
                        <div class="emp-kpi-label">Total Employees</div>
                    </div>
                </div>

                <div class="emp-kpi">
                    <div class="emp-kpi-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
                    <div>
                        <div class="emp-kpi-value">{{ $users->currentPage() }}</div>
                        <div class="emp-kpi-label">Current Page</div>
                    </div>
                </div>

                <div class="emp-kpi">
                    <div class="emp-kpi-icon"><i class="bi bi-list-ol"></i></div>
                    <div>
                        <div class="emp-kpi-value">{{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }}</div>
                        <div class="emp-kpi-label">Showing</div>
                    </div>
                </div>

                <div class="emp-kpi">
                    <div class="emp-kpi-icon"><i class="bi bi-layers-fill"></i></div>
                    <div>
                        <div class="emp-kpi-value">{{ $users->lastPage() }}</div>
                        <div class="emp-kpi-label">Last Page</div>
                    </div>
                </div>
            </div> --}}

            {{-- ============== TABLE CARD ============== --}}
            <div class="emp-card">
                <div class="emp-table-scroll">
                    <table class="emp-table" id="employeeTable">
                        <thead>
                            <tr>
                                <th style="width:260px;">Employee</th>
                                <th>Employee ID</th>
                                <th>Designation</th>
                                <th>Salary</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th class="emp-th-right" style="width:60px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                @php
                                    $fullName = trim($user->fname . ' ' . $user->lname);

                                    $initials = strtoupper(
                                        substr($user->fname ?? '', 0, 1) . substr($user->lname ?? '', 0, 1),
                                    );

                                    if ($initials == '') {
                                        $initials = 'U';
                                    }

                                    // Deterministic soft avatar color derived from the employee's name.
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
                                    $avatarColor = $avatarPalette[crc32($fullName ?: 'U') % count($avatarPalette)];
                                @endphp

                                <tr class="emp-row" data-status="{{ $user->status ? '1' : '2' }}">

                                    {{-- Employee --}}
                                    <td>
                                        <div class="emp-person">
                                            <div class="emp-avatar" style="background:{{ $avatarColor }};">
                                                {{ $initials }}
                                            </div>
                                            <div>
                                                <div class="emp-person-name">{{ $fullName }}</div>
                                                <div class="emp-person-meta">
                                                    {{ $user->designation }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Employee ID --}}
                                    <td>
                                        <div class="emp-copy-row">
                                            <span class="emp-copy-text">{{ $user->employee_id ?? '-' }}</span>
                                            @if ($user->employee_id)
                                                <button class="emp-copy-btn" data-copy="{{ $user->employee_id }}"
                                                    title="Copy Employee ID">
                                                    <i class="bi bi-copy"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Designation --}}
                                    <td>
                                        <span class="emp-designation">{{ $user->designation }}</span>
                                    </td>

                                    {{-- Salary --}}
                                    <td>
                                        @php
                                            $displaySalary = $user->latestFinancialData?->final_salary ?? $user->salary;
                                        @endphp
                                        <div class="emp-salary">₹{{ number_format((float) $displaySalary) }}</div>
                                        <div class="emp-salary-grade">
                                            @if ($user->salary_grade)
                                                <span class="emp-grade-pill">{{ $user->salary_grade }}</span>
                                            @else
                                                <span>—</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Contact (mobile + email combined) --}}
                                    <td>
                                        @php
                                            $mobile = trim((string) ($user->mobno ?? ''));
                                            $email = trim((string) ($user->email ?? ''));
                                        @endphp

                                        @if ($mobile !== '')
                                            <div class="emp-copy-row">
                                                <i class="bi bi-telephone emp-inline-icon"></i>
                                                <span class="emp-copy-text">{{ $mobile }}</span>
                                                <button class="emp-copy-btn" data-copy="{{ $mobile }}"
                                                    title="Copy Mobile">
                                                    <i class="bi bi-copy"></i>
                                                </button>
                                            </div>
                                        @endif

                                        @if ($email !== '')
                                            <div class="emp-copy-row">
                                                <i class="bi bi-envelope emp-inline-icon"></i>
                                                <span class="emp-copy-text"
                                                    title="{{ $email }}">{{ $email }}</span>
                                                <button class="emp-copy-btn" data-copy="{{ $email }}"
                                                    title="Copy Email">
                                                    <i class="bi bi-copy"></i>
                                                </button>
                                            </div>
                                        @endif

                                        @if ($mobile === '' && $email === '')
                                            <span>—</span>
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td class="status-class">
                                        @if ($user->status)
                                            <span class="emp-status is-active">
                                                <span class="emp-status-dot"></span>
                                                Active
                                            </span>
                                        @else
                                            <span class="emp-status is-inactive">
                                                <span class="emp-status-dot"></span>
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="emp-action-btn" type="button" aria-expanded="false"
                                                title="More actions">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end emp-menu">
                                                <li>
                                                    <a href="{{ url('/edit-user/' . $user->id) }}" class="dropdown-item">
                                                        <i class="bi bi-pencil-square"></i>
                                                        Edit Employee
                                                    </a>
                                                </li>

                                                @if ($user->email)
                                                    <li>
                                                        <button type="button" class="dropdown-item emp-copy-menu-item"
                                                            data-copy="{{ $user->email }}">
                                                            <i class="bi bi-envelope"></i>
                                                            Copy Email
                                                        </button>
                                                    </li>
                                                @endif

                                                @if ($user->mobno)
                                                    <li>
                                                        <button type="button" class="dropdown-item emp-copy-menu-item"
                                                            data-copy="{{ $user->mobno }}">
                                                            <i class="bi bi-telephone"></i>
                                                            Copy Mobile
                                                        </button>
                                                    </li>
                                                @endif

                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>

                                                <li>
                                                    <div class="emp-menu-label">Account Status</div>
                                                    <div class="emp-menu-toggle-row">
                                                        <span>{{ $user->status ? 'Active' : 'Inactive' }}</span>
                                                        {{-- <input type="checkbox" class="toggle-btn" data-toggle="toggle"
                                                            data-size="mini" data-on="Active" data-off="Inactive"
                                                            data-onstyle="success" data-offstyle="secondary"
                                                            data-user-type="{{ $user->user_type }}"
                                                            data-identifier="{{ $user->user_type === 'client' ? $user->id : $user->employee_id }}"
                                                            {{ $user->status ? 'checked' : '' }}> --}}
                                                        <div class="form-check form-switch m-0 d-flex justify-content-end">
                                                            <input class="form-check-input status-switch" type="checkbox"
                                                                role="switch" data-user-type="{{ $user->user_type }}"
                                                                data-identifier="{{ $user->user_type === 'client' ? $user->id : $user->employee_id }}"
                                                                {{ $user->status ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="emp-empty">
                                            <i class="bi bi-people"></i>
                                            <h5>No employees found</h5>
                                            <p>There are currently no employees available.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                            @if (count($users) > 0)
                                <tr id="employeeNoResults" hidden>
                                    <td colspan="7">
                                        <div class="emp-empty">
                                            <i class="bi bi-search"></i>
                                            <h5>No matching employees</h5>
                                            <p>Try a different search term.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- ============== FOOTER / PAGINATION ============== --}}
                @if (count($users) > 0)
                    <div class="emp-footer">
                        <div class="emp-footer-count" id="employeePaginationInfo"></div>
                        <div class="emp-pagination" id="employeePagination"></div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
        {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script> --}}

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // $('.toggle-btn').bootstrapToggle();

                // ---- Instant client-side search + pagination ----
                const searchInput = document.getElementById('employeeSearch');
                const rows = Array.from(document.querySelectorAll('#employeeTable tbody tr.emp-row'));
                const noResultsRow = document.getElementById('employeeNoResults');
                const paginationInfo = document.getElementById('employeePaginationInfo');
                const pagination = document.getElementById('employeePagination');
                const pageSize = 10;
                let currentPage = 1;
                let filteredRows = rows;

                function closeEmployeeMenus() {
                    document.querySelectorAll('.emp-page .dropdown.is-open').forEach(function(dropdown) {
                        const button = dropdown.querySelector('.emp-action-btn');
                        const menu = dropdown.querySelector('.emp-menu');

                        dropdown.classList.remove('is-open');

                        if (button) {
                            button.setAttribute('aria-expanded', 'false');
                        }

                        if (menu) {
                            menu.removeAttribute('style');
                        }
                    });
                }

                function openEmployeeMenu(dropdown) {
                    const button = dropdown.querySelector('.emp-action-btn');
                    const menu = dropdown.querySelector('.emp-menu');

                    if (!button || !menu) {
                        return;
                    }

                    closeEmployeeMenus();

                    dropdown.classList.add('is-open');
                    button.setAttribute('aria-expanded', 'true');
                    menu.style.display = 'block';
                    menu.style.position = 'fixed';
                    menu.style.zIndex = '2000';

                    const buttonRect = button.getBoundingClientRect();
                    const menuRect = menu.getBoundingClientRect();
                    const windowPadding = 12;
                    const left = Math.max(
                        windowPadding,
                        Math.min(buttonRect.right - menuRect.width, window.innerWidth - menuRect.width -
                            windowPadding)
                    );
                    let top = buttonRect.bottom + 6;

                    if (top + menuRect.height > window.innerHeight - windowPadding) {
                        top = Math.max(windowPadding, buttonRect.top - menuRect.height - 6);
                    }

                    menu.style.left = `${left}px`;
                    menu.style.top = `${top}px`;
                }

                document.addEventListener('click', function(event) {
                    const actionButton = event.target.closest('.emp-page .emp-action-btn');
                    const openMenu = event.target.closest('.emp-page .emp-menu');

                    if (actionButton) {
                        event.preventDefault();
                        event.stopPropagation();

                        const dropdown = actionButton.closest('.dropdown');

                        if (dropdown && dropdown.classList.contains('is-open')) {
                            closeEmployeeMenus();
                        } else if (dropdown) {
                            openEmployeeMenu(dropdown);
                        }

                        return;
                    }

                    if (!openMenu) {
                        closeEmployeeMenus();
                    }
                });

                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        closeEmployeeMenus();
                    }
                });

                window.addEventListener('resize', closeEmployeeMenus);
                window.addEventListener('scroll', closeEmployeeMenus, true);

                function getVisiblePages(totalPages) {
                    const pages = [];
                    const maxButtons = 5;
                    let start = Math.max(1, currentPage - Math.floor(maxButtons / 2));
                    let end = Math.min(totalPages, start + maxButtons - 1);

                    if (end - start + 1 < maxButtons) {
                        start = Math.max(1, end - maxButtons + 1);
                    }

                    for (let page = start; page <= end; page++) {
                        pages.push(page);
                    }

                    return pages;
                }

                function buildPageButton(label, page, options = {}) {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.textContent = label;
                    button.className = 'emp-page-btn';

                    if (options.active) {
                        button.classList.add('is-active');
                    }

                    if (options.disabled) {
                        button.disabled = true;
                    }

                    button.addEventListener('click', function() {
                        currentPage = page;
                        renderTable();
                    });

                    return button;
                }

                function renderPagination(totalPages) {
                    if (!pagination) {
                        return;
                    }

                    pagination.innerHTML = '';

                    if (totalPages <= 1) {
                        return;
                    }

                    pagination.appendChild(buildPageButton('Previous', Math.max(1, currentPage - 1), {
                        disabled: currentPage === 1
                    }));

                    getVisiblePages(totalPages).forEach(function(page) {
                        pagination.appendChild(buildPageButton(String(page), page, {
                            active: page === currentPage
                        }));
                    });

                    pagination.appendChild(buildPageButton('Next', Math.min(totalPages, currentPage + 1), {
                        disabled: currentPage === totalPages
                    }));
                }

                function renderTable() {
                    const totalRows = filteredRows.length;
                    const totalPages = Math.max(1, Math.ceil(totalRows / pageSize));

                    if (currentPage > totalPages) {
                        currentPage = totalPages;
                    }

                    const startIndex = (currentPage - 1) * pageSize;
                    const endIndex = startIndex + pageSize;

                    rows.forEach(function(row) {
                        row.hidden = true;
                    });

                    filteredRows.slice(startIndex, endIndex).forEach(function(row) {
                        row.hidden = false;
                    });

                    if (noResultsRow) {
                        noResultsRow.hidden = totalRows !== 0;
                    }

                    if (paginationInfo) {
                        const from = totalRows === 0 ? 0 : startIndex + 1;
                        const to = Math.min(endIndex, totalRows);
                        paginationInfo.innerHTML =
                            `Showing <strong>${from}</strong> to <strong>${to}</strong> of <strong>${totalRows}</strong> employees`;
                    }

                    renderPagination(totalPages);
                }

                function filterRows() {
                    const keyword = (searchInput?.value || '').toLowerCase().trim();

                    filteredRows = rows.filter(function(row) {
                        return row.innerText.toLowerCase().includes(keyword);
                    });

                    currentPage = 1;
                    renderTable();
                }

                if (searchInput && rows.length > 0) {
                    searchInput.addEventListener('keyup', filterRows);
                }

                if (rows.length > 0) {
                    renderTable();
                }

                // ---- Copy-to-clipboard (table icons + dropdown menu items) ----
                function bindCopyHandler(el) {
                    el.addEventListener('click', function() {
                        const value = this.dataset.copy;
                        navigator.clipboard.writeText(value);

                        const icon = this.querySelector('i');
                        if (!icon) return;

                        const originalClass = icon.className;
                        icon.className = 'bi bi-check2';
                        this.classList.add('is-copied');

                        setTimeout(() => {
                            icon.className = originalClass;
                            this.classList.remove('is-copied');
                        }, 800);

                        closeEmployeeMenus();
                    });
                }

                document.querySelectorAll('.emp-copy-btn').forEach(bindCopyHandler);
                document.querySelectorAll('.emp-copy-menu-item').forEach(bindCopyHandler);
            });

            // ---- Account status toggle (unchanged AJAX contract) ----
            $(document).on('change', '.status-switch', function() {

                let button = $(this);

                let userType = button.data('user-type');
                let identifier = button.data('identifier');

                let row = button.closest('tr.emp-row');
                let badge = row.find('.status-class');
                let menuLabel = button.closest('.emp-menu-toggle-row').find('span');

                // Remember current state
                const previousState = !button.prop('checked');

                $.ajax({
                    url: `/toggle-status/${userType}/${identifier}`,
                    type: "POST",

                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },

                    beforeSend: function() {
                        button.prop('disabled', true);
                    },

                    success: function(response) {
                        if (response.success) {
                            if (response.new_status) {
                                badge.html(`
                        <span class="emp-status is-active">
                            <span class="emp-status-dot"></span>
                            Active
                        </span>
                    `);
                                row.attr('data-status', '1');
                                menuLabel.text('Active');
                                button.prop('checked', true);
                            } else {

                                badge.html(`
                        <span class="emp-status is-inactive">
                            <span class="emp-status-dot"></span>
                            Inactive
                        </span>
                    `);
                                row.attr('data-status', '2');
                                menuLabel.text('Inactive');
                                button.prop('checked', false);
                            }
                        } else {
                            alert(response.error || 'Unable to update status.');
                            button.prop('checked', previousState);
                        }
                    },
                    error: function() {
                        alert('Something went wrong.');
                        button.prop('checked', previousState);
                    },
                    complete: function() {
                        button.prop('disabled', false);
                    }
                });
            });
        </script>
    @endpush
@endsection
