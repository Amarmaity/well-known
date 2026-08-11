@extends('layouts.app')

@section('title', 'Edit Probation Employee')
@section('breadcrumb', 'Edit Probation Employee')
@section('page-title', 'Edit Probation Employee')
@section('body-class', 'special-page')

@section('content')
    @push('styles')
        <link href="{{ asset('css/review-management.css') }}?v={{ filemtime(public_path('css/review-management.css')) }}" rel="stylesheet">
        <link href="{{ asset('css/review-details.css') }}?v={{ filemtime(public_path('css/review-details.css')) }}" rel="stylesheet">
    @endpush

    <div class="review-detail-page">
        <a href="javascript:void(0)" onclick="history.back()" class="review-back-btn">Back</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success mt-3" data-auto-dismiss="success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="content-block">
        <input type="checkbox" id="block1" checked>
        <label for="block1" class="main-label">Edit Probation Employee: {{ $user->fname }} {{ $user->lname }}</label>

        <div class="content">
            <form action="{{ route('update-probation-user', ['id' => $user->id]) }}" method="POST" class="forms-block" id="editProbationUserForm" novalidate>
                @csrf
                @method('PUT')

                <div class="row form-section">
                    <div class="col-md-6">
                        <label for="fname" class="forms-label">First Name</label>
                        <input type="text" name="fname" id="fname" class="form-control"
                            value="{{ old('fname', $user->fname) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="lname" class="forms-label">Last Name</label>
                        <input type="text" name="lname" id="lname" class="form-control"
                            value="{{ old('lname', $user->lname) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="employee_id" class="forms-label">Employee ID</label>
                        <input type="text" name="employee_id" id="employee_id" class="form-control"
                            value="{{ old('employee_id', $user->employee_id) }}" placeholder="e.g. DS00001" required>
                    </div>

                    <div class="col-md-6">
                        <label for="designation" class="forms-label">Designation</label>
                        <input type="text" name="designation" id="designation" class="form-control"
                            value="{{ old('designation', $user->designation) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="dob" class="forms-label">Joining Date</label>
                        <input type="date" name="dob" id="dob" class="form-control"
                            value="{{ old('dob', $user->dob) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="probation_date" class="forms-label">Probation Date</label>
                        <input type="date" name="probation_date" id="probation_date" class="form-control"
                            value="{{ old('probation_date', $user->probation_date) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="salary" class="forms-label">Salary</label>
                        <input type="number" name="salary" id="salary" class="form-control" min="0"
                            value="{{ old('salary', $user->salary) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="salary_grade" class="forms-label">Salary Grade/Band</label>
                        <select name="salary_grade" id="salary_grade" class="form-control">
                            <option value="">Salary Grade</option>
                            @foreach (['A', 'B', 'C', 'D', 'E', 'F'] as $grade)
                                <option value="{{ $grade }}" {{ old('salary_grade', $user->salary_grade) == $grade ? 'selected' : '' }}>
                                    {{ $grade }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="forms-label">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control"
                            value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="mobno" class="forms-label">Mobile Number</label>
                        <input type="tel" name="mobno" id="mobno" class="form-control" maxlength="10"
                            value="{{ old('mobno', $user->mobno) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="employee_status" class="forms-label">Employee Status</label>
                        <input type="text" id="employee_status" class="form-control"
                            value="{{ old('employee_status', $user->employee_status) }}" readonly>
                        <input type="hidden" name="employee_status" id="employee_status_hidden"
                            value="{{ old('employee_status', $user->employee_status) }}">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3" id="saveBtn">Update Probation Employee</button>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/autoDismissAlerts.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/editProbationUser.js') }}?v={{ time() }}"></script>
@endpush
