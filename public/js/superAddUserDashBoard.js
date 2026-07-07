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

    function syncClientSelect() {
        const shouldShow = $clientCheckbox.is(':checked') && $clientCheckbox.closest('.form-check').is(':visible');

        $clientSelectDiv.toggle(shouldShow);

        if (!shouldShow) {
            $('#client_id').val(null).trigger('change');
        }
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
            return;
        }

        if (selected === 'manager') {
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
            $('#users').closest('.form-check').hide();
        } else if (selected === 'admin') {
            ['admin', 'hr', 'users', 'client'].forEach(function (id) {
                $('#' + id).closest('.form-check').hide();
            });
        } else if (selected === 'hr') {
            ['hr', 'admin', 'users', 'client'].forEach(function (id) {
                $('#' + id).closest('.form-check').hide();
            });
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

    $designationDropdown.on('change', syncDesignationUi);
    $clientCheckbox.on('change', syncClientSelect);
    syncDesignationUi();

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

    $('#salary').on('input', function () {
        const monthlySalary = parseFloat($(this).val()) || 0;
        const annualCTC = monthlySalary / 12;
        let grade = '';

        if (annualCTC < 200000) grade = 'F';
        else if (annualCTC <= 349999) grade = 'E';
        else if (annualCTC <= 499999) grade = 'D';
        else if (annualCTC <= 649999) grade = 'C';
        else if (annualCTC <= 900000) grade = 'B';
        else grade = 'A';

        $('#salary_grade').val(grade);
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

        const date = new Date(joiningDate);
        date.setMonth(date.getMonth() + 6);

        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        $('#probation_date').val(`${year}-${month}-${day}`);
    });

    $userForm.on('submit', function (e) {
        e.preventDefault();

        const password = $('#password').val();
        const confirmPassword = $('#cnf-password').val();

        if (password !== confirmPassword) {
            showToast('error', 'Validation error', 'Passwords do not match.');
            return;
        }

        const joiningDateVal = $('#dob').val();
        const probationDateVal = $('input[name="probation_date"]').val();

        if (joiningDateVal && probationDateVal) {
            const joiningDate = new Date(joiningDateVal);
            const probationDate = new Date(probationDateVal);

            if (probationDate < joiningDate) {
                showToast('error', 'Validation error', 'Probation date cannot be earlier than the joining date.');
                return;
            }
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
                    message = Object.values(xhr.responseJSON.errors).flat().join('\n');
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
