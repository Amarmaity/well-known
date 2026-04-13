$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    const sessionEmail = "{{ session('user_email') }}"; // or use 'otp_email' if more accurate

    $("#evaluationSubmit").submit(function (event) {
        event.preventDefault();

        const $submitBtn = $(this).find("button[type='submit']");
        $submitBtn.prop("disabled", true).text("Please wait...");

        let countdown = 5;
        let otpTimer = setInterval(() => {
            countdown--;
            if (countdown <= 0) {
                clearInterval(otpTimer);
                $submitBtn.prop("disabled", false).text("Resend OTP");
            } else {
                $submitBtn.text(`Please wait... (${countdown}s)`);
            }
        }, 1000);

        // Send OTP via AJAX
        $.ajax({
            url: "{{ route('evaluation-send-otp') }}",
            type: "POST",
            data: {
                email: sessionEmail,
            },
            success: function (response) {
                console.log("OTP Sent Response:", response);
                if (response.success) {
                    $("#otpModal").modal("show");
                } else {
                    alert(response.message || "Failed to send OTP!");
                    $submitBtn.prop("disabled", false).text("Save");
                }
            },
            error: function (xhr) {
                console.error("OTP Request Error:", xhr.responseText);
                alert("Something went wrong! Please try again.");
                $submitBtn.prop("disabled", false).text("Save");
            },
        });
    });

    // OTP Form Submit
    $("#otpForm").submit(function (event) {
        event.preventDefault();

        $.ajax({
            url: "{{ route('evaluation-verify-otp') }}",
            type: "POST",
            data: {
                email: sessionEmail,
                otp: $("input[name='otp']").val(),
                _token: "{{ csrf_token() }}",
            },
            success: function (response) {
                console.log("OTP Verified Response:", response);
                if (response.success) {
                    alert("OTP Verified!");
                    $("input[name='otp']").val("");
                    $("#otpModal").modal("hide");

                    $("#loaderOverlay").show();

                    submitEvaluationForm();
                } else {
                    alert(response.message || "Invalid OTP!");
                }
            },
            error: function (xhr) {
                console.error("OTP Verification Error:", xhr.responseText);
                alert("Enter Valid OTP! Please try again.");
            },
        });
    });

    $(".close").on("click", function () {
        $("#otpModal").modal("hide");
    });

    $("#otpModal").on("hidden.bs.modal", function () {
        $("input[name='otp']").val("");
    });

    // Final form submission with duplicate check
    function submitEvaluationForm() {
        let formData = new FormData($("#evaluationSubmit")[0]);
        formData.append("email", sessionEmail);

        const empId = $("#evaluationSubmit input[name='emp_id']").val();
        const financialYear = $(
            "#evaluationSubmit select[name='financial_year']",
        ).val(); // or 'input' if you're using text input

        $.ajax({
            url: "{{ route('check-duplicate-evaluation') }}",
            type: "POST",
            data: {
                emp_id: empId,
                financial_year: financialYear,
                _token: "{{ csrf_token() }}",
            },
            success: function (res) {
                if (res.exists) {
                    $("#loaderOverlay").hide();
                    Swal.fire({
                        icon: "warning",
                        title: "Duplicate Submission",
                        text: res.message,
                    });
                    return;
                }

                // Submit the final form
                $.ajax({
                    url: "{{ route('insert-data-evaluation') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $("#loaderOverlay").hide();

                        if (response.success) {
                            Swal.fire({
                                icon: "success",
                                title: "Success",
                                text: response.message,
                            }).then(() => {
                                $("#evaluationSubmit")[0].reset();
                                window.location.href = response.redirect_url;
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Submission Failed",
                                text:
                                    response.message ||
                                    "Failed to submit evaluation.",
                            });
                        }
                    },
                    error: function (xhr) {
                        $("#loaderOverlay").hide();
                        let errorMessage =
                            "Something went wrong! Please try again.";

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseText) {
                            try {
                                const res = JSON.parse(xhr.responseText);
                                if (res.message) {
                                    errorMessage = res.message;
                                }
                            } catch (e) {
                                console.warn("Failed to parse error JSON");
                            }
                        }

                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: errorMessage,
                        });
                    },
                });
            },
            error: function () {
                $("#loaderOverlay").hide();
                Swal.fire({
                    icon: "error",
                    title: "Validation Error",
                    text: "Could not validate employee. Please try again.",
                });
            },
        });
    }
});

