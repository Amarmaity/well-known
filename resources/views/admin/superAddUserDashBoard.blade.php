@extends('layouts.app')
<!-- Extends app.blade.php (Header, Sidebar, Footer included) -->

@section('title', 'Add User')
@section('breadcrumb', 'Add User')
@section('body-class', 'special-page')
@section('content')

    <style>
        /* Remove the dropdown arrow inside the Select2 box (single select) */
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
            display: none !important;
        }

        /* Optional: Clean up spacing on the right to account for the missing arrow */
        .select2-container--bootstrap-5 .select2-selection--single {
            padding-right: 0.75rem !important;
            background-image: none !important;
        }
    </style>

    <body>

        <div class="super-add-user-page">
            <div class="content-block">
                <input type="checkbox" id="block1">
                <label for="block1" class="main-label">Add New User</label>
                <div class="content">
                    <form action="{{ route('save-user') }}" method="POST" enctype="multipart/form-data" class="forms-block"
                        id="userForm">
                        @csrf
                        <div class="form-section">
                            <h5 class="heading-three">Personal Information</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="fname" class="forms-label">First Name</label>
                                    <input type="text" class="form-control" id="fname" name="fname"
                                        placeholder="Enter first name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="lname" class="forms-label">Last Name</label>
                                    <input type="text" class="form-control" id="lname" name="lname"
                                        placeholder="Enter last name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="dob" class="forms-label">Joining Date</label>
                                    <input type="date" class="form-control" id="dob" name="dob" max="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="gender" class="forms-label">Gender</label>
                                    <select class="form-control" id="gender" name="gender" required>
                                        <option value="" selected disabled>Select gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="form-section">
                            <h5 class="heading-three">Contact Information</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="mobno" class="forms-label">Mobile Number</label>
                                    <input type="tel" class="form-control" id="mobno" name="mobno"
                                        placeholder="Enter mobile number" pattern="\d{10}" maxlength="10" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="forms-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Enter email" required>
                                </div>
                            </div>
                        </div>

                        <!-- Work Information Section -->

                        <div class="form-section">
                            <h5 class="heading-three">Work Information</h5>


                            <div class="row g-3">


                                <div class="col-md-6">
                                    <label for="designation" class="forms-label">Designation</label>
                                    <select class="form-control" id="designation_dropdown" name="designation" required>
                                        <option value="" selected disabled>Select Designation</option>
                                        <option value=" Hr">Hr</option>
                                        <option value="SEO">SEO</option>
                                        <option value="Admin">Admin</option>
                                        <option value="UI/UX Designer">UI/UX Designer</option>
                                        <option value="Quality Analyst">Quality Analyst</option>
                                        <option value="Software Developer">Software Developer</option>
                                        <option value="Manager">Manager</option>
                                        <option value="Business Development">Business Development(Sales)</option>
                                    </select>



                                    {{-- Division --}}
                                    <label for="division" class="forms-label">Division</label>
                                    <select class="form-control" id="division_dropdown" name="division" required>
                                        <option value="" selected disabled>Select Division</option>
                                        <option value="Kasba Office">Kasba Office</option>
                                        <option value="Salt Lake 3A">Salt Lake 3A</option>
                                        <option value="Salt Lake 3B">Salt Lake 3B</option>
                                        <option value="Salt Lake 17B">Salt Lake 17B</option>
                                        <option value="Salt Lake 504">Salt Lake 504</option>

                                    </select>
                                    {{-- <div class="client-hide" id="search_manager_div" style="display: block;">
                                            <label for="manager_name" class="forms-label">Search Manager Name</label>
                                            <select class="form-control" id="manager_id" name="manager_id" style="width: 100%">
                                                <!-- Loaded via AJAX -->
                                            </select>

                                        </div> --}}
                                    <div class="client-hide" id="search_manager_div" style="display: block;">
                                        <label for="manager_name" class="forms-label">Search Manager Name</label>
                                        <select class="form-control" id="manager_name" name="manager_id"
                                            style="width: 100%" data-route="{{ route('get.manager') }}">
                                            <!-- Select2 will load options via AJAX -->
                                        </select>
                                    </div>




                                    <div class="client-hide" id="manager_name_div" style="display: none;">
                                        <label for="manager_name_input" class="forms-label">Manager Name</label>
                                        <input type="text" class="form-control" id="manager_name_input"
                                            name="manager_name" placeholder="Enter Manager name">
                                    </div>

                                    <div class="client-hide mt-3" id="search_hr_div">
                                        <label class="forms-label">Search HR</label>
                                        <select class="form-control" id="hr_id" name="hr_id" style="width:100%"
                                            data-route="{{ route('get.hrs') }}">
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="client-hide">
                                        <label for="employee_id" class="forms-label">Employee ID</label>
                                        <input type="text" class="form-control" id="employee_id" name="employee_id"
                                            placeholder="e.g..DS00001">
                                    </div>
                                    {{-- Evaluation Purpose --}}
                                    <div class="client-hide"
                                        style="display: none;>
                                            <label for="
                                        Evaluation Purpose" class="forms-label">Evaluation Purpose</label>
                                        <select class="form-control" id="evaluation_purpose" name="evaluation_purpose">
                                            <option value="" selected>Select Purpose</option>
                                            <option value="Appraisal" selected>Appraisal</option>
                                        </select>
                                    </div>
                                    {{-- <option value="Salt Lake 3B">Salt Lake 3B</option> --}}

                                    <div class="client-hide" id="client_select_div" style="display: none;">
                                        <label for="client_id" class="forms-label">Select Client</label>
                                        <select class="form-control" id="client_id" name="client_id[]"
                                            multiple="multiple" style="width: 100%"
                                            data-route="{{ route('get.clients') }}">
                                            <!-- Options loaded via AJAX -->
                                        </select>
                                    </div>

                                    <div class="client-hide" id="search_admin_div">
                                        <label class="forms-label">Search Admin</label>
                                        <select class="form-control" id="admin_id" name="admin_id" style="width:100%"
                                            data-route="{{ route('get.admins') }}">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- User Type Selection (Dropdown & Checkboxes) -->
                        <div class="form-section">
                            <h5 class="heading-three">Additional Information</h5>
                            <div class="row d-flex flex-wrap align-items-start">
                                <div class="col-md-6" style="display: none;">
                                    <label for="user_type_dropdown" class="forms-label">User Type</label>
                                    <select class="form-control" id="user_type_dropdown" required disabled>
                                        <option value="" selected disabled>Select User Type</option>
                                        <option value="admin">Admin</option>
                                        <option value="hr">HR</option>
                                        <option value="users">Users</option>
                                        <option value="manager">Manager</option>
                                    </select>



                                    <!-- This hidden input is the one that will actually be submitted -->
                                    <input type="hidden" name="user_type" id="user_type_hidden" required>
                                </div>
                                <div class="col-md-6" id="review-section">
                                    <label class="forms-label d-block">Selected Person Can Review:</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input check-input" type="checkbox" id="admin"
                                            name="user_roles[]" value="admin">
                                        <label class="form-check-label" for="admin">Admin</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input check-input" type="checkbox" id="hr"
                                            name="user_roles[]" value="hr">
                                        <label class="form-check-label" for="hr">HR</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input check-input" type="checkbox" id="users"
                                            name="user_roles[]" value="users">
                                        <label class="form-check-label" for="users">Users</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input check-input" type="checkbox" id="manager"
                                            name="user_roles[]" value="manager">
                                        <label class="form-check-label" for="manager">Manager</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        {{-- <input class="form-check-input check-input" type="checkbox" id="client"
                                                name="user_roles[]" value="client"> --}}
                                        <input class="form-check-input check-input" type="checkbox" id="client-checkbox"
                                            name="user_roles[]" value="client">
                                        <label class="form-check-label" for="client-checkbox">Client</label>
                                    </div>
                                </div>

                            </div>

                            <!-- Checkboxes for Multiple Roles -->

                        </div>
                        <div class="client-hide">
                            <label for="probation_date" class="heading-four">Probation Date</label>
                            <input type="date" id="probation_date" name="probation_date" class="form-control">
                        </div>
                        <!-- Account Information Section -->
                        <div class="form-section">
                            <h5 class="heading-three">Account Information</h5>
                            <div class="row g-3">
                                <div class="client-hide col-md-6">
                                    <label for="salary" class="forms-label">Salary</label>
                                    <input type="text" class="form-control" id="salary" name="salary"
                                        max="7" placeholder="Enter Salary" min="0" required>
                                </div>

                                <div class="col-md-6">
                                    <div class="client-hide">
                                        <label for="salary grade" class="forms-label">Salary Grade/Band</label>
                                        <select class="form-control" id="salary_grade" name="salary_grade" required>
                                            <option value="" selected disabled>Salary Grade</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                            <option value="D">D</option>
                                            <option value="E">E</option>
                                            <option value="F">F</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <label for="password" class="forms-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Enter password" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="cnf-password" class="forms-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="cnf-password" name="password"
                                        placeholder="Enter password" required>
                                </div>
                            </div>
                        </div>

                        <!-- Confirmation Checkbox & Save Button -->
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary primary-btn" id="saveBtn">Save User</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>

    </body>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.routes = {
            getClients: "{{ route('get.clients') }}",
            getManager: "{{ route('get.manager') }}",
            getAdmins: "{{ route('get.admins') }}",
            getHrs: "{{ route('get.hrs') }}"
        };
    </script>
    <script src="{{ asset('js/superAddUserDashBoard.js') }}"></script>
@endsection
