@extends('layouts.app')

@section('title', 'Manager Review Details')

@section('breadcrumb', "Employee {$employee_id} / View Manager Review")

@section('body-class', 'special-page')


@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/review-detail-readonly.css') }}?v={{ filemtime(public_path('css/review-detail-readonly.css')) }}" rel="stylesheet">
@endpush

@section('content')
    <div class="review-read-page">
        <div class="review-read-header review-read-header--back-top">
            <a href="{{ url()->previous() }}" class="review-read-back">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>

            <div class="review-read-title">
                <i class="bi bi-diagram-3"></i>
                <div>
                    <h1>Manager Review Details</h1>
                    <p>Employee ID: {{ $employee_id }}@if(!empty($financial_year)) · {{ $financial_year }}@endif</p>
                </div>
            </div>
        </div>

    <div class="review-read-card">
        <div class="review-read-section-title">
            <i class="bi bi-card-checklist"></i>
            Review Scores and Comments
        </div>
        <div class="table-wrapper">
            <table id="managerReviewHistoryTable" class="table  table-bordered table-hover main-table">
                <thead>
                    <tr>
                        <th>Field</th>
                        <th>Rating</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $review)
                        <tr>
                            <td>1. How would you rate the employee’s quality of work, including accuracy, neatness, and timeliness?</td>
                            <td><span class="review-rating">{{ $review->rate_employee_quality }}/5</span></td>
                            <td>{{ $review->comments_rate_employee_quality }}</td>
                        </tr>
                        <tr>
                            <td>2. Does the employee align their work with the organization's goals and objectives?</td>
                            <td><span class="review-rating">{{ $review->organizational_goals }}/5</span></td>
                            <td>{{ $review->comments_organizational_goals }}</td>
                        </tr>
                        <tr>
                            <td>3. How effectively does the employee contribute to team efforts and collaborate with colleagues?</td>
                            <td><span class="review-rating">{{ $review->collaborate_colleagues }}/5</span></td>
                            <td>{{ $review->comments_collaborate_colleagues }}</td>
                        </tr>
                        <tr>
                            <td>4. Can you provide an example of when the employee demonstrated problem-solving skills?</td>
                            <td><span class="review-rating">{{ $review->demonstrated }}/5</span></td>
                            <td>{{ $review->comments_demonstrated }}</td>
                        </tr>
                        <tr>
                            <td>5. Has the employee shown leadership potential or accepted additional responsibilities?</td>
                            <td><span class="review-rating">{{ $review->leadership_responsibilities }}/5</span></td>
                            <td>{{ $review->comments_leadership_responsibilities }}</td>
                        </tr>
                        <tr>
                            <td>6. How would you rate the employee’s innovative thinking and contribution to team success?</td>
                            <td><span class="review-rating">{{ $review->thinking_contribution }}/5</span></td>
                            <td>{{ $review->comments_thinking_contribution }}</td>
                        </tr>
                        <tr>
                            <td>7. Does the employee effectively keep you informed about work progress and issues?</td>
                            <td><span class="review-rating">{{ $review->informed_progress }}/5</span></td>
                            <td>{{ $review->comments_comments_informed_progress }}</td>
                        </tr>
                        <tr class="review-total-row">
                            <td>Total Manager Review Score</td>
                            <td><span class="review-rating">{{ $review->ManagerTotalReview }}/35</span></td>
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
        $(document).ready(function () {
            $('#managerReviewHistoryTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": false,  // Disable ordering
                "info": true,
                "lengthMenu": [5, 10, 25, 50],  // Allow different page lengths
                "columnDefs": [
                    { "targets": [0, 1], "searchable": true }  // Enable search on the first two columns
                ]
            });
        });
    </script>
@endsection
