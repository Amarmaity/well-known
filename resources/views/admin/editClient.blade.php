@extends('layouts.app')

@section('title', 'Edit Client')
@section('breadcrumb', 'Edit Client')
@section('page-title', 'Edit Client')
@section('body-class', 'special-page')

@section('content')
    <div class="mb-3">
        <a href="{{ route('client-list') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="super-add-user-page">
        <div class="content-block">
            <input type="checkbox" id="block1">
            <label for="block1" class="main-label">Edit Client: {{ $client->client_name }}</label>
            <div class="content">
                <form method="POST" enctype="multipart/form-data" class="forms-block" id="clientEditForm">
                    @csrf
                    @method('PUT')

                    <div class="form-section">
                        <h5 class="heading-three">Client Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="client-name" class="forms-label">Client Name</label>
                                <input type="text" class="form-control" id="client-name" name="client_name"
                                    value="{{ old('client_name', $client->client_name) }}" placeholder="Enter client name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="company-name" class="forms-label">Company Name</label>
                                <input type="text" class="form-control" id="company-name" name="company_name"
                                    value="{{ old('company_name', $client->company_name) }}" placeholder="Enter company name" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h5 class="heading-three">Contact Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="mobno" class="forms-label">Mobile Number</label>
                                <input type="tel" class="form-control" id="mobno" name="client_mobno" maxlength="15"
                                    value="{{ old('client_mobno', $client->client_mobno) }}" placeholder="Enter mobile number">
                            </div>
                            <div class="col-md-6">
                                <label for="client-email" class="forms-label">Email</label>
                                <input type="email" class="form-control" id="client-email" name="client_email"
                                    value="{{ old('client_email', $client->client_email) }}" placeholder="Enter email" required>
                            </div>
                        </div>
                    </div>

                    <div class="row d-flex flex-wrap align-items-start">
                        <div class="col-md-6" style="display: none;">
                            <label for="user_type_dropdown" class="forms-label">User Type</label>
                            <select class="form-control" id="user_type_dropdown" name="user_type" required>
                                <option value="client" selected>Client</option>
                            </select>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="password" class="forms-label">New Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Leave blank to keep existing password">
                            </div>

                            <div class="col-md-6">
                                <label for="cnf-password" class="forms-label">Confirm Password</label>
                                <input type="password" class="form-control" id="cnf-password" placeholder="Confirm new password">
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary primary-btn" id="saveBtn">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#clientEditForm').on('submit', function(e) {
                e.preventDefault();

                const clientName = $('#client-name').val().trim();
                const companyName = $('#company-name').val().trim();
                const mobile = $('#mobno').val().trim();
                const email = $('#client-email').val().trim();
                const password = $('#password').val();
                const confirmPassword = $('#cnf-password').val();

                if (clientName === '') {
                    alert('Client Name is required.');
                    $('#client-name').focus();
                    return;
                }

                if (companyName === '') {
                    alert('Company Name is required.');
                    $('#company-name').focus();
                    return;
                }

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (email === '') {
                    alert('Email is required.');
                    $('#client-email').focus();
                    return;
                }

                if (!emailRegex.test(email)) {
                    alert('Please enter a valid email address.');
                    $('#client-email').focus();
                    return;
                }

                if (mobile !== '') {
                    const mobileRegex = /^[6-9]\d{9}$/;

                    if (!mobileRegex.test(mobile)) {
                        alert('Please enter a valid 10-digit Indian mobile number.');
                        $('#mobno').focus();
                        return;
                    }
                }

                if (password !== '') {
                    if (password.length < 6) {
                        alert('Password must be at least 6 characters.');
                        $('#password').focus();
                        return;
                    }

                    if (password !== confirmPassword) {
                        alert('Passwords do not match.');
                        $('#cnf-password').focus();
                        return;
                    }
                }

                $('#saveBtn').prop('disabled', true).text('Updating...');

                $.ajax({
                    url: "{{ route('update-client', $client->id) }}",
                    type: "POST",
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === "success") {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message || "Client updated successfully.",
                                timer: 1800,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = "{{ route('client-list') }}";
                            });
                        } else {
                            alert(response.message || "Update failed. Try again.");
                            $('#saveBtn').prop('disabled', false).text('Update');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            let errorMessages = '';

                            $.each(xhr.responseJSON.errors, function(field, messages) {
                                errorMessages += messages.join("\n") + "\n";
                            });

                            alert(errorMessages);
                        } else {
                            alert("Something went wrong. Please try again.");
                        }

                        $('#saveBtn').prop('disabled', false).text('Update');
                    }
                });
            });
        });
    </script>
@endsection