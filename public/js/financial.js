$(document).ready(function () {
    const $financialForm = $('#financial-data');
    const $saveButton = $('#save-financial-data');
    const financialDataUrl = $financialForm.data('financial-data-url') || '';
    const financialStoreUrl = $financialForm.data('financial-store-url') || '';
    const csrfToken = $('meta[name="csrf-token"]').attr('content') || '';
    const saveButtonHtml = $saveButton.html() || 'Save';
    const savingButtonHtml = '<i class="bi bi-hourglass-split"></i> Saving...';
    const alreadySubmittedText = 'Already Submitted';

    function showSweetAlert(icon, title, text) {
        if (typeof Swal !== 'undefined') {
            return Swal.fire({
                icon: icon,
                title: title,
                text: text
            });
        }

        alert(text);
        return Promise.resolve();
    }

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, function (char) {
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

    function setSaveButtonReady() {
        $saveButton
            .prop('disabled', false)
            .removeData('already-submitted')
            .removeAttr('title')
            .html(saveButtonHtml);
    }

    function setSaveButtonDisabled(label, title) {
        $saveButton
            .prop('disabled', true)
            .removeData('already-submitted')
            .attr('title', title || '')
            .text(label || 'Save');
    }

    function setSaveButtonAlreadySubmitted(financialYear) {
        const yearText = financialYear ? ` for ${financialYear}` : '';

        $saveButton
            .prop('disabled', true)
            .data('already-submitted', true)
            .attr('title', `Appraisal already submitted${yearText}.`)
            .text(alreadySubmittedText);
    }

    function renderMessage(icon, title, message) {
        $('#appraisal-body').html(`
            <tr>
                <td colspan="14">
                    <div class="financial-message">
                        <i class="bi ${icon}"></i>
                        <h5>${escapeHtml(title)}</h5>
                        <p>${escapeHtml(message)}</p>
                    </div>
                </td>
            </tr>
        `);
    }

    function scoreCell(className, value) {
        const displayValue = `${Number(value).toFixed(2)}%`;
        return `<span class="${className} financial-score">${displayValue}</span>`;
    }

    function pendingCell() {
        return '<span class="financial-pending">Pending</span>';
    }

    function reviewCell(className, value) {
        const numericValue = Number(value);

        if (!Number.isFinite(numericValue) || numericValue <= 0) {
            return pendingCell();
        }

        return scoreCell(className, numericValue);
    }

    function moneyCell(className, value) {
        const displayValue = `₹${Math.floor(Number(value) || 0)}`;
        return `<span class="${className} financial-money">${displayValue}</span>`;
    }

    function fetchEmployeeData() {
        const employeeSearch = $('#employee_search').val().trim();
        const financialYear = $('#financialYear').val();

        if (!employeeSearch || !financialYear) {
            setSaveButtonDisabled('Save', 'Search employee appraisal data before saving.');
            renderMessage('bi-search', 'Search for an employee',
                'Enter Employee ID/Name and select Financial Year to view data.');
            return;
        }

        setSaveButtonDisabled('Save', 'Financial data is loading.');
        renderMessage('bi-hourglass-split', 'Loading financial data',
            'Please wait while the salary and appraisal data are loaded.');

        $.ajax({
            url: financialDataUrl,
            method: 'GET',
            data: {
                search: employeeSearch,
                financial_year: financialYear
            },
            success: function (response) {
                let tableRows = '';
                let userType = response.user_type;

                const employeeName = response.employee_name || 'N/A';
                const employeeId = response.employee_id || 'N/A';
                const safeEmployeeName = escapeHtml(employeeName);
                const safeEmployeeId = escapeHtml(employeeId);
                const evaluationScore = parseFloat(response.evaluationScore) || 0;

                const hrReview = parseFloat(response.hrReviewData?.[0] || 0);
                const adminReview = parseFloat(response.adminReviewData?.[0] || 0);
                const managerReview = parseFloat(response.managerReviewData || 0);
                const clientReviewValue = parseFloat(response.clientReviewData || 0);

                const baseSalary = parseFloat(response.salary) || 0;
                const percentage = parseFloat(response.company_percentage) || 0;

                let showHRReview = response.showHRColumn ?? (userType !== 'hr' && hrReview > 0);
                let showAdminReview = response.showAdminColumn ?? (userType !== 'admin' && adminReview > 0);
                let showManagerReview = response.showManagerColumn ?? (!(userType === 'hr' || userType === 'admin' ||
                    userType === 'manager') && managerReview > 0);
                let showClientReview = response.showClientColumn ?? (clientReviewValue > 0);

                $('#hr-review-header').toggle(showHRReview);
                $('#admin-review-header').toggle(showAdminReview);
                $('#manager-review-header').toggle(showManagerReview);
                $('#client-review-header').toggle(showClientReview);

                const avgReviewPercentage = parseFloat(response.appraisalScore) || 0;
                const updatedSalary = parseFloat(response.updatedSalary) || 0;
                const finalSalary = parseFloat(response.finalSalary) || 0;
                const appraisalDate = response.appraisalDate || 'N/A';
                const selectedYear = $('#financialYear').val();
                const initials = escapeHtml(getInitials(employeeName));
                const avatarColor = getAvatarColor(employeeName);

                tableRows += `<tr class="emp-row">
                    <td>
                        <div class="emp-person">
                            <div class="emp-avatar" style="background:${avatarColor};">${initials}</div>
                            <div>
                                <div class="emp-person-name employeeName">${safeEmployeeName}</div>
                                <div class="emp-person-meta">${safeEmployeeId}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="employeeId financial-muted">${safeEmployeeId}</span></td>
                    <td>${scoreCell('EvaluationScore', evaluationScore)}</td>
                    ${showHRReview ? `<td>${reviewCell('hrReview', hrReview)}</td>` : ''}
                    ${showAdminReview ? `<td>${reviewCell('adminReview', adminReview)}</td>` : ''}
                    ${showManagerReview ? `<td>${reviewCell('managerReview', managerReview)}</td>` : ''}
                    ${showClientReview ? `<td>${reviewCell('clientReview', clientReviewValue)}</td>` : ''}
                    <td>${scoreCell('avgReview', avgReviewPercentage)}</td>
                    <td>${moneyCell('currentSalary', baseSalary)}</td>
                    <td><span class="percentage financial-percent">${percentage.toFixed(2)}%</span></td>
                    <td>${moneyCell('updated-salary', updatedSalary)}</td>
                    <td>${moneyCell('final-salary', finalSalary)}</td>
                    <td><span class="appraisal-date financial-muted">${escapeHtml(appraisalDate)}</span></td>
                    <td><span class="financial-year financial-muted">${escapeHtml(selectedYear)}</span></td>
                </tr>`;
                $('#appraisal-body').html(tableRows);

                if (response.alreadyAppraised) {
                    setSaveButtonAlreadySubmitted(selectedYear);
                    return;
                }

                if (response.allRequiredReviewsCompleted === false) {
                    const missingReviews = Array.isArray(response.missingReviews) && response.missingReviews.length > 0
                        ? response.missingReviews.join(', ')
                        : 'required reviews';
                    setSaveButtonDisabled('Save', `Complete pending reviews before saving: ${missingReviews}.`);
                    return;
                }

                setSaveButtonReady();
            },
            error: function (xhr) {
                const errorMsg = xhr.responseJSON?.message || 'Error fetching data';
                setSaveButtonDisabled('Save', 'Load employee appraisal data before saving.');
                renderMessage('bi-exclamation-circle', 'Unable to load financial data', errorMsg);
            }
        });
    }

    $('#employee_search').on('input', fetchEmployeeData);
    $('#financialYear').on('change', fetchEmployeeData);

    $financialForm.on('submit', function (e) {
        e.preventDefault();
        $saveButton.trigger('click');
    });

    $saveButton.click(function (e) {
        e.preventDefault();

        const button = $(this);
        if (button.prop('disabled') || button.data('already-submitted')) {
            return;
        }

        button.prop('disabled', true).html(savingButtonHtml);
        const selectedFinancialYear = $('#financialYear').val();

        if (!selectedFinancialYear) {
            showSweetAlert('error', 'Validation Error', 'Please select a financial year.');
            setSaveButtonDisabled('Save', 'Select a financial year before saving.');
            return;
        }

        const employees = [];
        $('#appraisal-body tr.emp-row').each(function () {
            const row = $(this);
            const employee = {
                employee_name: row.find('.employeeName').text().trim(),
                emp_id: row.find('.employeeId').text().trim(),
                evaluation_score: parseFloat(row.find('.EvaluationScore').text()) || 0,
                hr_review: parseFloat(row.find('.hrReview').text()) || 0,
                admin_review: parseFloat(row.find('.adminReview').text()) || 0,
                manager_review: parseFloat(row.find('.managerReview').text()) || 0,
                client_review: parseFloat(row.find('.clientReview').text()) || 0,
                apprisal_score: parseFloat(row.find('.avgReview').text()) || 0,
                current_salary: parseFloat(row.find('.currentSalary').text().replace('₹', '').trim()) || 0,
                percentage_given: parseFloat(row.find('.percentage').text()) || 0,
                update_salary: parseFloat(row.find('.updated-salary').text().replace('₹', '').trim()) || 0,
                final_salary: parseFloat(row.find('.final-salary').text().replace('₹', '').trim()) || 0,
                apprisal_date: row.find('.appraisal-date').text() || 'N/A',
                financial_year: selectedFinancialYear || 'N/A'
            };
            employees.push(employee);
        });

        if (employees.length === 0) {
            showSweetAlert('error', 'Validation Error', 'No employee data to save!');
            setSaveButtonDisabled('Save', 'Search employee appraisal data before saving.');
            return;
        }

        $.ajax({
            url: financialStoreUrl,
            method: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify({
                _token: csrfToken,
                employees: employees
            }),
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message || 'Data saved successfully!',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    setSaveButtonAlreadySubmitted(selectedFinancialYear);
                    location.reload();
                });
            },
            error: function (xhr) {
                let errorMessage = 'An error occurred. Please try again.';
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.message) {
                        errorMessage = response.message;
                    }
                } catch (error) {
                    console.error('Failed to parse error JSON:', error);
                }

                showSweetAlert('error', 'Error', errorMessage);

                if (xhr.status === 400 && errorMessage.toLowerCase().includes('already has an appraisal')) {
                    setSaveButtonAlreadySubmitted(selectedFinancialYear);
                    return;
                }

                setSaveButtonReady();
            }
        });
    });

    setSaveButtonDisabled('Save', 'Search employee appraisal data before saving.');
});
