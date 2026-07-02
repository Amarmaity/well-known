
$(document).ready(function () {

    //----------------------------------------
    // SELECT2
    //----------------------------------------

    if ($('#selected_users').hasClass('select2-hidden-accessible')) {
        $('#selected_users').select2('destroy');
    }

    $('#selected_users').select2({
        placeholder: "Select User",
        width: "100%"
    });


    //----------------------------------------
    // ROLE CHANGE
    //----------------------------------------

    $('#role').change(function () {
        let role = $(this).val();
        // $('#selected_users').empty().trigger('change');
        $('#selected_users').val(null).trigger('change.select2');
        resetPermissionUI();
        if (!role) { return; }
        showNotice('Loading users for selected role...', 'info');
        $.ajax({
            url: window.ACCESS_PERMISSION.getUsersUrl + '/' + encodeURIComponent(role),
            type: 'GET',
            success: function (users) {
                $('#selected_users').empty();
                $.each(users, function (index, user) {
                    $('#selected_users').append(
                        new Option(user.text, user.id, false, false)
                    );
                });
                // Refresh Select2
                $('#selected_users').trigger('change.select2');
                showNotice(users.length ? 'Users loaded successfully.' : 'No active users found for this role.', users.length ? 'success' : 'info');
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                showNotice('Unable to load users. Please try again.', 'error');
            }
        });
    });

    //----------------------------------------
    // USER CHANGE
    //----------------------------------------
    $('#selected_users').change(function () {
        let users = $(this).val();
        resetCheckboxes();
        if (!users) {
            resetPermissionUI();
            return;
        }
        if (!users || users.length === 0) {
            resetPermissionUI();
            return;
        }
        if (users.length > 1) {
            $('#summaryUser').text('Bulk Assignment');
            $('#summaryRole').text($('#role').val());
            $('#permissionSummary').html(`
            <div class="alert alert-warning mb-0">
                Multiple users selected.<br>
                Existing permissions are hidden in bulk mode.
            </div>
        `);
            $('#permissionCount').text('--');
            return;
        }
        loadPermission(users[0]);

    });

    //----------------------------------------
    // LOAD PERMISSION
    //----------------------------------------
    function loadPermission(userId) {
        $.ajax({
            url: window.ACCESS_PERMISSION.getPermissionUrl + '/' + userId,
            type: 'GET',
            success: function (res) {
                resetCheckboxes();
                $('#summaryUser').text(res.user.name);
                $('#summaryRole').text(res.user.role);
                let html = '';
                $.each(res.permissions, function (index, item) {

                    $('#module_' + item.id).prop('checked', true);

                    html += `
                <span class="badge bg-success me-1 mb-2">
                    ${item.name}
                </span>
            `;
                });
                if (html === '') {
                    html = '<div class="empty-summary">No permission assigned</div>';
                }
                $('#permissionSummary').html(html);
                $('#permissionCount').text(res.permissions.length);
                updateParentCheckbox();
                updateSelectAll();
            },
            error: function (xhr) {
                console.error(xhr.responseText);
            }
        });
    }

    //----------------------------------------
    // SELECT ALL
    //----------------------------------------
    $('#selectAll').change(function () {
        let checked = $(this).is(':checked');
        $('.parent-checkbox').prop('checked', checked);
        $('.child-checkbox').prop('checked', checked);
        $('.single-checkbox').prop('checked', checked);
        refreshSummary();
    });



    //----------------------------------------
    // PARENT
    //----------------------------------------
    $('.parent-checkbox').change(function () {
        let parent = $(this).data('parent');
        $('.child-checkbox[data-parent="' + parent + '"]').prop('checked', $(this).is(':checked'));
        refreshSummary();
        updateSelectAll();
    });

    //----------------------------------------
    // CHILD
    //----------------------------------------
    $(document).on('change', '.child-checkbox,.single-checkbox', function () {
        updateParentCheckbox();
        updateSelectAll();
        refreshSummary();
    });

    //----------------------------------------
    // SAVE
    //----------------------------------------
    $('#savePermission').click(function () {
        let modules = [];
        $('.child-checkbox:checked,.single-checkbox:checked').each(function () {

            modules.push($(this).val());
        });
        let users = $('#selected_users').val();
        if (!users || users.length == 0) {
            showNotice('Please select at least one user.', 'error');
            return;
        }
        const saveButton = $('#savePermission');
        saveButton.prop('disabled', true).addClass('disabled');
        showNotice('Saving permissions...', 'info');

        $.ajax({
            url: window.ACCESS_PERMISSION.saveUrl,
            type: 'POST',
            data: {
                _token: window.ACCESS_PERMISSION.csrfToken,
                role: $('#role').val(),
                users: users,
                modules: modules
            },
            success: function (res) {
                showNotice(res.message || 'Permissions saved successfully.', 'success');
                if (users.length == 1) {
                    loadPermission(users[0]);
                }
            },
            error: function () {
                showNotice('Something went wrong while saving permissions.', 'error');
            },
            complete: function () {
                saveButton.prop('disabled', false).removeClass('disabled');
            }
        });
    });

    //----------------------------------------
    // LIVE SUMMARY
    //----------------------------------------
    function refreshSummary() {
        let html = '';
        let count = 0;
        $('.child-checkbox:checked,.single-checkbox:checked').each(function () {
            count++;

            let name = $(this).closest('.form-check').find('label').text().trim();

            html += `
                <span class="badge bg-primary me-1 mb-2">
                    ${name}
                </span>
            `;

        });

        if (html == '') {

            html = '<div class="empty-summary">No permission selected</div>';

        }

        $('#permissionSummary').html(html);

        $('#permissionCount').text(count);

    }
    //----------------------------------------
    // UPDATE PARENT
    //----------------------------------------
    function updateParentCheckbox() {
        $('.parent-checkbox').each(function () {
            let parent = $(this).data('parent');
            let total = $('.child-checkbox[data-parent="' + parent + '"]').length;
            let checked = $('.child-checkbox[data-parent="' + parent + '"]:checked').length;
            if (total > 0) {
                $(this).prop('checked', total == checked);
            }
        });
    }
    //----------------------------------------
    // UPDATE SELECT ALL
    //----------------------------------------
    function updateSelectAll() {
        let total = $('.child-checkbox,.single-checkbox').length;
        let checked = $('.child-checkbox:checked,.single-checkbox:checked').length;
        $('#selectAll').prop('checked', total == checked);

    }

    //----------------------------------------
    // RESET CHECKBOX
    //----------------------------------------

    function resetCheckboxes() {
        $('.child-checkbox').prop('checked', false);
        $('.parent-checkbox').prop('checked', false);
        $('.single-checkbox').prop('checked', false);
        $('#selectAll').prop('checked', false);
    }

    //----------------------------------------
    // RESET RIGHT PANEL
    //----------------------------------------

    function resetPermissionUI() {
        resetCheckboxes();
        $('#summaryUser').text('--');
        $('#summaryRole').text('--');
        $('#permissionCount').text(0);
        $('#permissionSummary').html(
            '<div class="empty-summary">Select one user</div>'
        );
    }

    function showNotice(message, type) {
        const notice = $('#permissionNotice');

        if (!notice.length) {
            return;
        }

        notice
            .removeClass('is-success is-error is-info')
            .addClass('is-visible is-' + type)
            .text(message);
    }
});



