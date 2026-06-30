@extends('layouts.app')
@section('title', 'Designation Management')

@section('breadcrumb', 'Designation Management')

@section('page-title', 'Designation Management')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4>Designation Management</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('designation-store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text" name="designation_name" class="form-control" placeholder="Designation Name">
                        </div>
                        <div class="col-md-4">

                            <button class="btn btn-primary w-100">
                                Add Designation
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
                        @foreach ($designations as $designation)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $designation->designation_name }}</td>
                                <td>
                                    @if ($designation->status)
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
                                    <button type="button" class="btn btn-warning btn-sm editDesignation" data-id="{{ $designation->id }}">
                                        Edit
                                    </button>
                                    <button type="button" class="btn btn-info btn-sm changeStatus" data-id="{{ $designation->id }}">
                                        {{ $designation->status ? 'Deactivate' : 'Activate' }}
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm deleteDesignation"
                                        data-id="{{ $designation->id }}">
                                        Delete
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

                                    <label>Designation</label>

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
                let url = "{{ route('designation-edit', ':id') }}";
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
                let url = "{{ route('designation-update', ':id') }}";
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
                let url = "{{ route('designation-status', ':id') }}";
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


            //================ DELETE ====================

            $(document).on('click', '.deleteDesignation', function() {

                let id = $(this).data('id');

                if (confirm('Are you sure?')) {

                    let url = "{{ route('designation-destroy', ':id') }}";
                    url = url.replace(':id', id);

                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            alert(res.message);
                            location.reload();
                        },
                        error: function() {
                            alert('Delete Failed');
                        }
                    });

                }

            });

        });
    </script>
@endsection
