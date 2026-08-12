@extends('layouts.app')

@section('title', 'Client Management')
@section('breadcrumb', 'Client Management')
@section('page-title', 'Client Management')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <link href="{{ asset('css/client-management.css') }}?v={{ filemtime(public_path('css/client-management.css')) }}"
            rel="stylesheet">
    @endpush

    <div class="emp-page">
        <div class="emp-shell">
            <div class="emp-header">
                <div class="emp-header-text">
                    <h1>Client Management</h1>
                    <p>Manage client records, company details and account status.</p>
                </div>

                <div class="emp-header-actions">
                    <div class="emp-search">
                        <input type="text" id="clientSearch" placeholder="Search client, company, email...">
                        <i class="bi bi-search"></i>
                    </div>

                    @if (canAccess(3))
                        <a href="{{ route('create-client') }}" class="emp-add-btn">
                            <i class="nav-icon bi bi-person-plus"></i>
                            Add Client
                        </a>
                    @endif
                </div>
            </div>

            <div class="emp-card">
                <div class="emp-table-scroll">
                    <table class="emp-table" id="clientTable">
                        <thead>
                            <tr>
                                <th style="width:260px;">Client</th>
                                <th>Company</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th class="emp-th-right" style="width:60px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($allClients as $client)
                                @php
                                    $clientName = trim($client->client_name ?? '');
                                    $initials = strtoupper(substr($clientName, 0, 1));

                                    if ($initials == '') {
                                        $initials = 'C';
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
                                    $avatarColor = $avatarPalette[crc32($clientName ?: 'C') % count($avatarPalette)];
                                @endphp

                                <tr class="emp-row" data-status="{{ $client->status ? '1' : '2' }}">
                                    <td>
                                        <div class="emp-person">
                                            <div class="emp-avatar" style="background:{{ $avatarColor }};">
                                                {{ $initials }}
                                            </div>
                                            <div>
                                                <div class="emp-person-name">{{ $client->client_name }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="emp-designation">{{ $client->company_name ?? '-' }}</span>
                                    </td>

                                    <td>
                                        @php
                                            $clientMobile = trim((string) ($client->client_mobno ?? ''));
                                            $clientEmail = trim((string) ($client->client_email ?? ''));
                                        @endphp

                                        @if ($clientMobile !== '')
                                            <div class="emp-copy-row">
                                                <i class="bi bi-telephone emp-inline-icon"></i>
                                                <span class="emp-copy-text">{{ $clientMobile }}</span>
                                                <button class="emp-copy-btn" data-copy="{{ $clientMobile }}"
                                                    title="Copy Mobile">
                                                    <i class="bi bi-copy"></i>
                                                </button>
                                            </div>
                                        @endif

                                        @if ($clientEmail !== '')
                                            <div class="emp-copy-row">
                                                <i class="bi bi-envelope emp-inline-icon"></i>
                                                <span class="emp-copy-text"
                                                    title="{{ $clientEmail }}">{{ $clientEmail }}</span>
                                                <button class="emp-copy-btn" data-copy="{{ $clientEmail }}"
                                                    title="Copy Email">
                                                    <i class="bi bi-copy"></i>
                                                </button>
                                            </div>
                                        @endif

                                        @if ($clientMobile === '' && $clientEmail === '')
                                            <span>—</span>
                                        @endif

                                    </td>


                                    <td class="status-class" id="status-{{ $client->id }}">
                                        @if ($client->status)
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

                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="emp-action-btn" type="button" aria-expanded="false"
                                                title="More actions">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end emp-menu">
                                                <li>
                                                    <a href="{{ route('edit-client', $client->id) }}"
                                                        class="dropdown-item">
                                                        <i class="bi bi-pencil-square"></i>
                                                        Edit Client
                                                    </a>
                                                </li>

                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>

                                                <li>
                                                    <div class="emp-menu-label">Account Status</div>
                                                    <div class="emp-menu-toggle-row">
                                                        <span>{{ $client->status ? 'Active' : 'Inactive' }}</span>
                                                        <div class="form-check form-switch m-0 d-flex justify-content-end">
                                                            <input class="form-check-input status-switch" type="checkbox"
                                                                role="switch" data-user-type="client"
                                                                data-identifier="{{ $client->id }}"
                                                                {{ $client->status ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="emp-empty">
                                            <i class="bi bi-people"></i>
                                            <h5>No clients found</h5>
                                            <p>There are currently no clients available.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                            @if (count($allClients) > 0)
                                <tr id="clientNoResults" hidden>
                                    <td colspan="5">
                                        <div class="emp-empty">
                                            <i class="bi bi-search"></i>
                                            <h5>No matching clients</h5>
                                            <p>Try a different search term.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                @if (count($allClients) > 0)
                    <div class="emp-footer">
                        <div class="emp-footer-count" id="clientPaginationInfo"></div>
                        <div class="emp-pagination" id="clientPagination"></div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            function getToggleStatusUrl(user_type, id) {
                if (user_type === "client") {
                    return "{{ url('/toggle-status-client') }}/" + id;
                }

                return "/toggle-status/" + user_type + "/" + id;
            }

            $(document).ready(function() {
                const rows = Array.from(document.querySelectorAll('#clientTable tbody tr.emp-row'));
                const searchInput = document.getElementById('clientSearch');
                const noResultsRow = document.getElementById('clientNoResults');
                const paginationInfo = document.getElementById('clientPaginationInfo');
                const pagination = document.getElementById('clientPagination');
                const pageSize = 10;
                let currentPage = 1;
                let filteredRows = rows;

                function closeClientMenus() {
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

                function openClientMenu(dropdown) {
                    const button = dropdown.querySelector('.emp-action-btn');
                    const menu = dropdown.querySelector('.emp-menu');

                    if (!button || !menu) {
                        return;
                    }

                    closeClientMenus();

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

                        if (dropdown?.classList.contains('is-open')) {
                            closeClientMenus();
                        } else if (dropdown) {
                            openClientMenu(dropdown);
                        }

                        return;
                    }

                    if (!openMenu) {
                        closeClientMenus();
                    }
                });

                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        closeClientMenus();
                    }
                });

                window.addEventListener('resize', closeClientMenus);
                window.addEventListener('scroll', closeClientMenus, true);

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
                        closeClientMenus();
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
                            `Showing <strong>${from}</strong> to <strong>${to}</strong> of <strong>${totalRows}</strong> clients`;
                    }

                    renderPagination(totalPages);
                }

                function filterRows() {
                    const keyword = (searchInput?.value || '').toLowerCase().trim();

                    filteredRows = rows.filter(function(row) {
                        return row.innerText.toLowerCase().includes(keyword);
                    });

                    currentPage = 1;
                    closeClientMenus();
                    renderTable();
                }

                if (searchInput && rows.length > 0) {
                    searchInput.addEventListener('keyup', filterRows);
                }

                if (rows.length > 0) {
                    renderTable();
                }

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

                        closeClientMenus();
                    });
                }

                document.querySelectorAll('.emp-copy-btn').forEach(bindCopyHandler);
            });

            $(document).on("change", ".status-switch", function() {
                let identifier = $(this).data("identifier");
                let user_type = $(this).data("user-type");
                let button = $(this);
                let row = button.closest("tr.emp-row");
                let badge = row.find(".status-class");
                let menuLabel = button.closest(".emp-menu-toggle-row").find("span");
                const previousState = !button.prop("checked");

                $.ajax({
                    url: getToggleStatusUrl(user_type, identifier),
                    type: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    beforeSend: function() {
                        button.prop("disabled", true);
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
                                row.attr("data-status", "1");
                                menuLabel.text("Active");
                                button.prop("checked", true);
                            } else {
                                badge.html(`
                                    <span class="emp-status is-inactive">
                                        <span class="emp-status-dot"></span>
                                        Inactive
                                    </span>
                                `);
                                row.attr("data-status", "2");
                                menuLabel.text("Inactive");
                                button.prop("checked", false);
                            }
                        } else {
                            alert("Failed to update status: " + response.error);
                            button.prop("checked", previousState);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", error);
                        alert("Error toggling status");
                        button.prop("checked", previousState);
                    },
                    complete: function() {
                        button.prop("disabled", false);
                    }
                });
            });
        </script>
    @endpush
@endsection
