<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Side by Side Form</title>
    
    <style>
        .container {
            display: flex;
            gap: 20px;
            /* Adds space between divs */
            flex-wrap: wrap;
        }

        .form-section {
            width: 50%;
            /* Ensures both sections take equal space */
        }

        label {
            display: block;
            /* Ensures labels appear above inputs */
            margin-bottom: 5px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
        }

        /* Ensure Quality Work Total Rating input is longer */
        .qw-div2 input {
            width: 100%;
            padding: 12px;
            font-size: 16px;
        }

        .scoring-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .scoring-table th,
        .scoring-table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .scoring-table th {
            background-color: #f2f2f2;
        }

        .scoring-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</head>

<body>

    <!-- Centered Headings -->
    <div class="text-center">
        <h1>EVALUATION</h1>
        <h4>DELOSTYLE STUDIO PRIVATE LIMITED</h4>
    </div>

    <!-- Form Section -->
    <div class="container">
        <form action="{{route('insert-data')}}" method="post">
            @csrf
        <div class="form-section1">
            <div>
                <label for="designation">Designation:</label>
                <input type="text" name="designation" id="designation" placeholder="Enter designation" required>
            </div>
            <div>
                <label for="salary_grade">Salary Grade/Band:</label>
                <input type="text" name="salary_grade" id="salary_grade" placeholder="Enter Salary Grade" required>
            </div>
            <div>
                <label for="employee_name">Name of Employee:</label>
                <input type="text" name="employee_name" id="employee_name" placeholder="Enter name" required>
            </div>
            <div>
                <label for="department">Department:</label>
                <input type="text" name="department" id="department" placeholder="Enter Department" required>
            </div>
            <div>
                <label for="evaluation_purpose">Evaluation Purpose:</label>
                <input type="text" name="evaluation_purpose" id="evaluation_purpose" placeholder="Enter Evaluation Purpose" required>
            </div>
        </div>

        <div class="form-section2">
            <div>
                <label for="division">Division:</label>
                <input type="text" name="division" id="division" placeholder="Enter Division" required>
            </div>
            <div>
                <label for="manager_name">Manager Name:</label>
                <input type="text" name="manager_name" id="manager_name" placeholder="Enter Manager Name" required>
            </div>
            <div>
                <label for="joining_date">Joining Date:</label>
                <input type="date" name="joining_date" id="joining_date" required>
            </div>
            <div>
                <label for="review_period">Review Period:</label>
                <input type="text" name="review_period" id="review_period" placeholder="Enter Review Period" required>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>

    <div class="text-center mt-4">
        <h4>FUNCTIONAL SKILLS</h4>
        <h5>CRITERIA</h5>
        <h5>Quality of Work (Out of 15 Marks)</h5>
    </div>

    <div class="container">
        <!-- Left Section (Ratings) -->
        <div class="form-section">
            <div>
                <label for="accuracy_neatness">Accuracy, neatness and timeliness of work:</label>
                <input type="number" name="accuracy_neatness" id="qw1" min="0" max="5" required oninput="qualityWorkTotalRating()"
                    placeholder="Rate Yourself">
                <label for="comment">Justyfi Your Review.</label>
                <textarea name="comments_accuracy" id="comments" class="form-control" rows="5" cols="50"></textarea>
            </div>

            <div>
                <label for="adherence">Adherence to duties and procedures in Job Description and Work Instructions:</label>
                <input type="number" name="adherence" id="qw2" min="0" max="5" required oninput="qualityWorkTotalRating()"
                    placeholder="Rate Yourself">
                    <label for="comment">Justyfi Your Review.</label>
                    <textarea name="comments_adherence" id="comments" class="form-control" rows="5" cols="50"></textarea>
            </div>

            <div>
                <label for="synchronization">Synchronization with organizations/functional goals:</label>
                <input type="number" name="synchronization" id="qw3" min="0" max="5" required oninput="qualityWorkTotalRating()"
                    placeholder="Rate Yourself">
                    <textarea name="comments_synchronization" id="comments" class="form-control" rows="5" cols="50"></textarea>

            </div>
        </div>

        <!-- Right Section (Total Rating) -->
        <div class="form-section">
            <div>
                <label for="qualityworktotalrating">Quality of Work Total Rating:</label>
                <input type="text" name="qualityworktotalrating" id="qualityworktotalrating" readonly>
            </div>
        </div>
    </div>



     <!-- Work Habits Section -->
     <div class="text-center mt-4">
        <h4>Work Habits (Out of 20 Marks)</h4>
    </div>

    <div class="container">
        <!-- Left Section (Ratings) -->
        <div class="form-section">
            <div>
                <label for="punctuality">Punctuality to workplace:</label>
                <input type="number" name="punctuality" id="wh1" min="0" max="5" required oninput="workHabitsTotalRating()" placeholder="Rate Yourself">
                <label for="comments_punctuality">Justify Your Review:</label>
                <textarea name="comments_punctuality" id="comments_punctuality" class="form-control" rows="5" cols="50"></textarea>
            </div>
                        
                <div>
                    <label for="attendance">Attendance:</label>
                    <input type="number" name="attendance" id="wh2" min="0" max="5" required oninput="workHabitsTotalRating()" placeholder="Rate Yourself">
                    <label for="comments_attendance">Justify Your Review:</label>
                    <textarea name="comments_attendance" id="comments_attendance" class="form-control" rows="5" cols="50"></textarea>
                </div>

            <div>
                <label for="initiatives_at_workplace">Does the employee stay busy, look for things to do, take initiatives at workplace:</label>
                <input type="number" name="initiatives_at_workplace" id="wh3" min="0" max="5" required oninput="workHabitsTotalRating()" placeholder="Rate Yourself">
                <label for="comments_initiatives">Justify Your Review:</label>
                <textarea name="comments_initiatives" id="comments_initiatives" class="form-control" rows="5" cols="50"></textarea>
            </div>

            <div>
                <label for="submits_reports">Submits reports on time and meets deadlines:</label>
                <input type="number" name="submits_reports" id="wh4" min="0" max="5" required oninput="workHabitsTotalRating()" placeholder="Rate Yourself">
                <label for="comments_submits_reports">Justify Your Review:</label>
                <textarea name="comments_submits_reports" id="comments_submits_reports" class="form-control" rows="5" cols="50"></textarea>
            </div>
        </div>

        <!-- Right Section (Total Rating) -->
        <div class="form-section">
                <label for="work_habits_rating">Work Habits Total Rating:</label>
                <input type="text" name="work_habits_rating" id="work_habits_rating" readonly>
            </div>
        </div>
    </div>

    <!-- Job Knowledge Section -->
