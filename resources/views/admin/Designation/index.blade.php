@extends('layouts.app')
@section('title', 'Designation Management')

@section('breadcrumb', 'Designation Management')

@section('page-title', 'Designation Management')
@section('body-class', 'special-page')

@section('content')
    <div class="container">
        <div class="back-button">
            <a href="{{ route('setting-view') }}" class="btn btn-secondary mb-3">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>
        <div class="card">
            <div class="card-header">
                <h4>Role Management</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('role-store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text" name="role_name" class="form-control" placeholder="Role Name">
                        </div>

                        <div class="col-md-4">
                            <button class="btn btn-primary w-100">
                                Add Role
                            </button>
                        </div>
                    </div>
                </form>
                <hr>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Designation</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $role->role_name }}</td>
                                <td>
                                    @if ($role->status)
                                        <span class="badge bg-success">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm editDesignation"
                                        data-id="{{ $role->id }}">
                                        Edit
                                    </button>
                                    <button type="button" class="btn btn-info btn-sm changeStatus"
                                        data-id="{{ $role->id }}">
                                        {{ $role->status ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Edit Modal -->
            <div class="modal fade" id="editDesignationModal" tabindex="-1">
                <div class="modal-dialog">
                    <form id="updateDesignationForm">

                        @csrf

                        <div class="modal-content">

                            <div class="modal-header">
                                <h5>Edit Designation</h5>

                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                </button>

                            </div>

                            <div class="modal-body">

                                <input type="hidden" id="designation_id">

                                <div class="form-group">

                                    <label>Role</label>

                                    <input type="text" id="designation_name" class="form-control">

                                </div>

                            </div>

                            <div class="modal-footer">

                                <button class="btn btn-success" type="submit">

                                    Update

                                </button>

                            </div>

                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            const editModal = new bootstrap.Modal(document.getElementById('editDesignationModal'));

            //================== EDIT ===================

            $(document).on('click', '.editDesignation', function() {

                let id = $(this).data('id');
                let url = "{{ route('role-edit', ':id') }}";
                url = url.replace(':id', id);
                $.get(url, function(res) {

                    $('#designation_id').val(res.id);

                    $('#designation_name').val(res.designation_name);

                    editModal.show();

                });

            });

            //================ UPDATE ===================
            $('#updateDesignationForm').on('submit', function(e) {

                e.preventDefault();

                let id = $('#designation_id').val();
                let url = "{{ route('role-update', ':id') }}";
                url = url.replace(':id', id);
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        designation_name: $('#designation_name').val()
                    },
                    success: function(res) {
                        alert(res.message);
                        location.reload();
                    },
                    error: function() {

                        alert('Update Failed');

                    }
                });
            });
            //=============== STATUS ====================

            $(document).on('click', '.changeStatus', function() {

                let id = $(this).data('id');
                let url = "{{ route('role-status', ':id') }}";
                url = url.replace(':id', id);

                $.ajax({

                    url: url,

                    type: 'POST',

                    data: {
                        _token: '{{ csrf_token() }}'
                    },

                    success: function(res) {

                        location.reload();

                    }

                });

            });

        });
    </script>
@endsection
