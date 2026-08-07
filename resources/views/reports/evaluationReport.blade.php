<link href="{{ asset('css/review-detail-readonly.css') }}?v={{ filemtime(public_path('css/review-detail-readonly.css')) }}" rel="stylesheet">

@php
    $evaluation = $user;
    $totalScore = (float) ($evaluation->total_scoring_system ?? 0);
    $totalScoreDisplay = floor($totalScore) === $totalScore
        ? number_format($totalScore, 0)
        : rtrim(rtrim(number_format($totalScore, 2, '.', ''), '0'), '.');
@endphp

<div class="review-read-page review-read-page--embedded">
    <div class="review-read-card">
        <div class="review-read-section-title">
            <i class="bi bi-person-vcard"></i>
            Employee and Evaluation Details
        </div>
        <div class="table-wrapper">
            <table class="table table-bordered table-hover main-table">
                <tbody>
                    <tr>
                        <td>Designation:</td>
                        <td>{{ $evaluation->designation }}</td>
                    </tr>
                    <tr>
                        <td>Salary Grade/Band:</td>
                        <td>{{ $evaluation->salary_grade }}</td>
                    </tr>
                    <tr>
                        <td>Name of Employee:</td>
                        <td>{{ $evaluation->employee_name }}</td>
                    </tr>
                    <tr>
                        <td>Employee Id:</td>
                        <td>{{ $evaluation->emp_id }}</td>
                    </tr>
                    <tr>
                        <td>Division:</td>
                        <td>{{ $evaluation->division }}</td>
                    </tr>
                    <tr>
                        <td>Manager Name:</td>
                        <td>{{ $evaluation->manager_name }}</td>
                    </tr>
                    <tr>
                        <td>Joining Date:</td>
                        <td>{{ $evaluation->joining_date }}</td>
                    </tr>
                    <tr>
                        <td>Evaluation Purpose:</td>
                        <td>{{ $evaluation->evaluation_purpose }}</td>
                    </tr>
                    <tr>
                        <td>Review Period:</td>
                        <td>{{ $evaluation->review_period }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="review-read-card second-table">
        <div class="review-read-section-title">
            <i class="bi bi-card-checklist"></i>
            Evaluation Scores and Comments
        </div>
        <div class="table-wrapper">
            <table class="table table-bordered table-hover main-table evaluation-report-score-table">
                <thead>
                    <tr>
                        <th>Field</th>
                        <th>Rating</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1. Accuracy, neatness and timeliness of work</td>
                        <td><span class="review-rating">{{ $evaluation->accuracy_neatness }}/5</span></td>
                        <td>{{ $evaluation->comments_accuracy }}</td>
                    </tr>
                    <tr>
                        <td>2. Adherence to duties and procedures in Job Description and Work Instructions</td>
                        <td><span class="review-rating">{{ $evaluation->adherence }}/5</span></td>
                        <td>{{ $evaluation->comments_adherence }}</td>
                    </tr>
                    <tr>
                        <td>3. Synchronization with organizations/functional goals</td>
                        <td><span class="review-rating">{{ $evaluation->synchronization }}/5</span></td>
                        <td>{{ $evaluation->comments_synchronization }}</td>
                    </tr>
                    <tr class="review-total-row">
                        <td>Quality of Work Total Rating</td>
                        <td><span class="review-rating">{{ $evaluation->qualityworktotalrating }}/15</span></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>1. Punctuality to workplace</td>
                        <td><span class="review-rating">{{ $evaluation->punctuality }}/5</span></td>
                        <td>{{ $evaluation->comments_punctuality }}</td>
                    </tr>
                    <tr>
                        <td>2. Attendance</td>
                        <td><span class="review-rating">{{ $evaluation->attendance }}/5</span></td>
                        <td>{{ $evaluation->comments_attendance }}</td>
                    </tr>
                    <tr>
                        <td>3. Does the employee stay busy, look for things to do, take initiatives at workplace</td>
                        <td><span class="review-rating">{{ $evaluation->initiatives_at_workplace }}/5</span></td>
                        <td>{{ $evaluation->comments_initiatives }}</td>
                    </tr>
                    <tr>
                        <td>4. Submits reports on time and meets deadlines</td>
                        <td><span class="review-rating">{{ $evaluation->submits_reports }}/5</span></td>
                        <td>{{ $evaluation->comments_submits_reports }}</td>
                    </tr>
                    <tr class="review-total-row">
                        <td>Work Habits Total Rating</td>
                        <td><span class="review-rating">{{ $evaluation->work_habits_rating }}/20</span></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>1. Skill and ability to perform job satisfactorily</td>
                        <td><span class="review-rating">{{ $evaluation->skill_ability }}/5</span></td>
                        <td>{{ $evaluation->comments_skill_ability }}</td>
                    </tr>
                    <tr>
                        <td>2. Shown interest in learning and improving</td>
                        <td><span class="review-rating">{{ $evaluation->learning_improving }}/5</span></td>
                        <td>{{ $evaluation->comments_learning_improving }}</td>
                    </tr>
                    <tr>
                        <td>3. Problem solving ability</td>
                        <td><span class="review-rating">{{ $evaluation->problem_solving_ability }}/5</span></td>
                        <td>{{ $evaluation->comments_problem_solving }}</td>
                    </tr>
                    <tr class="review-total-row">
                        <td>Job Knowledge Total Rating</td>
                        <td><span class="review-rating">{{ $evaluation->jk_total_rating }}/15</span></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>1. Responds and contributes to team efforts</td>
                        <td><span class="review-rating">{{ $evaluation->respond_contributes }}/5</span></td>
                        <td>{{ $evaluation->comments_respond_contributes }}</td>
                    </tr>
                    <tr>
                        <td>2. Responds positively to suggestions, instructions, and criticism</td>
                        <td><span class="review-rating">{{ $evaluation->responds_positively }}/5</span></td>
                        <td>{{ $evaluation->comments_responds_positively }}</td>
                    </tr>
                    <tr>
                        <td>3. Keeps supervisor informed of all details</td>
                        <td><span class="review-rating">{{ $evaluation->supervisor }}/5</span></td>
                        <td>{{ $evaluation->comments_supervisor }}</td>
                    </tr>
                    <tr>
                        <td>4. Adapts well to changing circumstances</td>
                        <td><span class="review-rating">{{ $evaluation->adapts_changing }}/5</span></td>
                        <td>{{ $evaluation->comments_adapts_changing }}</td>
                    </tr>
                    <tr>
                        <td>5. Seeks feedback to improve</td>
                        <td><span class="review-rating">{{ $evaluation->seeks_feedback }}/5</span></td>
                        <td>{{ $evaluation->comments_seeks_feedback }}</td>
                    </tr>
                    <tr class="review-total-row">
                        <td>Interpersonal Relations Total Rating</td>
                        <td><span class="review-rating">{{ $evaluation->ir_total_rating }}/25</span></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>1. Aspirant to climb up the ladder, accepts challenges, new responsibilities, and roles</td>
                        <td><span class="review-rating">{{ $evaluation->challenges }}/10</span></td>
                        <td>{{ $evaluation->comments_challenges }}</td>
                    </tr>
                    <tr>
                        <td>2. Innovative thinking - contribution to organizations, functions, and personal growth</td>
                        <td><span class="review-rating">{{ $evaluation->personal_growth }}/10</span></td>
                        <td>{{ $evaluation->comments_personal_growth }}</td>
                    </tr>
                    <tr>
                        <td>3. Work motivation</td>
                        <td><span class="review-rating">{{ $evaluation->work_motivation }}/5</span></td>
                        <td>{{ $evaluation->comments_work_motivation }}</td>
                    </tr>
                    <tr class="review-total-row">
                        <td>Leadership Skill Total Rating</td>
                        <td><span class="review-rating">{{ $evaluation->leadership_rating }}/25</span></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>1. Employee performance and learning is unsatisfactory and is failing to improve at a satisfactory rate</td>
                        <td><span class="review-rating">{{ $evaluation->progress_unsatisfactory }}</span></td>
                        <td>{{ $evaluation->comments_unsatisfactory }}</td>
                    </tr>
                    <tr>
                        <td>2. Employee performance and learning is acceptable and is improving at a satisfactory rate</td>
                        <td><span class="review-rating">{{ $evaluation->progress_acceptable }}</span></td>
                        <td>{{ $evaluation->comments_acceptable }}</td>
                    </tr>
                    <tr>
                        <td>3. Employee has successfully demonstrated outstanding overall performance</td>
                        <td><span class="review-rating">{{ $evaluation->progress_outstanding }}</span></td>
                        <td>{{ $evaluation->comments_outstanding }}</td>
                    </tr>
                    <tr>
                        <td>Evaluator's Name</td>
                        <td>{{ $evaluation->evalutors_name }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Evaluator's Signature</td>
                        <td>
                            @if (!empty($evaluation->evaluator_signatur))
                                <img src="{{ asset('storage/' . $evaluation->evaluator_signatur) }}" alt="Evaluator's Signature" class="review-signature">
                            @else
                                N/A
                            @endif
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Evaluation Date</td>
                        <td>{{ $evaluation->evaluator_signatur_date }}</td>
                        <td></td>
                    </tr>
                    <tr class="review-total-row">
                        <td>Total Scoring System</td>
                        <td><span class="review-rating">{{ $totalScoreDisplay }}/100</span></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
