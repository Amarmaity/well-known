$(function () {
    const $userForm = $('#userForm');
    const $saveBtn = $('#saveBtn');
    const $designationDropdown = $('#designation_dropdown');
    const $userTypeDropdown = $('#user_type_dropdown');
    const $userTypeHidden = $('#user_type_hidden');
    const $reviewSection = $('#review-section');
    const $managerSearch = $('#search_manager_div');
    const $managerName = $('#manager_name_div');
    const $adminSearch = $('#search_admin_div');
    const $hrSearch = $('#search_hr_div');
    const $clientCheckbox = $('#client-checkbox');
    const $clientSelectDiv = $('#client_select_div');

    function normalize(value) {
        return (value || '').toString().trim().toLowerCase().replace(/\s+/g, ' ');
    }

    function showToast(icon, title, text) {
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            confirmButtonText: 'OK'
        });
    }

    function todayString() {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function isFutureDate(value) {
        return value && value > todayString();
    }

    function getErrorTarget($field) {
        if ($field.hasClass('select2-hidden-accessible')) {
            return $field.next('.select2-container');
        }

        return $field;
    }

    function getErrorPlacement($field) {
        if ($field.closest('#review-section').length) {
            const $lastRole = $field.closest('#review-section').find('.form-check:visible').last();
            return $lastRole.length ? $lastRole : $field.closest('#review-section');
        }

        return getErrorTarget($field);
    }

    function clearFieldError($field) {
        if (!$field || !$field.length) {
            return;
        }

        const $target = getErrorTarget($field);
        const $placement = getErrorPlacement($field);
        $field.removeClass('is-invalid');
        $target.removeClass('is-invalid');
        $target.next('.field-error').remove();
        $placement.next('.field-error').remove();
    }

    function setFieldError(selector, message) {
        const $field = $(selector).first();

        if (!$field.length) {
            return null;
        }

        clearFieldError($field);

        const $target = getErrorTarget($field);
        const $placement = getErrorPlacement($field);
        $field.addClass('is-invalid');
        $target.addClass('is-invalid');
        $('<div class="field-error"></div>').text(message).insertAfter($placement);

        return $field;
    }

    function clearAllFieldErrors() {
        $userForm.find('.is-invalid').removeClass('is-invalid');
        $userForm.find('.field-error').remove();
    }

    function focusFirstError($field) {
        if (!$field || !$field.length) {
            return;
        }

        const $target = getErrorTarget($field);
        const targetOffset = $target.offset();

        if (targetOffset) {
            $('html, body').animate({
                scrollTop: Math.max(targetOffset.top - 120, 0)
            }, 250);
        }

        if ($field.hasClass('select2-hidden-accessible')) {
            $field.select2('open');
            return;
        }

        $field.trigger('focus');
    }

    function isFieldActive($field) {
        if (!$field.length || $field.prop('disabled')) {
            return false;
        }

        if ($field.hasClass('select2-hidden-accessible')) {
            return $field.next('.select2-container').is(':visible');
        }

        return $field.is(':visible');
    }

    function validateRequiredField(selector, message) {
        const $field = $(selector).first();

        if (!isFieldActive($field)) {
            clearFieldError($field);
            return null;
        }

        const value = Array.isArray($field.val()) ? $field.val().filter(Boolean) : $.trim($field.val() || '');

        if (!value || value.length === 0) {
            return setFieldError(selector, message);
        }

        clearFieldError($field);
        return null;
    }

    const requiredFields = [
        ['#fname', 'First name is required.'],
        ['#lname', 'Last name is required.'],
        ['#dob', 'Joining date is required.'],
        ['#gender', 'Please select gender.'],
        ['#mobno', 'Mobile number is required.'],
        ['#email', 'Email is required.'],
        ['#designation_dropdown', 'Please select designation.'],
        ['#division_dropdown', 'Please select division.'],
        ['#manager_name', 'Please select manager.'],
        ['#manager_name_input', 'Manager name is required.'],
        ['#hr_id', 'Please select HR.'],
        ['#employee_id', 'Employee ID is required.'],
        ['#client_id', 'Please select client.'],
        ['#admin_id', 'Please select admin.'],
        ['#probation_date', 'Probation date is required.'],
        ['#salary', 'Salary is required.'],
        ['#salary_grade', 'Salary grade is required.'],
        ['#password', 'Password is required.'],
        ['#cnf-password', 'Confirm password is required.']
    ];

    function getRequiredFieldConfig($field) {
        return requiredFields.find(function (field) {
            const $requiredField = $(field[0]).first();
            return $requiredField.length && $requiredField[0] === $field[0];
        });
    }

    function getFieldValue($field) {
        const value = $field.val();
        return Array.isArray(value) ? value.filter(Boolean) : $.trim(value || '');
    }

    function validateFieldInstant($field) {
        if (!$field || !$field.length || !isFieldActive($field)) {
            clearFieldError($field);
            return true;
        }

        const fieldId = $field.attr('id');
        const value = getFieldValue($field);
        const requiredConfig = getRequiredFieldConfig($field);

        if (requiredConfig && (!value || value.length === 0)) {
            setFieldError('#' + fieldId, requiredConfig[1]);
            return false;
        }

        if (fieldId === 'fname' && value && !/^[A-Za-z ]+$/.test(value)) {
            setFieldError('#fname', 'First name should contain letters only.');
            return false;
        }

        if (fieldId === 'lname' && value && !/^[A-Za-z ]+$/.test(value)) {
            setFieldError('#lname', 'Last name should contain letters only.');
            return false;
        }

        if (fieldId === 'mobno' && value && !/^[6-9]\d{9}$/.test(value)) {
            setFieldError('#mobno', 'Enter a valid Indian mobile number.');
            return false;
        }

        if (fieldId === 'email' && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            setFieldError('#email', 'Enter a valid email address.');
            return false;
        }

        if (fieldId === 'dob' && isFutureDate(value)) {
            setFieldError('#dob', 'Joining date cannot be a future date.');
            return false;
        }

        if (fieldId === 'probation_date') {
            const joiningDateVal = $('#dob').val();
            if (joiningDateVal && value && new Date(value) < new Date(joiningDateVal)) {
                setFieldError('#probation_date', 'Probation date cannot be earlier than the joining date.');
                return false;
            }
        }

        if ((fieldId === 'password' || fieldId === 'cnf-password') && $('#password').val() && $('#cnf-password').val()) {
            if ($('#password').val() !== $('#cnf-password').val()) {
                setFieldError('#cnf-password', 'Passwords do not match.');
                return false;
            }

            clearFieldError($('#cnf-password'));
        }

        clearFieldError($field);
        return true;
    }

    function validateUserForm() {
        clearAllFieldErrors();

        const errors = [];
        requiredFields.forEach(function (field) {
            const errorField = validateRequiredField(field[0], field[1]);
            if (errorField) {
                errors.push(errorField);
            }
        });
        const firstName = $.trim($('#fname').val() || '');
        if (firstName && !/^[A-Za-z ]+$/.test(firstName)) {
            errors.push(setFieldError('#fname', 'First name should contain letters only.'));
        }

        const lastName = $.trim($('#lname').val() || '');
        if (lastName && !/^[A-Za-z ]+$/.test(lastName)) {
            errors.push(setFieldError('#lname', 'Last name should contain letters only.'));
        }

        const mobile = $.trim($('#mobno').val() || '');
        if (mobile && !/^[6-9]\d{9}$/.test(mobile)) {
            errors.push(setFieldError('#mobno', 'Enter a valid Indian mobile number.'));
        }

        const email = $.trim($('#email').val() || '');
        if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            errors.push(setFieldError('#email', 'Enter a valid email address.'));
        }

        const joiningDateVal = $('#dob').val();
        if (isFutureDate(joiningDateVal)) {
            errors.push(setFieldError('#dob', 'Joining date cannot be a future date.'));
        }

        const probationDateVal = $('#probation_date').val();
        if (joiningDateVal && probationDateVal) {
            const joiningDate = new Date(joiningDateVal);
            const probationDate = new Date(probationDateVal);

            if (probationDate < joiningDate) {
                errors.push(setFieldError('#probation_date', 'Probation date cannot be earlier than the joining date.'));
            }
        }

        const password = $('#password').val();
        const confirmPassword = $('#cnf-password').val();
        if (password && confirmPassword && password !== confirmPassword) {
            errors.push(setFieldError('#cnf-password', 'Passwords do not match.'));
        }

        if (errors.length) {
            focusFirstError(errors[0]);
            return false;
        }

        return true;
    }

    function hasFieldValue(selector) {
        const $field = $(selector).first();

        if (!isFieldActive($field)) {
            return true;
        }

        const value = Array.isArray($field.val()) ? $field.val().filter(Boolean) : $.trim($field.val() || '');
        return !!value && value.length !== 0;
    }

    function canSubmitForm() {
        const hasRequiredValues = requiredFields.every(function (field) {
            return hasFieldValue(field[0]);
        });

        if (!hasRequiredValues) {
            return false;
        }

        const firstName = $.trim($('#fname').val() || '');
        const lastName = $.trim($('#lname').val() || '');
        const mobile = $.trim($('#mobno').val() || '');
        const email = $.trim($('#email').val() || '');
        const joiningDateVal = $('#dob').val();
        const probationDateVal = $('#probation_date').val();
        const password = $('#password').val();
        const confirmPassword = $('#cnf-password').val();

        if (!/^[A-Za-z ]+$/.test(firstName) || !/^[A-Za-z ]+$/.test(lastName)) {
            return false;
        }

        if (!/^[6-9]\d{9}$/.test(mobile)) {
            return false;
        }

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            return false;
        }

        if (isFutureDate(joiningDateVal)) {
            return false;
        }

        if (joiningDateVal && probationDateVal && new Date(probationDateVal) < new Date(joiningDateVal)) {
            return false;
        }

        return password === confirmPassword;
    }

    function updateSaveButtonState() {
        $saveBtn.prop('disabled', !canSubmitForm());
    }

    function selectorForField(fieldName) {
        const selectors = {
            fname: '#fname',
            lname: '#lname',
            dob: '#dob',
            gender: '#gender',
            mobno: '#mobno',
            email: '#email',
            designation: '#designation_dropdown',
            division: '#division_dropdown',
            manager_id: '#manager_name',
            manager_name: '#manager_name_input',
            hr_id: '#hr_id',
            employee_id: '#employee_id',
            evaluation_purpose: '#evaluation_purpose',
            client_id: '#client_id',
            admin_id: '#admin_id',
            user_type: '#user_type_hidden',
            user_roles: '#review-section input[name="user_roles[]"]',
            probation_date: '#probation_date',
            salary: '#salary',
            salary_grade: '#salary_grade',
            password: '#password'
        };

        return selectors[fieldName] || selectors[fieldName.replace(/\.\d+$/, '')] || `[name="${fieldName}"]`;
    }

    function showServerFieldErrors(errors) {
        clearAllFieldErrors();

        let firstErrorField = null;

        Object.keys(errors || {}).forEach(function (fieldName) {
            const selector = selectorForField(fieldName);
            const message = Array.isArray(errors[fieldName]) ? errors[fieldName][0] : errors[fieldName];
            const $field = setFieldError(selector, message || 'This field is invalid.');

            if (!firstErrorField && $field && $field.length) {
                firstErrorField = $field;
            }
        });

        focusFirstError(firstErrorField);
    }

    $('#dob').attr('max', todayString());

    if (!document.getElementById('add-user-validation-style')) {
        $('head').append('<style id="add-user-validation-style">#userForm .field-error{display:block;width:100%;clear:both;margin-top:6px;margin-bottom:12px;color:#dc3545;font-size:13px;font-weight:500;line-height:1.4;}#userForm .field-error + .forms-label{margin-top:4px;}#userForm .is-invalid:not(.select2-container){border-color:#dc3545!important;}#userForm .select2-container.is-invalid .select2-selection{border-color:#dc3545!important;box-shadow:0 0 0 .2rem rgba(220,53,69,.12)!important;}#saveBtn:disabled{cursor:not-allowed;opacity:.65;}</style>');
    }

    updateSaveButtonState();

    function syncClientSelect() {
        const shouldShow = $clientCheckbox.is(':checked') && $clientCheckbox.closest('.form-check').is(':visible');

        $clientSelectDiv.toggle(shouldShow);

        if (!shouldShow) {
            $('#client_id').val(null).trigger('change');
            clearFieldError($('#client_id'));
        }

        updateSaveButtonState();
    }

    function syncDesignationUi() {
        const selected = normalize($designationDropdown.val());

        $reviewSection.show();
        $reviewSection.find('.form-check').show();
        $managerSearch.show();
        $managerName.hide();
        $adminSearch.show();
        $hrSearch.show();

        if (selected === 'client') {
            $reviewSection.hide();
            $clientCheckbox.prop('checked', false);
            syncClientSelect();
            updateSaveButtonState();
            return;
        }

        if (['manager', 'admin', 'hr'].includes(selected)) {
            $managerSearch.hide();
            $managerName.hide();
            $('#manager_name').val(null).trigger('change');
            $('#manager_name_input').val('');
        }

        if (selected === 'admin') {
            $adminSearch.hide();
        }

        if (selected === 'hr') {
            $hrSearch.hide();
        }

        const hideReviewRoles = ['hr', 'manager', 'client', 'admin'];
        if (hideReviewRoles.includes(selected)) {
            $clientCheckbox.prop('checked', false).closest('.form-check').hide();
        }

        if (selected === 'users') {
            $('#users').prop('checked', false).closest('.form-check').hide();
        } else if (selected === 'admin') {
            ['admin', 'users', 'manager', 'client'].forEach(function (id) {
                $('#' + id).prop('checked', false).closest('.form-check').hide();
            });
            $('#hr').closest('.form-check').show();
        } else if (selected === 'hr') {
            ['hr', 'users', 'manager', 'client'].forEach(function (id) {
                $('#' + id).prop('checked', false).closest('.form-check').hide();
            });
            $('#admin').closest('.form-check').show();
        } else if (selected === 'manager') {
            ['manager', 'client', 'users'].forEach(function (id) {
                $('#' + id).closest('.form-check').hide();
            });
            ['admin', 'hr'].forEach(function (id) {
                $('#' + id).closest('.form-check').show();
            });
        }

        const hideUsersFor = [
            'hr',
            'admin',
            'seo',
            'ui/ux designer',
            'quality analyst',
            'software developer',
            'business development',
            'manager'
        ];

        if (hideUsersFor.includes(selected)) {
            $('#users').closest('.form-check').hide();
        }

        const userTypeMap = {
            hr: 'hr',
            admin: 'admin',
            manager: 'manager'
        };

        const userType = userTypeMap[selected] || 'users';
        $userTypeDropdown.val(userType).prop('disabled', true);
        $userTypeHidden.val(userType);
        syncClientSelect();
    }

    $designationDropdown.on('change', function () {
        syncDesignationUi();
        clearFieldError($(this));
    });
    $clientCheckbox.on('change', syncClientSelect);
    $userForm.on('input change select2:select select2:clear', 'input, select, textarea', function () {
        validateFieldInstant($(this));

        if (this.id === 'password') {
            validateFieldInstant($('#cnf-password'));
        }

        if (this.id === 'dob') {
            validateFieldInstant($('#probation_date'));
        }

        updateSaveButtonState();
    });
    syncDesignationUi();
    updateSaveButtonState();

    $('#employee_id').on('input', function () {
        let value = $(this).val().replace(/^DS/i, '');
        value = value.replace(/\D/g, '');
        $(this).val('DS' + value);
    });

    const mobInput = document.getElementById('mobno');
    if (mobInput) {
        mobInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 10);
        });
    }

    const salaryInput = document.getElementById('salary');
    if (salaryInput) {
        salaryInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 10);
        });
    }

    // $('#salary').on('input', function () {
    //     const monthlySalary = parseFloat($(this).val()) || 0;
    //     const annualCTC = monthlySalary * 12;
    //     let grade = '';

    //     if (annualCTC < 200000) grade = 'F';
    //     else if (annualCTC <= 349999) grade = 'E';
    //     else if (annualCTC <= 499999) grade = 'D';
    //     else if (annualCTC <= 649999) grade = 'C';
    //     else if (annualCTC <= 900000) grade = 'B';
    //     else grade = 'A';

    //     $('#salary_grade').val(grade);
    // });
    $('#salary').on('input', function () {
        const annualCTC = parseFloat($(this).val()) || 0;
        let grade = '';

        if (annualCTC < 200000) {
            grade = 'F';
        } else if (annualCTC >= 200000 && annualCTC <= 349999) {
            grade = 'E';
        } else if (annualCTC >= 350000 && annualCTC <= 499999) {
            grade = 'D';
        } else if (annualCTC >= 500000 && annualCTC <= 649999) {
            grade = 'C';
        } else if (annualCTC >= 650000 && annualCTC <= 900000) {
            grade = 'B';
        } else {
            grade = 'A';
        }

        $('#salary_grade').val(grade);
        clearFieldError($('#salary_grade'));
        updateSaveButtonState();
    });

    $('#client_id').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select Client',
        allowClear: true,
        maximumSelectionLength: 10,
        ajax: {
            url: $('#client_id').data('route') || window.routes?.getClients || '',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (client) {
                        return {
                            id: client.id,
                            text: client.client_name + ' (' + client.company_name + ')',
                            client_name: client.client_name,
                            company_name: client.company_name
                        };
                    })
                };
            },
            cache: true
        },
        templateResult: function (data) {
            if (!data.id) return data.text;
            return $('<div><strong>' + data.client_name + '</strong><br><small>' + data.company_name + '</small></div>');
        },
        templateSelection: function (data) {
            return data.text || data.client_name;
        }
    });

    $('#manager_name').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select Manager',
        allowClear: true,
        ajax: {
            url: window.routes?.getManager || '',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return { results: data };
            },
            cache: true
        },
        templateResult: function (data) {
            if (!data.id) return data.text;
            return $('<div><strong>' + data.text + '</strong></div>');
        },
        templateSelection: function (data) {
            return data.text || data.id;
        }
    });

    $('#admin_id').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select Admin',
        allowClear: true,
        ajax: {
            url: window.routes?.getAdmins || '',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return { results: data };
            },
            cache: true
        },
        templateResult: function (data) {
            if (!data.id) return data.text;
            return $('<div><strong>' + data.text + '</strong></div>');
        },
        templateSelection: function (data) {
            return data.text || data.id;
        }
    });

    $('#hr_id').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select HR',
        allowClear: true,
        ajax: {
            url: window.routes?.getHrs || '',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return { results: data };
            },
            cache: true
        },
        templateResult: function (data) {
            if (!data.id) return data.text;
            return $('<div><strong>' + data.text + '</strong></div>');
        },
        templateSelection: function (data) {
            return data.text || data.id;
        }
    });

    $('#dob').on('change', function () {
        const joiningDate = $(this).val();
        if (!joiningDate) {
            $('#probation_date').val('');
            return;
        }

        if (isFutureDate(joiningDate)) {
            $(this).val('');
            $('#probation_date').val('');
            setFieldError('#dob', 'Joining date cannot be a future date.');
            focusFirstError($('#dob'));
            return;
        }

        const date = new Date(joiningDate);
        date.setMonth(date.getMonth() + 6);

        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        $('#probation_date').val(`${year}-${month}-${day}`);
        updateSaveButtonState();
    });

    $userForm.on('submit', function (e) {
        e.preventDefault();

        if (!validateUserForm()) {
            return;
        }

        $saveBtn.prop('disabled', true).text('Saving...');

        Swal.fire({
            title: 'Saving user',
            text: 'Please wait while the user is being saved.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: function () {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: $userForm.attr('action'),
            type: 'POST',
            data: $userForm.serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved',
                        text: response.message || 'User saved successfully.'
                    }).then(function () {
                        $userForm[0].reset();
                        $('#client_id, #manager_name, #admin_id, #hr_id').val(null).trigger('change');
                        syncDesignationUi();
                        updateSaveButtonState();
                        window.location.reload();
                    });
                    return;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Save failed',
                    text: response.message || 'Failed to submit user data.'
                });
            },
            error: function (xhr) {
                let message = 'Something went wrong. Please try again.';

                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    showServerFieldErrors(xhr.responseJSON.errors);
                    message = 'Please correct the highlighted fields.';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message
                });
            },
            complete: function () {
                $saveBtn.prop('disabled', false).text('Save User');
            }
        });
    });
});
