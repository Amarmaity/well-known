@extends('layouts.app')

@section('title', 'Add Client')
@section('breadcrumb', 'Add Client')
@section('page-title', 'Add Client')
@section('body-class', 'special-page')

@section('content')

<style>
    select:disabled {
  appearance: none;           
  -webkit-appearance: none;   
  -moz-appearance: none;      
  background-image: none;     
}

#userForm .field-error {
  display: block;
  width: 100%;
  margin-top: 6px;
  margin-bottom: 12px;
  color: #dc3545;
  font-size: 13px;
  font-weight: 500;
  line-height: 1.4;
}

#userForm .is-invalid {
  border-color: #dc3545 !important;
}
</style>

    <div class="super-add-user-page">
        <div class="content-block">
            <input type="checkbox" id="block1">
            <label for="block1" class="main-label">Add New Client</label>
            <div class="content">
                <form method="POST" enctype="multipart/form-data" class="forms-block" id="userForm">
                    @csrf
                    <div class="form-section">
                        <h5 class="heading-three">Client Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="client-name" class="forms-label">Client Name</label>
                                <input type="text" class="form-control" id="client-name" name="client_name"
                                    placeholder="Enter client name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="company-name" class="forms-label">Company Name</label>
                                <input type="text" class="form-control" id="company-name" name="company_name"
                                    placeholder="Enter company name" required>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="form-section">
                        <h5 class="heading-three">Contact Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="mobno" class="forms-label">Mobile Number</label>
                                <input type="tel" class="form-control" id="mobno" name="client_mobno" maxlength="15"
                                    placeholder="Enter mobile number">
                            </div>
                            <div class="col-md-6">
                                <label for="client-email" class="forms-label">Email</label>
                                <input type="email" class="form-control" id="client-email" name="client_email"
                                    placeholder="Enter email" required>
                            </div>
                        </div>
                    </div>

                    <div class="row d-flex flex-wrap align-items-start">
                        <div class="col-md-6" style="display: none;">
                            <label for="user_type_dropdown" class="forms-label">User Type</label>
                            <select class="form-control" id="user_type_dropdown" name="user_type" required>
                                <option value="" selected >Select User Type</option>
                                <option value="client" selected>Client</option>
                            </select>
                        </div>

                        <!-- Password Section -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="password" class="forms-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Enter password" required>
                            </div>

                            <div class="col-md-6">
                                <label for="cnf-password" class="forms-label">Confirm Password</label>
                                <input type="password" class="form-control" id="cnf-password" placeholder="Confirm password"
                                    required>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary primary-btn" id="saveBtn" disabled>Save</button>
                        </div>
                </form>
            </div>
        </div>
    </div>

    <!-- CSRF Meta Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- AJAX Script -->
    <script>
        $(document).ready(function () {
            const $saveBtn = $('#saveBtn');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const mobileRegex = /^\+?[1-9]\d{6,14}$/;

            function clearFieldError($field) {
                $field.removeClass('is-invalid');
                const $next = $field.next('.field-error');

                if ($next.length) {
                    $next.remove();
                }
            }

            function setFieldError($field, message) {
                clearFieldError($field);
                $field.addClass('is-invalid');
                $('<div class="field-error"></div>').text(message).insertAfter($field);
            }

            function validateClientField($field, showError = true) {
                const id = $field.attr('id');
                const value = $.trim($field.val() || '');

                if (id === 'client-name') {
                    if (!value) {
                        if (showError) setFieldError($field, 'Client Name is required.');
                        return false;
                    }

                    if (!/^[A-Za-z ]+$/.test(value)) {
                        if (showError) setFieldError($field, 'Client Name should contain letters only.');
                        return false;
                    }
                }

                if (id === 'company-name') {
                    if (!value) {
                        if (showError) setFieldError($field, 'Company Name is required.');
                        return false;
                    }

                    if (!/^[A-Za-z ]+$/.test(value)) {
                        if (showError) setFieldError($field, 'Company Name should contain letters only.');
                        return false;
                    }
                }

                if (id === 'client-email') {
                    if (!value) {
                        if (showError) setFieldError($field, 'Email is required.');
                        return false;
                    }

                    if (!emailRegex.test(value)) {
                        if (showError) setFieldError($field, 'Please enter a valid email address.');
                        return false;
                    }
                }

                if (id === 'mobno' && value !== '' && !mobileRegex.test(value)) {
                    if (showError) setFieldError($field, 'Please enter a valid mobile number.');
                    return false;
                }

                if (id === 'password') {
                    if (!value) {
                        if (showError) setFieldError($field, 'Password is required.');
                        return false;
                    }

                    if (value.length < 6) {
                        if (showError) setFieldError($field, 'Password must be at least 6 characters.');
                        return false;
                    }
                }

                if (id === 'cnf-password') {
                    if (!value) {
                        if (showError) setFieldError($field, 'Confirm Password is required.');
                        return false;
                    }

                    if (value !== $('#password').val()) {
                        if (showError) setFieldError($field, 'Passwords do not match.');
                        return false;
                    }
                }

                if (showError) {
                    clearFieldError($field);
                }
                return true;
            }

            function canSubmitClientForm() {
                const fields = ['#client-name', '#company-name', '#mobno', '#client-email', '#password', '#cnf-password'];
                return fields.every(function(selector) {
                    return validateClientField($(selector), false);
                });
            }

            function updateSaveButtonState() {
                $saveBtn.prop('disabled', !canSubmitClientForm());
            }

            $('#client-name, #company-name, #mobno, #client-email, #password, #cnf-password').on('input change', function() {
                validateClientField($(this), true);

                if (this.id === 'password' || this.id === 'cnf-password') {
                    validateClientField($('#cnf-password'), true);
                }

                updateSaveButtonState();
            });
            updateSaveButtonState();

            $('#userForm').on('submit', function (e) {
                e.preventDefault();
                
                const clientName = $('#client-name').val().trim();
                const companyName = $('#company-name').val().trim();
                const mobile = $('#mobno').val().trim();
                const email = $('#client-email').val().trim();
                const password = $('#password').val();
                const confirmPassword = $('#cnf-password').val();

                // Client Name
                if (clientName === '') {
                    Swal.fire({ icon: 'error', title: 'Validation error', text: 'Client Name is required.' });
                    $('#client-name').focus();
                    return;
                }

                if (!/^[A-Za-z ]+$/.test(clientName)) {
                    Swal.fire({ icon: 'error', title: 'Validation error', text: 'Client Name should contain letters only.' });
                    $('#client-name').focus();
                    return;
                }

                // Company Name
                if (companyName === '') {
                    Swal.fire({ icon: 'error', title: 'Validation error', text: 'Company Name is required.' });
                    $('#company-name').focus();
                    return;
                }

                if (!/^[A-Za-z ]+$/.test(companyName)) {
                    Swal.fire({ icon: 'error', title: 'Validation error', text: 'Company Name should contain letters only.' });
                    $('#company-name').focus();
                    return;
                }

                // Email
                if (email === '') {
                    Swal.fire({ icon: 'error', title: 'Validation error', text: 'Email is required.' });
                    $('#client-email').focus();
                    return;
                }

                if (!emailRegex.test(email)) {
                    Swal.fire({ icon: 'error', title: 'Validation error', text: 'Please enter a valid email address.' });
                    $('#client-email').focus();
                    return;
                }

                // Mobile (Optional)
                if (mobile !== '') {
                    if (!mobileRegex.test(mobile)) {
                        Swal.fire({ icon: 'error', title: 'Validation error', text: 'Please enter a valid mobile number.' });
                        $('#mobno').focus();
                        return;
                    }
                }

                // Password
                if (password.length < 6) {
                    Swal.fire({ icon: 'error', title: 'Validation error', text: 'Password must be at least 6 characters.' });
                    $('#password').focus();
                    return;
                }

                // Confirm Password
                if (password !== confirmPassword) {
                    Swal.fire({ icon: 'error', title: 'Validation error', text: 'Passwords do not match.' });
                    $('#cnf-password').focus();
                    return;
                }
                
                
                $saveBtn.prop('disabled', true).text('Saving...');

                $.ajax({
                    url: "{{ route('new-client') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.status === "success" || response.status === "warning") {
                            Swal.fire({
                                icon: response.status === "warning" ? 'warning' : 'success',
                                title: response.status === "warning" ? 'Saved with warning' : 'Saved',
                                text: response.message || 'Client added successfully.'
                            }).then(function () {
                                $('#userForm')[0].reset();
                                $saveBtn.text('Save');
                                updateSaveButtonState();
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Save failed',
                                text: response.message || 'Submission failed. Try again.'
                            });
                            $saveBtn.text('Save');
                            updateSaveButtonState();
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = '';

                            $.each(errors, function (field, messages) {
                                errorMessages += messages.join("\n") + "\n";
                            });

                            Swal.fire({
                                icon: 'error',
                                title: 'Validation error',
                                text: errorMessages
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong. Please try again.'
                            });
                        }

                        $saveBtn.text('Save');
                        updateSaveButtonState();
                    }
                });
            });
        });
    </script>

@endsection
