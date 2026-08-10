$(function () {
    window.__evaluationFormHandled = true;

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });

    const sessionEmail = window.evaluationConfig?.sessionEmail || '';
    const submitUrl = window.evaluationConfig?.submitUrl || '';
    const sendOtpUrl = window.evaluationConfig?.sendOtpUrl || '';
    const verifyOtpUrl = window.evaluationConfig?.verifyOtpUrl || '';
    const duplicateCheckUrl = window.evaluationConfig?.duplicateCheckUrl || '';
    const userType = (window.evaluationConfig?.userType || '').toString().toLowerCase();
    const employeeStatus = (window.evaluationConfig?.employeeStatus || '').toString().toLowerCase();
    const probationDate = window.evaluationConfig?.probationDate || '';
    const isProbationLocked = Boolean(window.evaluationConfig?.isProbationLocked) || employeeStatus.includes('probation');

    const $form = $("#evaluationForm");
    const $submitBtn = $("#evaluationSubmitBtn");
    let duplicateSubmissionLocked = false;

    function errorAlert(message, fieldSelector = null) {
        Swal.fire({ icon: 'error', title: 'Validation Error', text: message }).then(() => {
            if (fieldSelector) {
                focusField(fieldSelector);
            }
        });
    }

    function focusField(selector) {
        const $field = $(selector);
        if (!$field.length) {
            return;
        }

        const $visibleField = $field.is(':visible') ? $field : $field.closest('.content-block, .form-section, .review-block, .row, .col-12');
        $('html, body').animate({
            scrollTop: Math.max($visibleField.offset().top - 120, 0)
        }, 300);

        if ($field.is('select, input, textarea')) {
            $field.trigger('focus');
        } else {
            $visibleField.attr('tabindex', '-1').trigger('focus');
        }
    }

    function markFieldInvalid(selector) {
        const $field = $(selector);
        if (!$field.length) {
            return;
        }

        $field.addClass('is-invalid');
    }

    function clearFieldInvalid(selector) {
        const $field = $(selector);
        if (!$field.length) {
            return;
        }

        $field.removeClass('is-invalid');
    }

    function showProbationAlert() {
        const probationText = probationDate
            ? `Evaluation cannot be submitted while the employee is under probation period. Probation date: ${probationDate}.`
            : 'Evaluation cannot be submitted while the employee is under probation period.';

        Swal.fire({
            icon: 'warning',
            title: 'Under Probation',
            text: probationText
        });
    }

    function isProbationUser() {
        return isProbationLocked;
    }

    function getRatingValue(id) {
        return parseInt($('#' + id).val(), 10) || 0;
    }

    function updateTotals() {
        const qualityWork = getRatingValue('qw1') + getRatingValue('qw2') + getRatingValue('qw3');
        const workHabits = getRatingValue('wh1') + getRatingValue('wh2') + getRatingValue('wh3') + getRatingValue('wh4');
        const jobKnowledge = getRatingValue('jk1') + getRatingValue('jk2') + getRatingValue('jk3');
        const interpersonal = getRatingValue('ir1') + getRatingValue('ir2') + getRatingValue('ir3') + getRatingValue('ir4') + getRatingValue('ir5');
        const leadership = getRatingValue('ls1') + getRatingValue('ls2') + getRatingValue('ls3');
        const total = qualityWork + workHabits + jobKnowledge + interpersonal + leadership;

        $('#qualityworktotalrating').val(qualityWork);
        $('#work_habits_rating').val(workHabits);
        $('#jk_total_rating').val(jobKnowledge);
        $('#ir_total_rating').val(interpersonal);
        $('#leadership_rating').val(leadership);
        $('#total-score').text(total);
        $('#total_scoring_system').val(total);
    }

    function applyMandatoryFields() {
        $form.find('input, select, textarea').each(function () {
            const $field = $(this);
            const type = ($field.attr('type') || '').toLowerCase();

            if ($field.is('[readonly]') || $field.is('[disabled]')) {
                return;
            }

            if (type === 'hidden' || type === 'radio') {
                return;
            }

            if (type === 'file' || type === 'date' || type === 'text' || $field.is('select, textarea')) {
                $field.prop('required', true);
            }
        });
    }

    function updateEvaluationCommentCounter(textarea) {
        const limit = parseInt(textarea.getAttribute('maxlength'), 10) || 1500;
        const count = textarea.value.length;
        const counter = textarea.parentElement.querySelector('.evaluation-char-counter');

        if (counter) {
            counter.textContent = `${count}/${limit}`;
            counter.classList.toggle('is-limit', count >= limit);
        }
    }

    function initEvaluationCommentCounters() {
        $form.find('textarea').each(function () {
            this.setAttribute('maxlength', '1500');

            if (!this.parentElement.querySelector('.evaluation-char-counter')) {
                $(this).after('<div class="evaluation-char-counter" aria-live="polite">0/1500</div>');
            }

            updateEvaluationCommentCounter(this);
        });
    }

    function validateForm() {
        if (isProbationUser()) {
            return {
                message: 'Evaluation cannot be submitted while the employee is under probation period.',
                field: null
            };
        }

        if (duplicateSubmissionLocked) {
            return {
                message: 'You already submitted for this financial year.',
                field: null
            };
        }

        if (!$form[0].checkValidity()) {
            $form[0].reportValidity();
            const firstInvalid = $form[0].querySelector(':invalid');
            $form.find(':invalid').each(function () {
                markFieldInvalid(this);
            });
            return {
                message: 'Please complete all required fields.',
                field: firstInvalid ? `#${firstInvalid.id}` : null
            };
        }

        const financialYear = $('#financialYear').val();
        if (!financialYear) {
            markFieldInvalid('#financialYear');
            return {
                message: 'Please select a financial year.',
                field: '#financialYear'
            };
        }

        const requiredRatings = ['qw1', 'qw2', 'qw3', 'wh1', 'wh2', 'wh3', 'wh4', 'jk1', 'jk2', 'jk3', 'ir1', 'ir2', 'ir3', 'ir4', 'ir5', 'ls1', 'ls2', 'ls3'];
        for (const id of requiredRatings) {
            if (getRatingValue(id) <= 0) {
                markFieldInvalid('#' + id);
                return {
                    message: 'Please complete all rating fields before sending OTP.',
                    field: `#${id}`
                };
            }
        }

        if (!$('#evalutors_name').val().trim()) {
            markFieldInvalid('#evalutors_name');
            return {
                message: "Evaluator's name is required.",
                field: '#evalutors_name'
            };
        }
        if (!$('#signatur').val()) {
            markFieldInvalid('#signatur');
            return {
                message: 'Signature upload is required.',
                field: '#signatur'
            };
        }
        if (!$('#evaluator_date').val()) {
            markFieldInvalid('#evaluator_date');
            return {
                message: 'Evaluator date is required.',
                field: '#evaluator_date'
            };
        }

        return null;
    }

    function showOtpModal() {
        if ($.fn.modal) {
            $("#otpModal").modal("show");
        } else {
            $("#otpModal").addClass("show").css("display", "block");
        }
    }

    function hideOtpModal() {
        if ($.fn.modal) {
            $("#otpModal").modal("hide");
        } else {
            $("#otpModal").removeClass("show").css("display", "none");
        }
    }

    function setSubmitDisabled(disabled, text) {
        $submitBtn.prop('disabled', disabled);
        if (text) {
            $submitBtn.text(text);
        }
    }

    function isFormComplete() {
        let complete = true;
        const checkedRadioGroups = new Set();

        $form.find('input, select, textarea').each(function () {
            if (!complete) {
                return;
            }

            const field = this;
            const $field = $(field);
            const type = ($field.attr('type') || '').toLowerCase();

            if ($field.is('[disabled]') || $field.is('[readonly]') || type === 'hidden') {
                return;
            }

            if ($field.is('select')) {
                const selectedOption = field.options[field.selectedIndex];
                if (!$field.val() || (selectedOption && selectedOption.disabled)) {
                    complete = false;
                }
                return;
            }

            if (type === 'radio') {
                const name = $field.attr('name');
                if (!name || checkedRadioGroups.has(name)) {
                    return;
                }

                checkedRadioGroups.add(name);
                const selectedRadio = $form.find('input[type="radio"]').filter(function () {
                    return this.name === name && this.checked;
                });

                if (selectedRadio.length === 0) {
                    complete = false;
                }
                return;
            }

            if (!field.checkValidity()) {
                complete = false;
            }
        });

        return complete;
    }

    function updateSubmitAvailability() {
        if (isProbationUser()) {
            setSubmitDisabled(true, 'Under Probation');
            $submitBtn.attr('title', 'Evaluation cannot be submitted while the employee is under probation period.');
            return;
        }

        if (duplicateSubmissionLocked) {
            setSubmitDisabled(true, 'Already Submitted');
            return;
        }

        $submitBtn.removeAttr('title');
        setSubmitDisabled(!isFormComplete(), 'Submit');
    }

    function checkExistingEvaluation() {
        if (isProbationUser()) {
            updateSubmitAvailability();
            return;
        }

        const empId = $('#emp_id').val();
        const financialYear = $('#financialYear').val();

        if (!empId || !financialYear) {
            setSubmitDisabled(true, 'Submit');
            return;
        }

        setSubmitDisabled(true, 'Checking...');

        $.ajax({
            url: duplicateCheckUrl,
            type: 'POST',
            data: {
                emp_id: empId,
                financial_year: financialYear
            },
            success: function (res) {
                duplicateSubmissionLocked = !!res.exists;

                if (duplicateSubmissionLocked) {
                    setSubmitDisabled(true, 'Already Submitted');
                    $submitBtn.attr('title', res.message || 'Evaluation already submitted for this financial year.');
                } else {
                    $submitBtn.removeAttr('title');
                    updateSubmitAvailability();
                }
            },
            error: function () {
                duplicateSubmissionLocked = false;
                $submitBtn.removeAttr('title');
                updateSubmitAvailability();
            }
        });
    }

    function submitEvaluationForm() {
        const formData = new FormData($form[0]);
        formData.append("email", sessionEmail);

        const empId = $("#emp_id").val();
        const financialYear = $("#financialYear").val();

        $.ajax({
            url: duplicateCheckUrl,
            type: "POST",
            data: {
                emp_id: empId,
                financial_year: financialYear
            },
            success: function (res) {
                if (res.exists) {
                    $("#loaderOverlay").hide();
                    Swal.fire({ icon: 'warning', title: 'Duplicate Submission', text: res.message });
                    duplicateSubmissionLocked = true;
                    setSubmitDisabled(true, 'Already Submitted');
                    return;
                }

                $.ajax({
                    url: submitUrl,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $("#loaderOverlay").hide();

                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message || 'Evaluation submitted successfully.'
                            }).then(() => {
                                $form[0].reset();
                                window.location.href = response.redirect_url;
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Submission Failed',
                                text: response.message || 'Failed to submit evaluation.'
                            });
                        }
                    },
                    error: function (xhr) {
                        $("#loaderOverlay").hide();
                        let message = 'Something went wrong! Please try again.';
                        if (xhr.responseJSON?.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire({ icon: 'error', title: 'Error', text: message });
                    }
                });
            },
            error: function () {
                $("#loaderOverlay").hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Could not validate employee. Please try again.'
                });
            }
        });
    }

    $submitBtn.on('click', function (e) {
        e.preventDefault();

        if (isProbationUser()) {
            showProbationAlert();
            return;
        }

        if ($submitBtn.prop('disabled')) {
            return;
        }

        const validationError = validateForm();
        if (validationError) {
            errorAlert(validationError.message || validationError, validationError.field || null);
            return;
        }

        const $btn = $(this);
        $btn.prop('disabled', true).text('Please wait...');

        $.ajax({
            url: sendOtpUrl,
            type: "POST",
            data: { email: sessionEmail },
            success: function (response) {
                if (response.success) {
                    showOtpModal();
                } else {
                    errorAlert(response.message || 'Failed to send OTP!');
                    updateSubmitAvailability();
                }
            },
            error: function () {
                errorAlert('Something went wrong! Please try again.');
                updateSubmitAvailability();
            }
        });
    });

    $("#otpForm").on("submit", function (event) {
        event.preventDefault();

        $.ajax({
            url: verifyOtpUrl,
            type: "POST",
            data: {
                email: sessionEmail,
                otp: $("input[name='otp']").val()
            },
            success: function (response) {
                if (response.success) {
                    $("input[name='otp']").val("");
                    hideOtpModal();
                    $("#loaderOverlay").show();
                    submitEvaluationForm();
                } else {
                    errorAlert(response.message || "Invalid OTP!");
                }
            },
            error: function () {
                errorAlert("Enter Valid OTP! Please try again.");
            }
        });
    });

    $("#otpModal .close").on("click", function () {
        hideOtpModal();
    });

    $("#otpModal").on("hidden.bs.modal", function () {
        $("input[name='otp']").val("");
        updateSubmitAvailability();
    });

    function bindRatingListeners() {
        const ids = ['qw1', 'qw2', 'qw3', 'wh1', 'wh2', 'wh3', 'wh4', 'jk1', 'jk2', 'jk3', 'ir1', 'ir2', 'ir3', 'ir4', 'ir5', 'ls1', 'ls2', 'ls3'];
        ids.forEach(function (id) {
            $('#' + id).on('change input', function () {
                clearFieldInvalid('#' + id);
                updateTotals();
            });
        });
        updateTotals();
        updateSubmitAvailability();
    }

    function bindValidationStyles() {
        $form.on('input change blur', 'input, select, textarea', function () {
            clearFieldInvalid(this);

            if (this.tagName && this.tagName.toLowerCase() === 'textarea') {
                updateEvaluationCommentCounter(this);
            }

            updateSubmitAvailability();
        });

        $form.on('invalid', 'input, select, textarea', function () {
            markFieldInvalid(this);
        });
    }

    $('#financialYear').on('change', checkExistingEvaluation);

    applyMandatoryFields();
    initEvaluationCommentCounters();
    bindValidationStyles();
    bindRatingListeners();
    updateSubmitAvailability();
    checkExistingEvaluation();
});
