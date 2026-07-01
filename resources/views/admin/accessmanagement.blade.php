@extends('layouts.app')

@section('title', 'Access Management')
@section('breadcrumb', 'Access Management')
@section('page-title', 'Access Management')
@section('body-class', 'special-page')

@section('content')

<div class="container-fluid">

    <div class="back-button">
        <a href="{{ route('setting-view') }}" class="btn btn-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow-sm">

        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h4 class="mb-0">
                        <i class="bi bi-shield-lock"></i>
                        Access Management
                    </h4>

                    <small class="text-muted">
                        Assign sidebar permissions
                    </small>
                </div>

                <button class="btn btn-primary" id="savePermission">
                    <i class="bi bi-check-circle"></i>
                    Save Permission
                </button>

            </div>
        </div>

        <div class="card-body">

            <div class="row mb-4">

                {{-- ROLE --}}

                <div class="col-md-4">

                    <label class="fw-bold">
                        Role
                    </label>

                    <select id="role" class="form-control">

                        <option value="">Select Role</option>

                        @foreach($roles as $role)

                        <option value="{{ $role->user_type }}">
                            {{ ucfirst($role->user_type) }}
                        </option>

                        @endforeach

                    </select>

                </div>

                {{-- USERS --}}

                <div class="col-md-8">

                    <label class="fw-bold">

                        Users

                    </label>

                    <select id="selected_users" class="form-control" multiple>

                    </select>

                </div>

            </div>

            <hr>

            <div class="form-check mb-3">

                <input type="checkbox" id="selectAll" class="form-check-input">

                <label class="form-check-label fw-bold">

                    Select All Permissions

                </label>

            </div>


            <div class="accordion" id="permissionAccordion">

                @foreach($modules as $parent)

                <div class="accordion-item">

                    <h2 class="accordion-header">

                        <button class="accordion-button collapsed" data-bs-toggle="collapse"
                            data-bs-target="#module{{ $parent->id }}">

                            <input type="checkbox" class="parent-checkbox me-3" data-parent="{{ $parent->id }}">

                            <strong>

                                {{ $parent->module_name }}

                            </strong>

                        </button>

                    </h2>

                    <div id="module{{ $parent->id }}" class="accordion-collapse collapse">

                        <div class="accordion-body">

                            @if($parent->children->count())

                            <div class="row">

                                @foreach($parent->children as $child)

                                <div class="col-md-4">

                                    <div class="form-check mb-2">

                                        <input type="checkbox" value="{{ $child->id }}" id="module_{{ $child->id }}"
                                            class="child-checkbox" data-parent="{{ $parent->id }}">

                                        <label for="module_{{ $child->id }}">

                                            {{ $child->module_name }}

                                        </label>

                                    </div>

                                </div>

                                @endforeach

                            </div>

                            @else

                            <div class="form-check">

                                <input type="checkbox" value="{{ $parent->id }}" id="module_{{ $parent->id }}"
                                    class="single-checkbox">

                                <label>

                                    {{ $parent->module_name }}

                                </label>

                            </div>

                            @endif

                        </div>

                    </div>

                </div>

                @endforeach

            </div>

        </div>

    </div>

</div>

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(function () {

    // ------------------------------
    // Select2 safe init
    // ------------------------------
    if ($('#selected_users').hasClass('select2-hidden-accessible')) {
        $('#selected_users').select2('destroy');
    }

    $('#selected_users').select2({
        placeholder: 'Select Users',
        width: '100%'
    });

    // ------------------------------
    // Load users by role
    // ------------------------------
    $('#role').off('change').on('change', function () {

        let role = $(this).val();

        $('#selected_users').empty().trigger('change');

        if (role == '') return;

        $.get('/access-management/get-users/' + encodeURIComponent(role), function (res) {

            $('#selected_users').empty();

            $.each(res, function (index, user) {
                $('#selected_users').append(
                    new Option(user.text, user.id, false, false)
                );
            });

            $('#selected_users').trigger('change');

        }).fail(function (xhr) {
            console.error('User fetch failed:', xhr.responseText);
        });

    });

    // ------------------------------
    $('#selectAll').off('change').on('change', function () {
        $('input[type=checkbox]').prop('checked', $(this).is(':checked'));
    });

    // ------------------------------
    $('.parent-checkbox').off('change').on('change', function () {
        let parent = $(this).data('parent');
        $('.child-checkbox[data-parent="' + parent + '"]').prop('checked', $(this).is(':checked'));
    });

    // ------------------------------
    $(document).off('change', '.child-checkbox').on('change', '.child-checkbox', function () {
        let parent = $(this).data('parent');
        let total = $('.child-checkbox[data-parent="' + parent + '"]').length;
        let checked = $('.child-checkbox[data-parent="' + parent + '"]:checked').length;
        $('.parent-checkbox[data-parent="' + parent + '"]').prop('checked', total == checked);
    });

    // ------------------------------
    // SAVE
    // ------------------------------
    $('#savePermission').off('click').on('click', function () {

        let modules = [];
        $('.child-checkbox:checked, .single-checkbox:checked').each(function () {
            modules.push($(this).val());
        });

        if (!$('#selected_users').val() || $('#selected_users').val().length === 0) {
            alert('Please select at least one user.');
            return;
        }
        if (modules.length === 0) {
            alert('Please select at least one module.');
            return;
        }

        $.ajax({
            url: "{{ route('access.permission.save') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                role: $('#role').val(),
                users: $('#selected_users').val(),
                modules: modules
            },
            success: function (res) {
                alert(res.message);
            },
            error: function (xhr) {
                alert('Something went wrong.');
                console.error(xhr.responseText);
            }
        });

    });

});
</script>

@endsection