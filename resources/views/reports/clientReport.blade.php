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
    .set-position tr{
            display: flex;
            gap: 100px;
           
        }
        .set-position tr, .set-position th,  .set-position td,  .set-position tbody{
             cursor: auto !important;
            }

        .set-position td , .set-position th{
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
<div class="container table-container span-tage">
    <div class="table-responsive table-wrapper">
        <table class="table table-bordered table-hover main-table">
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Rating</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientQuestions as $item)
                    <tr>
                        <td>{{ $item['question'] }}</td>
                        <td>({{ $user->{$item['rating']} ?? 'N/A' }}/5)</td>
                        <td>{{ $user->{$item['comment']} ?? 'N/A' }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td>Total Client Review</td>
                    <td>{{ $user->ClientTotalReview ?? 'N/A' }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>