<div class="text-center mt-4">
    <h4>Job Knowledge (Out of 15 Marks)</h4>
</div>

<div class="container">
    <!-- Left Section (Ratings) -->
    <div class="form-section">
        <div>
            <label for="skill_ability">Skill and ability to perform job satisfactorily:</label>
            <input type="number" name="skill_ability" id="jk1" min="0" max="5" required oninput="jobKnowledgeTotalRating()" placeholder="Rate Yourself">
            <label for="comments_skill_ability">Justify Your Review:</label>
            <textarea name="comments_skill_ability" id="comments_skill_ability" class="form-control" rows="5" cols="50"></textarea>
        </div>

        <div>
            <label for="learning_improving">Shown interest in learning and improving:</label>
            <input type="number" name="learning_improving" id="jk2" min="0" max="5" required oninput="jobKnowledgeTotalRating()" placeholder="Rate Yourself">
            <label for="comments_learning_improving">Justify Your Review:</label>
            <textarea name="comments_learning_improving" id="comments_learning_improving" class="form-control" rows="5" cols="50"></textarea>
        </div>

        <div>
            <label for="problem_solving_ability">Problem solving ability:</label>
            <input type="number" name="problem_solving_ability" id="jk3" min="0" max="5" required oninput="jobKnowledgeTotalRating()" placeholder="Rate Yourself">
            <label for="comments_problem_solving">Justify Your Review:</label>
            <textarea name="comments_problem_solving" id="comments_problem_solving" class="form-control" rows="5" cols="50"></textarea>
        </div>
    </div>

    <!-- Right Section (Total Rating) -->
    <div class="form-section">
        <div>
            <label for="jk_total_rating">Job Knowledge Total Rating:</label>
            <input type="text" name="jk_total_rating" id="jk_total_rating" readonly>
        </div>
    </div>
