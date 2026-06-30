@extends('layouts.app')

@section('title', 'Access Management')
@section('breadcrumb', 'Access Management')
@section('page-title', 'Access Management')

@section('content')

    <div class="container-fluid">

        <div class="card shadow-sm border-0">

            <div class="card-header bg-white">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h4 class="mb-0">
                            <i class="bi bi-shield-lock"></i>
                            Access Management
                        </h4>

                        <small class="text-muted">
                            Manage sidebar permissions by designation
                        </small>

                    </div>

                    <button id="savePermission" class="btn btn-primary">

                        <i class="bi bi-check-circle"></i>

                        Save Permission

                    </button>

                </div>

            </div>

            <div class="card-body">

                <div class="row mb-4">

                    <div class="col-md-5">

                        <label class="form-label fw-bold">

                            Designation

                            <span class="text-danger">*</span>

                        </label>

                        <select class="form-select" id="designation">

                            <option value="">

                                Select Designation

                            </option>

                            @foreach ($designations as $designation)
                                <option value="{{ $designation->id }}">

                                    {{ ucwords($designation->designation_name) }}

                                </option>
                            @endforeach

                        </select>

                    </div>

                </div>

                <hr>

                <div class="mb-3">

                    <div class="form-check">

                        <input class="form-check-input" type="checkbox" id="selectAll">

                        <label class="form-check-label fw-bold" for="selectAll">

                            Select All Permissions

                        </label>

                    </div>

                </div>

                <div class="accordion" id="permissionAccordion">

                    @foreach ($modules as $parent)
                        <div class="accordion-item mb-2">

                            <h2 class="accordion-header" id="heading{{ $parent->id }}">

                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $parent->id }}">

                                    <div class="d-flex align-items-center w-100">

                                        <input type="checkbox" class="form-check-input me-3 parent-checkbox"
                                            data-parent="{{ $parent->id }}">

                                        <strong>

                                            {{ $parent->module_name }}

                                        </strong>

                                    </div>

                                </button>

                            </h2>

                            <div id="collapse{{ $parent->id }}" class="accordion-collapse collapse">

                                <div class="accordion-body">

                                    @if ($parent->children->count())
                                        <div class="row">

                                            @foreach ($parent->children as $child)
                                                <div class="col-md-4 mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input child-checkbox" type="checkbox"
                                                            value="{{ $child->id }}" data-parent="{{ $parent->id }}"
                                                            id="module{{ $child->id }}">

                                                        <label class="form-check-label" for="module{{ $child->id }}">

                                                            {{ $child->module_name }}

                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                    @else
                                        <div class="form-check">

                                            <input class="form-check-input single-checkbox" type="checkbox"
                                                value="{{ $parent->id }}" id="module{{ $parent->id }}">

                                            <label class="form-check-label">

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

    <script>
        $(function() {

            /*
            =====================================
            SELECT ALL
            =====================================
            */

            $('#selectAll').change(function() {

                $('input[type=checkbox]').prop(

                    'checked',

                    $(this).is(':checked')

                );

            });


            /*
            =====================================
            PARENT
            =====================================
            */

            $('.parent-checkbox').change(function() {

                let parent = $(this).data('parent');

                $('.child-checkbox[data-parent="' + parent + '"]')

                    .prop('checked', $(this).is(':checked'));

            });



            /*
            =====================================
            CHILD
            =====================================
            */

            $('.child-checkbox').change(function() {

                let parent = $(this).data('parent');

                let total = $('.child-checkbox[data-parent="' + parent + '"]').length;

                let checked = $('.child-checkbox[data-parent="' + parent + '"]:checked').length;

                $('.parent-checkbox[data-parent="' + parent + '"]')

                    .prop('checked', total == checked);

            });

        });
    </script>

@endsection
