(function () {
    function initEditProbationUser() {
        const form = document.getElementById('editProbationUserForm');
        if (!form) {
            return;
        }
    const saveBtn = document.getElementById('saveBtn');
    const fields = {
        fname: document.getElementById('fname'),
        lname: document.getElementById('lname'),
        employee_id: document.getElementById('employee_id'),
        designation: document.getElementById('designation'),
        dob: document.getElementById('dob'),
        probation_date: document.getElementById('probation_date'),
        salary: document.getElementById('salary'),
        salary_grade: document.getElementById('salary_grade'),
        email: document.getElementById('email'),
        mobno: document.getElementById('mobno'),
        employee_status: document.getElementById('employee_status')
    };
    const employeeStatusHidden = document.getElementById('employee_status_hidden');

    const requiredFields = [
        ['fname', 'First name is required.'],
        ['lname', 'Last name is required.'],
        ['dob', 'Joining date is required.'],
        ['email', 'Email is required.'],
        ['mobno', 'Mobile number is required.']
    ];

    if (!document.getElementById('edit-probation-validation-style')) {
        const style = document.createElement('style');
        style.id = 'edit-probation-validation-style';
        style.textContent = '#editProbationUserForm .field-error{display:block;width:100%;clear:both;margin-top:6px;margin-bottom:12px;color:#dc3545;font-size:13px;font-weight:500;line-height:1.4;}#editProbationUserForm .is-invalid{border-color:#dc3545!important;}#saveBtn:disabled{cursor:not-allowed;opacity:.65;}';
        document.head.appendChild(style);
    }

    function valueOf(field) {
        return ((field && field.value) || '').trim();
    }

    function clearFieldError(field) {
        if (!field) {
            return;
        }

        field.classList.remove('is-invalid');
        const next = field.nextElementSibling;
        if (next && next.classList.contains('field-error')) {
            next.remove();
        }
    }

    function setFieldError(field, message) {
        if (!field) {
            return null;
        }

        clearFieldError(field);
        field.classList.add('is-invalid');

        const error = document.createElement('div');
        error.className = 'field-error';
        error.textContent = message;
        field.insertAdjacentElement('afterend', error);

        return field;
    }

    function clearAllErrors() {
        form.querySelectorAll('.is-invalid').forEach(function (field) {
            field.classList.remove('is-invalid');
        });
        form.querySelectorAll('.field-error').forEach(function (error) {
            error.remove();
        });
    }

    function isFutureDate(value) {
        if (!value) {
            return false;
        }

        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const date = new Date(value);
        date.setHours(0, 0, 0, 0);
        return date > today;
    }

    function validateField(field, showError = true) {
        if (!field || field.disabled) {
            return true;
        }

        const id = field.id;
        const value = valueOf(field);
        const required = requiredFields.find(function (item) {
            return item[0] === id;
        });

        if (required && !value) {
            if (showError) {
                setFieldError(field, required[1]);
            }
            return false;
        }

        if (id === 'fname' && value && !/^[A-Za-z ]+$/.test(value)) {
            if (showError) {
                setFieldError(field, 'First name should contain letters only.');
            }
            return false;
        }

        if (id === 'lname' && value && !/^[A-Za-z ]+$/.test(value)) {
            if (showError) {
                setFieldError(field, 'Last name should contain letters only.');
            }
            return false;
        }

        if (id === 'employee_id' && value && !/^DS\d{5}$/.test(value)) {
            if (showError) {
                setFieldError(field, 'Employee ID must be in the format DS00001.');
            }
            return false;
        }

        if (id === 'dob' && isFutureDate(value)) {
            if (showError) {
                setFieldError(field, 'Joining date cannot be a future date.');
            }
            return false;
        }

        if (id === 'probation_date' && valueOf(fields.dob) && value && dateFromInput(value) < dateFromInput(valueOf(fields.dob))) {
            if (showError) {
                setFieldError(field, 'Probation date cannot be earlier than the joining date.');
            }
            return false;
        }

        if (id === 'salary' && value && (!/^\d+$/.test(value) || Number(value) < 0)) {
            if (showError) {
                setFieldError(field, 'Salary must be a valid positive number.');
            }
            return false;
        }

        if (id === 'email' && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            if (showError) {
                setFieldError(field, 'Enter a valid email address.');
            }
            return false;
        }

        if (id === 'mobno' && value && !/^[6-9]\d{9}$/.test(value)) {
            if (showError) {
                setFieldError(field, 'Enter a valid Indian mobile number.');
            }
            return false;
        }

        if (showError) {
            clearFieldError(field);
        }
        return true;
    }

    function validateForm(showErrors = true) {
        if (showErrors) {
            clearAllErrors();
        }

        const invalidFields = Object.values(fields).filter(function (field) {
            return !validateField(field, showErrors);
        });

        if (showErrors && invalidFields.length) {
            invalidFields[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            invalidFields[0].focus();
        }

        return invalidFields.length === 0;
    }

    function updateSaveButtonState() {
        saveBtn.disabled = false;
    }

    function todayDateOnly() {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        return today;
    }

    function dateFromInput(value) {
        if (!value) {
            return null;
        }

        const parts = value.split('-').map(Number);
        if (parts.length !== 3 || parts.some(Number.isNaN)) {
            return null;
        }

        return new Date(parts[0], parts[1] - 1, parts[2]);
    }

    function formatDateForInput(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function updateEmployeeStatusFromProbationDate() {
        const probationDateValue = valueOf(fields.probation_date);
        let status = 'Probation Period';

        if (probationDateValue) {
            const probationDate = dateFromInput(probationDateValue);

            if (probationDate && probationDate <= todayDateOnly()) {
                status = 'Employee';
            }
        }

        fields.employee_status.value = status;
        employeeStatusHidden.value = status;
    }

    function setProbationDateFromJoiningDate() {
        if (!fields.dob.value) {
            fields.probation_date.value = '';
            updateEmployeeStatusFromProbationDate();
            validateField(fields.probation_date);
            updateSaveButtonState();
            return;
        }

        const probationDate = dateFromInput(fields.dob.value);
        probationDate.setMonth(probationDate.getMonth() + 6);

        fields.probation_date.value = formatDateForInput(probationDate);
        updateEmployeeStatusFromProbationDate();
        validateField(fields.probation_date);
        updateSaveButtonState();
    }

    fields.employee_id.addEventListener('input', function () {
        let value = this.value.replace(/^DS/i, '');
        value = value.replace(/\D/g, '').slice(0, 5);
        this.value = 'DS' + value;
        validateField(this);
        updateSaveButtonState();
    });

    fields.mobno.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 10);
        validateField(this);
        updateSaveButtonState();
    });

    fields.salary.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 10);
        validateField(this);
        updateSaveButtonState();
    });

    ['input', 'change', 'blur'].forEach(function (eventName) {
        fields.dob.addEventListener(eventName, setProbationDateFromJoiningDate);
    });

    Object.values(fields).forEach(function (field) {
        field.addEventListener('input', function () {
            validateField(field);

            if (field.id === 'dob') {
                setProbationDateFromJoiningDate();
            }

            updateSaveButtonState();
        });
        field.addEventListener('change', function () {
            validateField(field);

            if (field.id === 'dob') {
                setProbationDateFromJoiningDate();
            }

            if (field.id === 'probation_date') {
                updateEmployeeStatusFromProbationDate();
                validateField(fields.dob);
            }

            updateSaveButtonState();
        });
    });

    form.addEventListener('submit', function (event) {
        if (!validateForm(true)) {
            event.preventDefault();
            updateSaveButtonState();
        }
    });

    updateEmployeeStatusFromProbationDate();
    updateSaveButtonState();

    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initEditProbationUser);
    } else {
        initEditProbationUser();
    }
})();