</div>

    <!-- Scoring System Section -->
    <div class="text-center mt-4">
        <h4>Scoring System</h4>
    </div>

    {{-- <table class="scoring-table container">
        <thead>
            <tr>
                <th>Attribute</th>
                <th>Score</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr class="attribute-row">
                <td>Outstanding</td>
                <td class="score-column">5</td>
                <td class="empty-cell"></td>
            </tr>
            <tr class="attribute-row">
                <td>Exceeds Requirements</td>
                <td class="score-column">4</td>
                <td class="empty-cell"></td>
            </tr>
            <tr class="attribute-row">
                <td>Meets Requirements</td>
                <td class="score-column">3</td>
                <td class="empty-cell"></td>
            </tr>
            <tr class="attribute-row">
                <td>Need Improvement</td>
                <td class="score-column">2</td>
                <td class="empty-cell"></td>
            </tr>
            <tr class="attribute-row">
                <td>Unsatisfactory</td>
                <td class="score-column">1</td>
                <td class="empty-cell"></td>
            </tr>
        </tbody>
    </table> --}}

{{-- Recomendation part --}}

   <!-- Evaluator Recommendations Section -->
   <div class="container mt-4">
    <div>
        <label for="recommendation">Recommendations:</label>
        <textarea name="recomendation" id="evalution_recomendation" rows="5" cols="50"></textarea>
    </div>

    <div>
        <label for="evalutors_name">Evaluator's Name::</label>
        <input type="text" id="evalutors_name" name="evalutors_name" placeholder="Enter Name">
    </div>
    <div>
        <label for="signatur">Signature:</label>
        <input type="text" id="signatur" name="evaluator_signatur" placeholder="Signatur..">
    </div>

    <div>
        <label for="date">Date:</label>
        <input type="date" id="evaluator_date" name="evaluator_signatur_date" placeholder="Select Date" >
    </div> 
</div>

<!-- Interpersonal Relations/Behaviour Section -->
<div class="text-center mt-4">
    <h4>Interpersonal Relations/Behaviour (Out of 25 Marks)</h4>
</div>

<div class="container">
    <!-- Left Section (Ratings) -->
    <div class="form-section">
        <div>
            <label for="respond_contributes">Responds and contributes to team efforts:</label>
            <input type="number" name="respond_contributes" id="ir1" min="0" max="5" required oninput="interpersonalTotalRating()" placeholder="Rate Yourself">
            <label for="comments_respond_contributes">Justify Your Review:</label>
            <textarea name="comments_respond_contributes" id="comments_respond_contributes"  rows="5" cols="50"></textarea>
        </div>

        <div>
            <label for="responds_positively">Responds positively to suggestions, instructions, and criticism:</label>
            <input type="number" name="responds_positively" id="ir2" min="0" max="5" required oninput="interpersonalTotalRating()" placeholder="Rate Yourself">
            <label for="comments_responds_positively">Justify Your Review:</label>
            <textarea name="comments_responds_positively" id="comments_responds_positively" rows="5" cols="50"></textarea>
        </div>

        <div>
            <label for="supervisor">Keeps supervisor informed of all details:</label>
            <input type="number" name="supervisor" id="ir3" min="0" max="5" required oninput="interpersonalTotalRating()" placeholder="Rate Yourself">
            <label for="comments_supervisor">Justify Your Review:</label>
            <textarea name="comments_supervisor" id="comments_supervisor"  rows="5" cols="50"></textarea>
        </div>

        <div>
            <label for="adapts_changing">Adapts well to changing circumstances:</label>
            <input type="number" name="adapts_changing" id="ir4" min="0" max="5" required oninput="interpersonalTotalRating()" placeholder="Rate Yourself">
            <label for="comments_adapts_changing">Justify Your Review:</label>
            <textarea name="comments_adapts_changing" id="comments_adapts_changing"  rows="5" cols="50"></textarea>
        </div>

        <div>
            <label for="seeks_feedback">Seeks feedback to improve:</label>
            <input type="number" name="seeks_feedback" id="ir5" min="0" max="5" required oninput="interpersonalTotalRating()" placeholder="Rate Yourself">
            <label for="comments_seeks_feedback">Justify Your Review:</label>
            <textarea name="comments_seeks_feedback" id="comments_seeks_feedback"  rows="5" cols="50"></textarea>
        </div>
    </div>

    <!-- Right Section (Total Rating) -->
    <div class="form-section">
        <div>
            <label for="ir_total_rating">Interpersonal Relations Total Rating:</label>
            <input type="text" name="ir_total_rating" id="ir_total_rating" readonly>
        </div>
    </div>
