@extends('layouts.app')

@section('title', 'Client Review Details')
{{-- @section('breadcrumb',' /Employee'. ' ' .$employee_id ) --}}
@section('breadcrumb', "Employee {$employee_id} /Client Review")

@section('body-class', 'special-page')

@section('content')
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <link
            href="{{ asset('css/review-detail-readonly.css') }}?v={{ filemtime(public_path('css/review-detail-readonly.css')) }}"
            rel="stylesheet">
    @endpush

@php
    $clientQuestions = [
        ['question' => '1. Did the developer(s) understand your project requirements clearly?', 'rating' => 'understand_requirements', 'comment' => 'comment_understand_requirements'],
        ['question' => '2. Were your business goals and technical needs properly translated into the solution?', 'rating' => 'business_needs', 'comment' => 'comments_business_needs'],
        ['question' => '3. Was there a clear and detailed project scope defined at the beginning?', 'rating' => 'detailed_project_scope', 'comment' => 'comments_detailed_project_scope'],
        ['question' => '4. Was the developer(s) responsive and easy to reach during the project?', 'rating' => 'responsive_reach_project', 'comment' => 'comments_responsive_reach_project'],
        ['question' => '5. Did you feel comfortable discussing changes or suggestions?', 'rating' => 'comfortable_discussing', 'comment' => 'comments_comfortable_discussing'],
        ['question' => '6. Did the developer(s) provide regular updates on progress?', 'rating' => 'regular_updates', 'comment' => 'comments_regular_updates'],
        ['question' => '7. Were your questions and concerns addressed promptly?', 'rating' => 'concerns_addressed', 'comment' => 'comments_concerns_addressed'],
        ['question' => '8. How would you rate the technical expertise of the developer(s)?', 'rating' => 'technical_expertise', 'comment' => 'comments_technical_expertise'],
        ['question' => '9. Were industry best practices followed during the development process?', 'rating' => 'best_practices', 'comment' => 'comments_best_practices'],
        ['question' => '10. Did the developer(s) suggest innovative solutions or improvements?', 'rating' => 'suggest_innovative', 'comment' => 'comments_suggest_innovative'],
        ['question' => '11. How would you rate the quality of the code delivered?', 'rating' => 'quality_code', 'comment' => 'comments_quality_code'],
        ['question' => '12. Did you encounter any bugs or issues post-launch?', 'rating' => 'encounter_issues', 'comment' => 'comments_encounter_issues'],
        ['question' => '13. Was the code scalable and well-structured for future updates?', 'rating' => 'code_scalable', 'comment' => 'comments_code_scalable'],
        ['question' => '14. Did the solution perform well under expected load and conditions?', 'rating' => 'solution_perform', 'comment' => 'comments_solution_perform'],
        ['question' => '15. Was the project delivered on time?', 'rating' => 'project_delivered', 'comment' => 'comments_project_delivered'],
        ['question' => '16. If there were delays, were they communicated and handled effectively?', 'rating' => 'communicated_handled', 'comment' => 'comments_communicated_handled'],
        ['question' => '17. Was the development process well-organized and structured?', 'rating' => 'development_process', 'comment' => 'comments_development_process'],
        ['question' => '18. How well did the developer(s) handle unexpected challenges or changes?', 'rating' => 'unexpected_challenges', 'comment' => 'comments_unexpected_challenges'],
        ['question' => '19. Did the developer(s) propose effective workarounds when issues arose?', 'rating' => 'effective_workarounds', 'comment' => 'comments_effective_workarounds'],
        ['question' => '20. How quickly were bugs or issues resolved during the project?', 'rating' => 'bugs_issues', 'comment' => 'comments_bugs_issues'],
    ];
@endphp



    <style>
        .span-tage .span-data {
            display: flex;
            justify-content: space-between;
            padding-right: 60px;
        }

        .span-tage tr {
            /* border-bottom: 1px solid #000; */
            margin-bottom: 30px;
        }

        .set-position tr {
            display: flex;
            gap: 100px;
        }

        .set-position td {
            width: 100%;
        }

        .set-position thead tr {
            display: flex;
            justify-content: space-between;
        }

        .set-position thead tr th {
            display: flex;
            width: 100%;
            justify-content: flex-start;
        }

        @media (max-width: 1124px) {
            .set-position tr {
                gap: 40px;
            }
        }
    </style>

    <div class="review-read-page">
        <div class="review-read-header review-read-header--back-top">
            <a href="{{ url()->previous() }}" class="review-read-back">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>

            <div class="review-read-title">
                <i class="bi bi-briefcase"></i>
                <div>
                    <h1>Client Review Details</h1>
                    <p>Employee ID: {{ $employee_id }}@if (!empty($financial_year)) Financial Year : {{ $financial_year }}@endif</p>
                </div>
            </div>
        </div>

        <div class="review-read-card span-tage">
            <div class="review-read-section-title">
                {{-- <i class="bi bi-card-checklist"></i> --}}
                Review Scores and Comments
            </div>
            <div class="table-wrapper">
                <table id="clientReviewHistoryTable" class="table table-bordered table-hover main-table">
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>Rating</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $review)
                            @foreach ($clientQuestions as $item)
                                <tr>
                                    <td>{{ $item['question'] }}</td>
                                    <td>({{ $review->{$item['rating']} ?? 'N/A' }}/5)</td>
                                    <td>{{ $review->{$item['comment']} ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td>Total Client Review Score</td>
                                <td>{{ $review->ClientTotalReview ?? 'N/A' }}</td>
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
            $('#clientReviewHistoryTable').DataTable({
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