@extends('layouts.app')

@section('title', 'Appraisal Done')
@section('breadcrumb', 'Appraisal Done')
@section('page-title', 'Financial Section')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <link href="{{ asset('css/appraisal-done-management.css') }}?v={{ filemtime(public_path('css/appraisal-done-management.css')) }}" rel="stylesheet">
        <link href="{{ asset('css/appraisal-done-extra.css') }}?v={{ filemtime(public_path('css/appraisal-done-extra.css')) }}" rel="stylesheet">
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
                                <th>Company Given</th>
                                <th>Financial Year</th>
                                <th>Updated Salary</th>
                                <th>Final Salary</th>
                                <th>Appraisal Date</th>
                            </tr>
                        </thead>
                        <tbody id="employeeList">
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
                                    <td><span class="appraisal-done-score">{{ $financial->evaluation_score ?? '-' }}</span></td>
                                    <td><span class="appraisal-done-score">{{ $financial->hr_review ?? '-' }}</span></td>
                                    <td><span class="appraisal-done-score">{{ $financial->admin_review ?? '-' }}</span></td>
                                    <td><span class="appraisal-done-score">{{ $financial->manager_review ?? '-' }}</span></td>
                                    <td><span class="appraisal-done-score">{{ $financial->clint_review ?? '-' }}</span></td>
                                    <td><span class="appraisal-done-score">{{ $financial->apprisal_score ?? '-' }}</span></td>
                                    <td><span class="appraisal-done-money">{{ $financial->current_salary ?? '-' }}</span></td>
                                    <td><span class="appraisal-done-score">{{ $financial->percentage_given ?? '-' }}</span></td>
                                    <td><span class="appraisal-done-muted">{{ $financial->financial_year ?? '-' }}</span></td>
                                    <td><span class="appraisal-done-money">{{ $financial->update_salary ?? '-' }}</span></td>
                                    <td><span class="appraisal-done-money">{{ $financial->final_salary ?? '-' }}</span></td>
                                    <td><span class="appraisal-done-muted">{{ $financial->apprisal_date ?? '-' }}</span></td>
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

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const body = document.getElementById('employeeList');
                const searchInput = document.getElementById('employee_search');
                const noResultsRow = document.getElementById('appraisalDoneNoResults');
                const paginationInfo = document.getElementById('appraisalDonePaginationInfo');
                const pagination = document.getElementById('appraisalDonePagination');
                const pageSize = 10;
                let rows = Array.from(document.querySelectorAll('#employeeList tr.emp-row'));
                let filteredRows = rows;
                let currentPage = 1;
                let typingTimer;
                const debounceDelay = 400;

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

                function messageRow(icon, title, message) {
                    return `
                        <tr>
                            <td colspan="14">
                                <div class="appraisal-done-message">
                                    <i class="bi ${icon}"></i>
                                    <h5>${escapeHtml(title)}</h5>
                                    <p>${escapeHtml(message)}</p>
                                </div>
                            </td>
                        </tr>
                    `;
                }

                function buildFinancialRow(financial) {
                    const employeeName = financial.employee_name || '-';
                    const employeeId = financial.emp_id || '-';
                    const financialYear = financial.financial_year || '-';
                    const tr = document.createElement('tr');

                    tr.className = 'emp-row';
                    tr.innerHTML = `
                        <td>
                            <div class="emp-person">
                                <div class="emp-avatar" style="background:${getAvatarColor(employeeName)};">
                                    ${escapeHtml(getInitials(employeeName))}
                                </div>
                                <div>
                                    <div class="emp-person-name">${escapeHtml(employeeName)}</div>
                                    <div class="emp-person-meta">${escapeHtml(financialYear)}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="appraisal-done-muted">${escapeHtml(employeeId)}</span></td>
                        <td><span class="appraisal-done-score">${escapeHtml(financial.evaluation_score ?? '-')}</span></td>
                        <td><span class="appraisal-done-score">${escapeHtml(financial.hr_review ?? '-')}</span></td>
                        <td><span class="appraisal-done-score">${escapeHtml(financial.admin_review ?? '-')}</span></td>
                        <td><span class="appraisal-done-score">${escapeHtml(financial.manager_review ?? '-')}</span></td>
                        <td><span class="appraisal-done-score">${escapeHtml(financial.clint_review ?? '-')}</span></td>
                        <td><span class="appraisal-done-score">${escapeHtml(financial.apprisal_score ?? '-')}</span></td>
                        <td><span class="appraisal-done-money">${escapeHtml(financial.current_salary ?? '-')}</span></td>
                        <td><span class="appraisal-done-score">${escapeHtml(financial.percentage_given ?? '-')}</span></td>
                        <td><span class="appraisal-done-muted">${escapeHtml(financialYear)}</span></td>
                        <td><span class="appraisal-done-money">${escapeHtml(financial.update_salary ?? '-')}</span></td>
                        <td><span class="appraisal-done-money">${escapeHtml(financial.final_salary ?? '-')}</span></td>
                        <td><span class="appraisal-done-muted">${escapeHtml(financial.apprisal_date ?? '-')}</span></td>
                    `;

                    return tr;
                }

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
                        paginationInfo.innerHTML = `Showing <strong>${from}</strong> to <strong>${to}</strong> of <strong>${totalRows}</strong> records`;
                    }

                    renderPagination(totalPages);
                }

                function resetRows(newRows) {
                    body.querySelectorAll('tr.emp-row').forEach(function(row) {
                        row.remove();
                    });

                    newRows.forEach(function(row) {
                        if (noResultsRow) {
                            body.insertBefore(row, noResultsRow);
                        } else {
                            body.appendChild(row);
                        }
                    });

                    rows = newRows;
                    filteredRows = rows;
                    currentPage = 1;
                    renderTable();
                }

                function searchEmployee() {
                    const query = searchInput.value.trim();

                    body.innerHTML = messageRow('bi-hourglass-split', query ? 'Searching records' : 'Loading records',
                        query ? 'Please wait while matching appraisal records are loaded.' : 'Please wait while appraisal records are loaded.');

                    $.ajax({
                        url: "{{ route('super.user.search.bar') }}",
                        type: 'GET',
                        data: {
                            query: query
                        },
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            const financialRows = response.financialData || [];

                            body.innerHTML = '';

                            if (!financialRows.length) {
                                body.innerHTML = messageRow('bi-search', query ? 'No matching data found' : 'No appraisal records found',
                                    query ? 'Try a different search term.' : 'Completed appraisal records will appear here.');
                                if (pagination) {
                                    pagination.innerHTML = '';
                                }
                                if (paginationInfo) {
                                    paginationInfo.innerHTML = 'Showing <strong>0</strong> to <strong>0</strong> of <strong>0</strong> records';
                                }
                                rows = [];
                                filteredRows = [];
                                return;
                            }

                            const newRows = financialRows.map(buildFinancialRow);
                            if (noResultsRow) {
                                body.appendChild(noResultsRow);
                            }
                            resetRows(newRows);
                        },
                        error: function() {
                            body.innerHTML = messageRow('bi-exclamation-circle', 'Unable to load data',
                                'An error occurred while loading appraisal records.');
                        }
                    });
                }

                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        clearTimeout(typingTimer);
                        typingTimer = setTimeout(searchEmployee, debounceDelay);
                    });
                }

                if (rows.length > 0) {
                    renderTable();
                }
            });
        </script>
    @endpush
@endsection
