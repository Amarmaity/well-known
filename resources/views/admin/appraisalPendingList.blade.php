@extends('layouts.app')

@section('title', 'Pending Appraisal')
@section('breadcrumb', 'Pending Appraisal')
@section('page-title', 'Appraisal Section')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <link href="{{ asset('css/pending-appraisal-management.css') }}?v={{ filemtime(public_path('css/pending-appraisal-management.css')) }}" rel="stylesheet">
        <link href="{{ asset('css/pending-appraisal-extra.css') }}?v={{ filemtime(public_path('css/pending-appraisal-extra.css')) }}" rel="stylesheet">
    @endpush

    <div class="emp-page pending-appraisal-page">
        <div class="emp-shell">
            <div class="emp-header">
                <div class="emp-header-text">
                    <h1>Pending Appraisal</h1>
                    <p>Review employees still waiting for appraisal completion.</p>
                </div>

                <div class="emp-header-actions">
                    <div class="emp-search">
                        <input type="text" id="employee_search" name="search" placeholder="Search employee, email, ID..."
                            aria-label="Search">
                        <i class="bi bi-search"></i>
                    </div>
                </div>
            </div>

            <div class="emp-card">
                <div class="emp-table-scroll">
                    <table id="pending-apprasial" class="emp-table">
                        <thead>
                            <tr>
                                <th style="width:260px;">Employee</th>
                                <th>Employee ID</th>
                                <th>Designation</th>
                                <th>Contact</th>
                                <th>Joining Date</th>
                                <th>Financial Year</th>
                                <th>Probation Date</th>
                            </tr>
                        </thead>
                        <tbody id="pendingAppraisalBody">
                            @forelse ($users as $user)
                                @php
                                    $fullName = trim(($user->fname ?? '') . ' ' . ($user->lname ?? ''));
                                    $initials = strtoupper(
                                        substr($user->fname ?? '', 0, 1) . substr($user->lname ?? '', 0, 1),
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
                                    $avatarColor = $avatarPalette[crc32($fullName ?: 'U') % count($avatarPalette)];
                                @endphp

                                <tr class="emp-row">
                                    <td>
                                        <div class="emp-person">
                                            <div class="emp-avatar" style="background:{{ $avatarColor }};">
                                                {{ $initials }}
                                            </div>
                                            <div>
                                                <div class="emp-person-name">{{ $fullName }}</div>
                                                <div class="emp-person-meta">{{ $user->designation }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="pending-muted">{{ $user->employee_id }}</span></td>
                                    <td><span class="emp-designation">{{ $user->designation }}</span></td>
                                    <td>
                                        <div class="emp-copy-row">
                                            <i class="bi bi-envelope emp-inline-icon"></i>
                                            <span class="emp-copy-text" title="{{ $user->email }}">{{ $user->email }}</span>
                                        </div>
                                    </td>
                                    <td><span class="pending-date">{{ $user->dob }}</span></td>
                                    <td><span class="pending-muted">{{ $user->financial_year }}</span></td>
                                    <td><span class="pending-date">{{ $user->probation_date ?? 'Not Set' }}</span></td>
                                </tr>
                            @empty
                                <tr id="pendingAppraisalEmpty">
                                    <td colspan="7">
                                        <div class="pending-message">
                                            <i class="bi bi-list-check"></i>
                                            <h5>No pending appraisals</h5>
                                            <p>There are currently no employees waiting for appraisal.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                            @if (count($users) > 0)
                                <tr id="pendingAppraisalNoResults" hidden>
                                    <td colspan="7">
                                        <div class="pending-message">
                                            <i class="bi bi-search"></i>
                                            <h5>No matching pending appraisals</h5>
                                            <p>Try a different search term.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                @if (count($users) > 0)
                    <div class="emp-footer" id="pendingAppraisalFooter">
                        <div class="emp-footer-count" id="pendingAppraisalPaginationInfo"></div>
                        <div class="emp-pagination" id="pendingAppraisalPagination"></div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const body = document.getElementById('pendingAppraisalBody');
                const searchInput = document.getElementById('employee_search');
                const noResultsRow = document.getElementById('pendingAppraisalNoResults');
                const paginationInfo = document.getElementById('pendingAppraisalPaginationInfo');
                const pagination = document.getElementById('pendingAppraisalPagination');
                const pageSize = 10;
                let rows = Array.from(document.querySelectorAll('#pendingAppraisalBody tr.emp-row'));
                let filteredRows = rows;
                let currentPage = 1;

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

                function buildPendingRow(user) {
                    const fullName = user.full_name || user[0] || `${user.fname || ''} ${user.lname || ''}`.trim() || 'N/A';
                    const employeeId = user.employee_id || user[1] || '-';
                    const designation = user.designation || user[2] || '-';
                    const email = user.email || user[3] || '-';
                    const joiningDate = user.dob || user[4] || '-';
                    const financialYear = user.financial_year || user[5] || '-';
                    const probationDate = user.probation_date || user[6] || 'Not Set';
                    const tr = document.createElement('tr');

                    tr.className = 'emp-row';
                    tr.innerHTML = `
                        <td>
                            <div class="emp-person">
                                <div class="emp-avatar" style="background:${getAvatarColor(fullName)};">
                                    ${escapeHtml(getInitials(fullName))}
                                </div>
                                <div>
                                    <div class="emp-person-name">${escapeHtml(fullName)}</div>
                                    <div class="emp-person-meta">${escapeHtml(designation)}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="pending-muted">${escapeHtml(employeeId)}</span></td>
                        <td><span class="emp-designation">${escapeHtml(designation)}</span></td>
                        <td>
                            <div class="emp-copy-row">
                                <i class="bi bi-envelope emp-inline-icon"></i>
                                <span class="emp-copy-text" title="${escapeHtml(email)}">${escapeHtml(email)}</span>
                            </div>
                        </td>
                        <td><span class="pending-date">${escapeHtml(joiningDate)}</span></td>
                        <td><span class="pending-muted">${escapeHtml(financialYear)}</span></td>
                        <td><span class="pending-date">${escapeHtml(probationDate)}</span></td>
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
                        paginationInfo.innerHTML = `Showing <strong>${from}</strong> to <strong>${to}</strong> of <strong>${totalRows}</strong> employees`;
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

                function fetchAppraisalPendingList(yearRange) {
                    if (!yearRange) {
                        return;
                    }

                    $.ajax({
                        url: '/filter-by-financial-year',
                        method: 'POST',
                        data: {
                            financial_year: yearRange,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            const data = response.data || [];

                            if (!data.length) {
                                alert("No users found for the selected financial year.");
                                resetRows([]);
                                return;
                            }

                            resetRows(data.map(buildPendingRow));
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching data: " + error);
                        }
                    });
                }

                if (searchInput && rows.length > 0) {
                    searchInput.addEventListener('keyup', filterRows);
                }

                if (rows.length > 0) {
                    renderTable();
                }

                $(document).on('change', '#financialYearFilter', function() {
                    fetchAppraisalPendingList($(this).val());
                });

                const defaultYear = $('#financialYearFilter').val();
                if (defaultYear) {
                    fetchAppraisalPendingList(defaultYear);
                }
            });
        </script>
    @endpush
@endsection
