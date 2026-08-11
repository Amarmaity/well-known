@extends('layouts.app')

@section('title', 'Employee Details')

@section('breadcrumb', "Employee {$employee_id} / View Admin Review")

@section('body-class', 'special-page')

@section('content')
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <link
            href="{{ asset('css/review-detail-readonly.css') }}?v={{ filemtime(public_path('css/review-detail-readonly.css')) }}"
            rel="stylesheet">
    @endpush

    <div class="review-read-page">
        <div class="review-read-header">
            <a href="{{ url()->previous() }}" class="review-read-back">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
            <div class="review-read-title">
                <i class="bi bi-person-check"></i>
                <div>
                    <h1>Admin Review Details</h1>
                    <p>Employee ID: {{ $employee_id }}@if (!empty($financial_year)) 
                        Financial Year: {{ $financial_year }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="review-read-card span-tage">
            <div class="review-read-section-title">
                <i class="bi bi-card-checklist"></i>
                Review Scores and Comments
            </div>
            <div class="table-wrapper">
                <table id="reviewHistoryTable" class="table table-bordered table-hover main-table">
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>Rating</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reviews as $review)
                            <tr>
                                <td>1. Has the employee demonstrated regular attendance and punctuality?</td>
                                <td><span class="review-rating">{{ $review->demonstrated_attendance }}/5</span></td>
                                <td>{{ $review->comments_demonstrated_attendance }}</td>
                            </tr>
                            <tr>
                                <td>2. How well does the employee manage time within the shift?</td>
                                <td><span class="review-rating">{{ $review->employee_manage_shift }}/5</span></td>
                                <td>{{ $review->comments_employee_manage_shift }}</td>
                            </tr>
                            <tr>
                                <td>3. How would you rate the employee’s accuracy and neatness in reports and documentation?
                                </td>
                                <td><span class="review-rating">{{ $review->documentation_neatness }}/5</span></td>
                                <td>{{ $review->comments_documentation_neatness }}
                                </td>
                            </tr>
                            <tr>
                                <td>4. Has the employee followed administrative procedures and job instructions properly?
                                </td>
                                <td><span class="review-rating">{{ $review->followed_instructions }}/5</span></td>
                                <td>{{ $review->comments_followed_instructions }}</td>
                            </tr>
                            <tr>
                                <td>5. Does the employee effectively manage time and stay productive during working hours?
                                </td>
                                <td><span class="review-rating">{{ $review->productive }}/5</span></td>
                                <td>{{ $review->comments_productive }}</td>
                            </tr>
                            <tr>
                                <td>6. How well does the employee handle changes in schedules or assignments?</td>
                                <td><span class="review-rating">{{ $review->changes_schedules }}/5</span></td>
                                <td>{{ $review->comments_changes_schedules }}</td>
                            </tr>
                            <tr>
                                <td>7. Does the employee consistently adhere to the company's leave policy?</td>
                                <td><span class="review-rating">{{ $review->leave_policy }}/5</span></td>
                                <td>{{ $review->comments_leave_policy }}</td>
                            </tr>
                            <tr>
                                <td>8. Has there been any salary deduction due to the employee's leave?</td>
                                <td><span class="review-rating">{{ $review->salary_deduction }}/5</span></td>
                                <td>{{ $review->comments_salary_deduction }}</td>
                            </tr>
                            <tr>
                                <td>9. How well does the employee interact with the housekeeping staff?</td>
                                <td><span class="review-rating">{{ $review->interact_housekeeping }}/5</span></td>
                                <td>{{ $review->comments_interact_housekeeping }}</td>
                            </tr>
                            <tr class="review-total-row">
                                <td>Total Review</td>
                                <td><span class="review-rating">{{ $review->AdminTotalReview }}/45</span></td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#reviewHistoryTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": false, // Disable ordering
                "info": true,
                "lengthMenu": [5, 10, 25, 50], // Allow different page lengths
                "columnDefs": [{
                        "targets": [0, 1],
                        "searchable": true
                    } // Enable search on the first two columns
                ]
            });
        });
    </script>
@endsection
