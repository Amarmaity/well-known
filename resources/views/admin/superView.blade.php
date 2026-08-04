@extends('layouts.app')

@section('title', 'Employee Review')
@section('breadcrumb', 'Super view')
@section('page-title', 'Super Admin Dashboard')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <link href="{{ asset('css/review-management.css') }}?v={{ filemtime(public_path('css/review-management.css')) }}" rel="stylesheet">
    @endpush

    <div class="emp-page">
        <div class="emp-shell">
            <div class="emp-header">
                <div class="emp-header-text">
                    <h1>All Employee Reviews</h1>
                    <p>Review completed employee evaluations and open detailed review reports.</p>
                </div>

                <div class="emp-header-actions">
                    <div class="emp-search">
                        <input type="text" id="employeeReviewSearch" placeholder="Search employee, email, ID...">
                        <i class="bi bi-search"></i>
                    </div>
                </div>
            </div>

            <div class="emp-card">
                <div class="emp-table-scroll">
                    <table class="emp-table" id="employeeReviewTable">
                        <thead>
                            <tr>
                                <th style="width:260px;">Employee</th>
                                <th>Employee ID</th>
                                <th>Designation</th>
                                <th>Contact</th>
                                <th class="emp-th-right" style="width:60px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="employeeReviewBody">
                            @forelse ($employees as $employee)
                                @php
                                    $fullName = trim(($employee->fname ?? '') . ' ' . ($employee->lname ?? ''));
                                    $initials = strtoupper(
                                        substr($employee->fname ?? '', 0, 1) . substr($employee->lname ?? '', 0, 1),
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
                                                <div class="emp-person-meta">{{ $employee->designation }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="emp-copy-row">
                                            <span class="emp-copy-text">{{ $employee->employee_id ?? '-' }}</span>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="emp-designation">{{ $employee->designation }}</span>
                                    </td>

                                    <td>
                                        <div class="emp-copy-row">
                                            <i class="bi bi-envelope emp-inline-icon"></i>
                                            <span class="emp-copy-text" title="{{ $employee->email }}">
                                                {{ $employee->email }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="emp-action-btn" type="button" aria-expanded="false"
                                                title="More actions">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end emp-menu">
                                                <li>
                                                    <button type="button" class="dropdown-item"
                                                        data-review-employee="{{ $employee->employee_id }}">
                                                        <i class="bi bi-eye"></i>
                                                        View Details
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="employeeReviewEmpty">
                                    <td colspan="5">
                                        <div class="emp-empty">
                                            <i class="bi bi-clipboard-data"></i>
                                            <h5>No employee reviews found</h5>
                                            <p>There are currently no employee reviews available.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                            @if (count($employees) > 0)
                                <tr id="employeeReviewNoResults" hidden>
                                    <td colspan="5">
                                        <div class="emp-empty">
                                            <i class="bi bi-search"></i>
                                            <h5>No matching employee reviews</h5>
                                            <p>Try a different search term.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                @if (count($employees) > 0)
                    <div class="emp-footer" id="employeeReviewFooter">
                        <div class="emp-footer-count" id="employeeReviewPaginationInfo"></div>
                        <div class="emp-pagination" id="employeeReviewPagination"></div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            function viewEmployeeDetails(empId) {
                window.location.href = "/employee/details/" + empId;
            }

            document.addEventListener('DOMContentLoaded', function() {
                const body = document.getElementById('employeeReviewBody');
                const searchInput = document.getElementById('employeeReviewSearch');
                const noResultsRow = document.getElementById('employeeReviewNoResults');
                const paginationInfo = document.getElementById('employeeReviewPaginationInfo');
                const pagination = document.getElementById('employeeReviewPagination');
                const pageSize = 10;
                let rows = Array.from(document.querySelectorAll('#employeeReviewTable tbody tr.emp-row'));
                let currentPage = 1;
                let filteredRows = rows;

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

                function escapeHtml(value) {
                    return String(value)
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;');
                }

                function closeReviewMenus() {
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

                function openReviewMenu(dropdown) {
                    const button = dropdown.querySelector('.emp-action-btn');
                    const menu = dropdown.querySelector('.emp-menu');

                    if (!button || !menu) {
                        return;
                    }

                    closeReviewMenus();

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
                        Math.min(buttonRect.right - menuRect.width, window.innerWidth - menuRect.width - windowPadding)
                    );
                    let top = buttonRect.bottom + 6;

                    if (top + menuRect.height > window.innerHeight - windowPadding) {
                        top = Math.max(windowPadding, buttonRect.top - menuRect.height - 6);
                    }

                    menu.style.left = `${left}px`;
                    menu.style.top = `${top}px`;
                }

                document.addEventListener('click', function(event) {
                    const reviewAction = event.target.closest('[data-review-employee]');
                    const actionButton = event.target.closest('.emp-page .emp-action-btn');
                    const openMenu = event.target.closest('.emp-page .emp-menu');

                    if (reviewAction) {
                        viewEmployeeDetails(reviewAction.dataset.reviewEmployee);
                        return;
                    }

                    if (actionButton) {
                        event.preventDefault();
                        event.stopPropagation();

                        const dropdown = actionButton.closest('.dropdown');

                        if (dropdown?.classList.contains('is-open')) {
                            closeReviewMenus();
                        } else if (dropdown) {
                            openReviewMenu(dropdown);
                        }

                        return;
                    }

                    if (!openMenu) {
                        closeReviewMenus();
                    }
                });

                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        closeReviewMenus();
                    }
                });

                window.addEventListener('resize', closeReviewMenus);
                window.addEventListener('scroll', closeReviewMenus, true);

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
                        closeReviewMenus();
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
                        paginationInfo.innerHTML = `Showing <strong>${from}</strong> to <strong>${to}</strong> of <strong>${totalRows}</strong> reviews`;
                    }

                    renderPagination(totalPages);
                }

                function filterRows() {
                    const keyword = (searchInput?.value || '').toLowerCase().trim();

                    filteredRows = rows.filter(function(row) {
                        return row.innerText.toLowerCase().includes(keyword);
                    });

                    currentPage = 1;
                    closeReviewMenus();
                    renderTable();
                }

                function buildEmployeeRow(employee) {
                    const fullName = employee.full_name || `${employee.fname || ''} ${employee.lname || ''}`.trim() || 'N/A';
                    const employeeId = employee.employee_id || '-';
                    const designation = employee.designation || '-';
                    const email = employee.email || '-';
                    const safeFullName = escapeHtml(fullName);
                    const safeEmployeeId = escapeHtml(employeeId);
                    const safeDesignation = escapeHtml(designation);
                    const safeEmail = escapeHtml(email);
                    const tr = document.createElement('tr');

                    tr.className = 'emp-row';
                    tr.innerHTML = `
                        <td>
                            <div class="emp-person">
                                <div class="emp-avatar" style="background:${getAvatarColor(fullName)};">
                                    ${escapeHtml(getInitials(fullName))}
                                </div>
                                <div>
                                    <div class="emp-person-name">${safeFullName}</div>
                                    <div class="emp-person-meta">${safeDesignation}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="emp-copy-row">
                                <span class="emp-copy-text">${safeEmployeeId}</span>
                            </div>
                        </td>
                        <td><span class="emp-designation">${safeDesignation}</span></td>
                        <td>
                            <div class="emp-copy-row">
                                <i class="bi bi-envelope emp-inline-icon"></i>
                                <span class="emp-copy-text" title="${safeEmail}">${safeEmail}</span>
                            </div>
                        </td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="emp-action-btn" type="button" aria-expanded="false" title="More actions">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end emp-menu">
                                    <li>
                                        <button type="button" class="dropdown-item" data-review-employee="${safeEmployeeId}">
                                            <i class="bi bi-eye"></i>
                                            View Details
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    `;

                    return tr;
                }

                function resetRows(newRows) {
                    rows = newRows;
                    filteredRows = rows;
                    currentPage = 1;
                    closeReviewMenus();
                    renderTable();
                }

                if (searchInput && rows.length > 0) {
                    searchInput.addEventListener('keyup', filterRows);
                }

                if (rows.length > 0) {
                    renderTable();
                }

                $(document).on('change', '#financialYearFilter', function() {
                    const selectedYear = $(this).val();

                    if (selectedYear !== '') {
                        $.ajax({
                            url: '/employees/filter-financial-year-employee-review',
                            method: 'POST',
                            data: {
                                financial_year: selectedYear,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                const employees = response.data || [];
                                const newRows = employees.map(buildEmployeeRow);

                                body.querySelectorAll('tr.emp-row').forEach(function(row) {
                                    row.remove();
                                });

                                newRows.forEach(function(row) {
                                    body.insertBefore(row, noResultsRow);
                                });

                                resetRows(newRows);
                            },
                            error: function(xhr, status, error) {
                                console.error("Error fetching data: " + error);
                            }
                        });
                    } else {
                        location.reload();
                    }
                });
            });
        </script>
    @endpush
@endsection
