document.addEventListener('DOMContentLoaded', function() {
    const body = document.getElementById('employeeList');
    const searchUrl = body?.dataset.searchUrl || '';
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
            } [char];
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

    function formatPercent(value) {
        if (value === null || value === undefined || value === '') {
            return '-';
        }

        const stringValue = String(value);
        if (stringValue.includes('%')) {
            return stringValue;
        }

        const number = Number(value);
        if (Number.isNaN(number)) {
            return stringValue;
        }

        const formatted = Number.isInteger(number) ?
            String(number) :
            number.toFixed(2).replace(/\.?0+$/, '');

        return `${formatted}%`;
    }

    function getEmployeeRoles(financial) {
        const roles = financial?.employee?.user_roles;

        if (Array.isArray(roles)) {
            return roles;
        }

        if (!roles) {
            return [];
        }

        try {
            const parsedRoles = JSON.parse(roles);
            return Array.isArray(parsedRoles) ? parsedRoles : [];
        } catch (error) {
            return [];
        }
    }

    function formatReviewPercent(financial, role, value) {
        return getEmployeeRoles(financial).includes(role) ? formatPercent(value) : '-';
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
            <td><span class="appraisal-done-score">${escapeHtml(formatPercent(financial.evaluation_score))}</span></td>
            <td><span class="appraisal-done-score">${escapeHtml(formatReviewPercent(financial, 'hr', financial.hr_review))}</span></td>
            <td><span class="appraisal-done-score">${escapeHtml(formatReviewPercent(financial, 'admin', financial.admin_review))}</span></td>
            <td><span class="appraisal-done-score">${escapeHtml(formatReviewPercent(financial, 'manager', financial.manager_review))}</span></td>
            <td><span class="appraisal-done-score">${escapeHtml(formatReviewPercent(financial, 'client', financial.clint_review))}</span></td>
            <td><span class="appraisal-done-score">${escapeHtml(formatPercent(financial.apprisal_score))}</span></td>
            <td><span class="appraisal-done-money">${escapeHtml(financial.current_salary ?? '-')}</span></td>
            <td><span class="appraisal-done-score">${escapeHtml(formatPercent(financial.percentage_given))}</span></td>
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
            paginationInfo.innerHTML =
                `Showing <strong>${from}</strong> to <strong>${to}</strong> of <strong>${totalRows}</strong> records`;
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
        if (!searchUrl) {
            return;
        }

        const query = searchInput.value.trim();

        body.innerHTML = messageRow('bi-hourglass-split', query ? 'Searching records' : 'Loading records',
            query ? 'Please wait while matching appraisal records are loaded.' :
            'Please wait while appraisal records are loaded.');

        $.ajax({
            url: searchUrl,
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
                    body.innerHTML = messageRow('bi-search', query ? 'No matching data found' :
                        'No appraisal records found',
                        query ? 'Try a different search term.' :
                        'Completed appraisal records will appear here.');
                    if (pagination) {
                        pagination.innerHTML = '';
                    }
                    if (paginationInfo) {
                        paginationInfo.innerHTML =
                            'Showing <strong>0</strong> to <strong>0</strong> of <strong>0</strong> records';
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