// Utility: safely parse integer from input by id, fallback 0
function getIntValue(id) {
    const el = document.getElementById(id);
    if (!el) return 0;
    return parseInt(el.value) || 0;
}

// Update Quality Work total
function qualityWorkTotalRating() {
    const total = getIntValue("qw1") + getIntValue("qw2") + getIntValue("qw3");
    document.getElementById("qualityworktotalrating").value = total;
    //   console.log('Quality Work Total:', total);
}

// Update Work Habits total
function workHabitsTotalRating() {
    const total =
        getIntValue("wh1") +
        getIntValue("wh2") +
        getIntValue("wh3") +
        getIntValue("wh4");
    document.getElementById("work_habits_rating").value = total;
    //   console.log('Work Habits Total:', total);
}

// Update Job Knowledge total
function jobKnowledgeTotalRating() {
    const total = getIntValue("jk1") + getIntValue("jk2") + getIntValue("jk3");
    document.getElementById("jk_total_rating").value = total;
    //   console.log('Job Knowledge Total:', total);
}

// Update Interpersonal total
function interpersonalTotalRating() {
    const total =
        getIntValue("ir1") +
        getIntValue("ir2") +
        getIntValue("ir3") +
        getIntValue("ir4") +
        getIntValue("ir5");
    document.getElementById("ir_total_rating").value = total;
    //   console.log('Interpersonal Total:', total);
}

// Update Leadership total
function leadershipTotalRating() {
    const total = getIntValue("ls1") + getIntValue("ls2") + getIntValue("ls3");
    document.getElementById("leadership_rating").value = total;
    //   console.log('Leadership Total:', total);
}

// Calculate and display average rating
function calculateAverageRating() {
    const leadership =
        parseFloat(document.getElementById("leadership_rating").value) || 0;
    const interpersonal =
        parseFloat(document.getElementById("ir_total_rating").value) || 0;
    const jobKnowledge =
        parseFloat(document.getElementById("jk_total_rating").value) || 0;
    const workHabits =
        parseFloat(document.getElementById("work_habits_rating").value) || 0;
    const qualityWork =
        parseFloat(document.getElementById("qualityworktotalrating").value) ||
        0;

    const total =
        leadership + interpersonal + jobKnowledge + workHabits + qualityWork;
    //   console.log('Total:', total);

    const part1 = total;
    const average = (part1 / 100) * 100;

    document.getElementById("total-score").innerText = total;
    document.getElementById("total_scoring_system").value = average.toFixed(2);
}

// Update all subtotal ratings then calculate average
function updateAllTotals() {
    qualityWorkTotalRating();
    workHabitsTotalRating();
    jobKnowledgeTotalRating();
    interpersonalTotalRating();
    leadershipTotalRating();
    calculateAverageRating();
}

// Setup event listeners on all input fields involved to recalc totals on change/input
function setupEventListeners() {
    const allInputIds = [
        "qw1",
        "qw2",
        "qw3",
        "wh1",
        "wh2",
        "wh3",
        "wh4",
        "jk1",
        "jk2",
        "jk3",
        "ir1",
        "ir2",
        "ir3",
        "ir4",
        "ir5",
        "ls1",
        "ls2",
        "ls3",
    ];

    allInputIds.forEach((id) => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener("input", updateAllTotals);
        }
    });
}

// Initialize on page load
window.addEventListener("load", () => {
    setupEventListeners();
    updateAllTotals(); // initial calculation in case inputs have default values
});
