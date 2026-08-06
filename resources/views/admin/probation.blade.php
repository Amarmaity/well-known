@extends('layouts.app')

@section('title', 'Probation Period')
@section('breadcrumb', 'Probation Period')
@section('page-title', 'Probation Period')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <link href="{{ asset('css/probation-management.css') }}?v={{ filemtime(public_path('css/probation-management.css')) }}" rel="stylesheet">
    @endpush

    <div class="emp-page">
        <div class="emp-shell">
            <div class="emp-header">
                <div class="emp-header-text">
                    <h1>Probation Period</h1>
                    <p>Track probation employees, joining dates and probation completion dates.</p>
                </div>

                <div class="emp-header-actions">
                    <div class="emp-search">
                        <input type="text" id="probationSearch" placeholder="Search employee, email, ID...">
                        <i class="bi bi-search"></i>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success" data-auto-dismiss="success">{{ session('success') }}</div>
            @endif

            <div class="emp-card">
                <div class="emp-table-scroll">
                    <table class="emp-table" id="probationTable">
                        <thead>
                            <tr>
                                <th style="width:260px;">Employee</th>
                                <th>Employee ID</th>
                                <th>Joining Date</th>
                                <th>Probation Date</th>
                                <th>Salary</th>
                                <th>Contact</th>
                                <th class="emp-th-right" style="width:60px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($user as $users)
                                @php
                                    $fullName = trim(($users->fname ?? '') . ' ' . ($users->lname ?? ''));
                                    $initials = strtoupper(
                                        substr($users->fname ?? '', 0, 1) . substr($users->lname ?? '', 0, 1),
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
                                                <div class="emp-person-meta">{{ $users->designation }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="emp-copy-row">
                                            <span class="emp-copy-text">{{ $users->employee_id ?? '-' }}</span>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="emp-designation">{{ $users->dob ?? '-' }}</span>
                                    </td>

                                    <td>
                                        <span class="emp-status is-active probation-date-text"
                                            id="probationDate{{ $users->employee_id }}">
                                            <span class="emp-status-dot"></span>
                                            {{ $users->probation_date ?? 'Not Set' }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="emp-salary">₹{{ number_format((float) $users->salary) }}</div>
                                    </td>

                                    <td>
                                        <div class="emp-copy-row">
                                            <i class="bi bi-envelope emp-inline-icon"></i>
                                            <span class="emp-copy-text" title="{{ $users->email }}">{{ $users->email }}</span>
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
                                                    <a href="{{ route('edit-probation-user', ['id' => $users->id]) }}"
                                                        class="dropdown-item">
                                                        <i class="bi bi-pencil-square"></i>
                                                        Edit Employee
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="emp-empty">
                                            <i class="bi bi-hourglass-split"></i>
                                            <h5>No probation employees found</h5>
                                            <p>There are currently no employees in probation period.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                            @if (count($user) > 0)
                                <tr id="probationNoResults" hidden>
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

                @if (count($user) > 0)
                    <div class="emp-footer">
                        <div class="emp-footer-count" id="probationPaginationInfo"></div>
                        <div class="emp-pagination" id="probationPagination"></div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/autoDismissAlerts.js') }}?v={{ time() }}"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const rows = Array.from(document.querySelectorAll('#probationTable tbody tr.emp-row'));
                const searchInput = document.getElementById('probationSearch');
                const noResultsRow = document.getElementById('probationNoResults');
                const paginationInfo = document.getElementById('probationPaginationInfo');
                const pagination = document.getElementById('probationPagination');
                const pageSize = 10;
                let currentPage = 1;
                let filteredRows = rows;

                function closeProbationMenus() {
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

                function openProbationMenu(dropdown) {
                    const button = dropdown.querySelector('.emp-action-btn');
                    const menu = dropdown.querySelector('.emp-menu');

                    if (!button || !menu) {
                        return;
                    }

                    closeProbationMenus();

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
                    const actionButton = event.target.closest('.emp-page .emp-action-btn');
                    const openMenu = event.target.closest('.emp-page .emp-menu');

                    if (actionButton) {
                        event.preventDefault();
                        event.stopPropagation();

                        const dropdown = actionButton.closest('.dropdown');

                        if (dropdown?.classList.contains('is-open')) {
                            closeProbationMenus();
                        } else if (dropdown) {
                            openProbationMenu(dropdown);
                        }

                        return;
                    }

                    if (!openMenu) {
                        closeProbationMenus();
                    }
                });

                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        closeProbationMenus();
                    }
                });

                window.addEventListener('resize', closeProbationMenus);
                window.addEventListener('scroll', closeProbationMenus, true);

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
                        closeProbationMenus();
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
                    closeProbationMenus();
                    renderTable();
                }

                if (searchInput && rows.length > 0) {
                    searchInput.addEventListener('keyup', filterRows);
                }

                if (rows.length > 0) {
                    renderTable();
                }
            });

            $(document).ready(function() {
                $('#update-status-btn').click(function() {
                    $.ajax({
                        url: '/employee-status',
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function(response) {
                            if (response.updated_users_count > 0) {
                                response.updated_users.forEach(function(user) {
                                    $('#user-status-' + user.id).text('Employee');
                                });
                                alert(response.message);
                            } else {
                                alert('No users were updated.');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error in AJAX request:', error);
                            alert('An error occurred: ' + error);
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
