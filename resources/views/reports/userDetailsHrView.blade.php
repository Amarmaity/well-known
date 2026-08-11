@extends('layouts.app')

@section('title', 'HR Review Details')

@section('breadcrumb', "Employee {$employee_id} / View Hr Review")

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
                <i class="bi bi-people"></i>
                <div>
                    <h1>HR Review Details</h1>
                    <p>Employee ID: {{ $employee_id }}@if (!empty($financial_year))
                            Financial Year : {{ $financial_year }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="review-read-card">
            <div class="review-read-section-title">
                <i class="bi bi-card-checklist"></i>
                Review Scores and Comments
            </div>
            <div class="table-wrapper">
                <table id="hrReviewHistoryTable" class="table table-bordered table-hover main-table">
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
                                <td>1. How would you rate the employee’s adherence to company policies and procedures?</td>
                                <td><span class="review-rating">{{ $review->adherence_hr }}/5</span></td>
                                <td>{{ $review->comments_adherence_hr }}</td>
                            </tr>
                            <tr>
                                <td>2. Does the employee maintain professionalism and a positive attitude in the workplace?
                                </td>
                                <td><span class="review-rating">{{ $review->professionalism_positive }}/5</span></td>
                                <td>{{ $review->comments_professionalism }}</td>
                            </tr>
                            <tr>
                                <td>3. How well does the employee respond to feedback or suggestions for improvement from
                                    colleagues?</td>
                                <td><span class="review-rating">{{ $review->respond_feedback }}/5</span></td>
                                <td>{{ $review->comments_respond_feedback }}</td>
                            </tr>
                            <tr>
                                <td>3. Does the employee take the initiative to seek feedback and act on it?</td>
                                <td><span class="review-rating">{{ $review->initiative }}/5</span></td>
                                <td>{{ $review->comments_initiative }}</td>
                            </tr>
                            <tr>
                                <td>4. Has the employee shown interest in learning and participating in training programs?
                                </td>
                                <td><span class="review-rating">{{ $review->interest_learning }}/5</span></td>
                                <td>{{ $review->comments_interest_learning }}</td>
                            </tr>
                            <tr>
                                <td>5. Does the employee consistently adhere to the company's leave policy?</td>
                                <td><span class="review-rating">{{ $review->company_leave_policy }}/5</span></td>
                                <td>{{ $review->comments_company_leave_policy }}</td>
                            </tr>
                            <tr class="review-total-row">
                                <td>Total HR Review Score</td>
                                <td><span class="review-rating">{{ $review->HrTotalReview }}/30</span></td>
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
            $('#hrReviewHistoryTable').DataTable({
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
