@extends('layouts.app')

@section('title', 'Pending Appraisal')
@section('breadcrumb', 'Pending Appraisal')
@section('page-title', 'Appraisal Section')

@section('content')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    </head>
    <style>
        .top {

            display: flex;
            width: 100%;
            flex-direction: column;

        }

        .dataTables_filter {
            display: none;
        }
    </style>
    <div class="client">
        <h1 class="client__heading">Pending Appraisal</h1>
        <div class="client___item">
            <input type="search" id="employee_search" name="search" class="form-control client__search" placeholder="Search"
                aria-label="Search">
            <button class="client__btn" type="submit">
                <img src="https://modest-gagarin.74-208-156-247.plesk.page/images/search.png" alt="Search">
            </button>
        </div>
    </div>
    <div class="container table-container pending-appraisal-table">
        <div class="table-responsive table-wrapper">
            <table id="pending-apprasial" class="table table-bordered table-hover main-table table-view">
                <thead>
                    <tr>
                        <th>Employee Name</th>
                        <th>Employee Id</th>
                        <th>Designation</th>
                        <th>Email</th>
                        <th>Joining Date</th>
                        <th>Financial Year</th>
                        <th>Probation Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{$user->fname}} {{$user->lname}}</td>
                            <td>{{$user->employee_id}}</td>
                            <td>{{$user->designation}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->dob}}</td>
                            <td>{{$user->financial_year}}</td>
                            <td>{{$user->probation_date ?? 'Not Set'}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize the DataTable
            let table = $('#pending-apprasial').DataTable({
                dom: '<"top"lfr>t<"bottom"ip><"clear">',
                paging: true,
                searching: true, // required for custom search to work
                ordering: true,
                info: true,
                lengthChange: false,
                pageLength: 10,
                language: {
                    emptyTable: "No employee data available"
                }
            });

            // ✅ Custom search input logic
            $('#employee_search').on('keyup', function () {
                table.search(this.value).draw();
            });

            // ✅ AJAX filtering logic
            function fetchAppraisalPendingList(yearRange) {
                if (!yearRange) return;

                $.ajax({
                    url: '/filter-by-financial-year',
                    method: 'POST',
                    data: {
                        financial_year: yearRange,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        table.clear();
                        if (!response.data || response.data.length === 0) {
                            alert("No users found for the selected financial year.");
                            return;
                        }

                        response.data.forEach(function (user) {
                            table.row.add([
                                user[0],
                                user[1],
                                user[2],
                                user[3],
                                user[4],
                                user[5],
                                user[6]
                            ]);
                        });

                        table.draw();
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching data: " + error);
                    }
                });
            }

            $(document).on('change', '#financialYearFilter', function () {
                fetchAppraisalPendingList($(this).val());
            });

            const defaultYear = $('#financialYearFilter').val();
            if (defaultYear) {
                fetchAppraisalPendingList(defaultYear);
            }
        });
    </script>

@endsection