</div>


<!-- Leadership Skills Section -->
<div class="text-center mt-4">
    <h4>Leadership (Out of 25 Marks)</h4>
</div>

<div class="container">
    <!-- Left Section (Ratings) -->
    <div class="form-section">
        <div>
            <label for="challenges">Aspirant to climb up the ladder, accepts challenges, new responsibilities, and roles (Out of 10):</label>
            <input type="number" name="challenges" id="ls1" min="0" max="10" required oninput="leadershipTotalRating()" placeholder="Rate Yourself">
            <label for="comments_challenges">Justify Your Review:</label>
            <textarea name="comments_challenges" id="comments_challenges" class="form-control" rows="5" cols="50"></textarea>
        </div>

        <div>
            <label for="personal_growth">Innovative thinking - contribution to organizations, functions, and personal growth (Out of 10):</label>
            <input type="number" name="personal_growth" id="ls2" min="0" max="10" required oninput="leadershipTotalRating()" placeholder="Rate Yourself">
            <label for="comments_personal_growth">Justify Your Review:</label>
            <textarea name="comments_personal_growth" id="comments_personal_growth" class="form-control" rows="5" cols="50"></textarea>
        </div>

        <div>
            <label for="work_motivation">Work motivation (Out of 5):</label>
            <input type="number" name="work_motivation" id="ls3" min="0" max="5" required oninput="leadershipTotalRating()" placeholder="Rate Yourself">
            <label for="comments_work_motivation">Justify Your Review:</label>
            <textarea name="comments_work_motivation" id="comments_work_motivation" class="form-control" rows="5" cols="50"></textarea>
        </div>
    </div>

    <!-- Right Section (Total Rating) -->
    <div class="form-section">
        <div>
            <label for="leadership_rating">Leadership Skill Total Rating:</label>
            <input type="text" name="leadership_rating" id="leadership_rating" readonly>
        </div>
    </div>
</div>

{{-- OVERALL PROGRESS --}}
<h4>OVERALL PROGRESS</h4>
<div>
    <label for="progress_unsatisfactory">Employee performance and learning is unsatisfactory and is failing to improve at a satisfactory rate:</label>
    <button type="button" id="progress_unsatisfactory" onclick="toggleYesNo('progress_unsatisfactory', 'comments_unsatisfactory')">Yes</button>
    <label for="comments_unsatisfactory">Justify Your Review:</label>
    <textarea name="comments_unsatisfactory" id="comments_unsatisfactory" class="form-control" rows="5" cols="50"></textarea>
</div>

<div>
    <label for="progress_acceptable">Employee performance and learning is acceptable and is improving at a satisfactory rate:</label>
    <button type="button" id="progress_acceptable" onclick="toggleYesNo('progress_acceptable', 'comments_acceptable')">Yes</button>
    <label for="comments_acceptable">Justify Your Review:</label>
    <textarea name="comments_acceptable" id="comments_acceptable" class="form-control" rows="5" cols="50"></textarea>
</div>

<div>
    <label for="progress_outstanding">Employee has successfully demonstrated outstanding overall performance:</label>
    <button type="button" id="progress_outstanding" onclick="toggleYesNo('progress_outstanding', 'comments_outstanding')">Yes</button>
    <label for="comments_outstanding">Justify Your Review:</label>
    <textarea name="comments_outstanding" id="comments_outstanding" class="form-control" rows="5" cols="50"></textarea>
</div>



 <!-- FINAL COMMENTS Section -->
 <div class="container mt-4">
    <div>
        <label for="final_comment">FINAL COMMENTS:</label>
        <textarea name="final_comment" id="f_comment" rows="5" cols="50"></textarea>
    </div>

    <div>
        <label for="director_name">Director's Name:</label>
        <input type="text" id="d_name" name="director_name" placeholder="Enter Name">
    </div>
    <div>
        <label for="signatur">Signature:</label>
        <input type="text" id="signatur" name="dirrector_signatur" placeholder="Signatur..">
    </div>

    <div>
        <label for="date">Date:</label>
        <input type="date" id="date" name="director_signatur_date" placeholder="Select Date" >
    </div> 
</div>


</body>

</html>

<script>

//total rating of qualityworktotalrating
function qualityWorkTotalRating() {
    var qwtotalrating1 = parseInt(document.getElementById('qw1').value) || 0;
    var qwtotalrating2 = parseInt(document.getElementById('qw2').value) || 0;
    var qwtotalrating3 = parseInt(document.getElementById('qw3').value) || 0;

    var qualityworktotalrating = qwtotalrating1 + qwtotalrating2 + qwtotalrating3;

    document.getElementById('qualityworktotalrating').value = qualityworktotalrating;
}

//total rating of wor habits.
function workHabitsTotalRating() {
        var workhabitsrating1 = parseInt(document.getElementById('wh1').value) || 0;
        var workhabitsrating2 = parseInt(document.getElementById('wh2').value) || 0;
        var workhabitsrating3 = parseInt(document.getElementById('wh3').value) || 0;
        var workhabitsrating4 = parseInt(document.getElementById('wh4').value) || 0;

        var workhabitstotalrating = workhabitsrating1 + workhabitsrating2 + workhabitsrating3 + workhabitsrating4;

        document.getElementById('work_habits_rating').value = workhabitstotalrating;
    }

//total rating  of job Knowladge
function jobKnowledgeTotalRating() {
        var jk1 = parseInt(document.getElementById('jk1').value) || 0;
        var jk2 = parseInt(document.getElementById('jk2').value) || 0;
        var jk3 = parseInt(document.getElementById('jk3').value) || 0;

        var jkTotal = jk1 + jk2 + jk3;

        document.getElementById('jk_total_rating').value = jkTotal;
    }

    //total rating of interpersonal skill
    function interpersonalTotalRating() {
        var ir1 = parseInt(document.getElementById('ir1').value) || 0;
        var ir2 = parseInt(document.getElementById('ir2').value) || 0;
        var ir3 = parseInt(document.getElementById('ir3').value) || 0;
        var ir4 = parseInt(document.getElementById('ir4').value) || 0;
        var ir5 = parseInt(document.getElementById('ir5').value) || 0;

        var irTotal = ir1 + ir2 + ir3 + ir4 + ir5;

        document.getElementById('ir_total_rating').value = irTotal;
    }

    //leader Ship total rating
    function leadershipTotalRating() {
        var ls1 = parseInt(document.getElementById('ls1').value) || 0;
        var ls2 = parseInt(document.getElementById('ls2').value) || 0;
        var ls3 = parseInt(document.getElementById('ls3').value) || 0;

        var leadershipTotal = ls1 + ls2 + ls3;

        document.getElementById('leadership_rating').value = leadershipTotal;
    }

     // Toggle function to switch between Yes and No
     function toggleYesNo(buttonId, textareaId) {
        var button = document.getElementById(buttonId);
        var textarea = document.getElementById(textareaId);

        if (button.innerText === 'Yes') {
            button.innerText = 'No';
            textarea.style.display = "block"; // Show textarea when "No" is selected
        } else {
            button.innerText = 'Yes';
            textarea.style.display = "block"; // Show textarea when "Yes" is selected
        }
    }

</script>