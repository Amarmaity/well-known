$(function () {
    function markInvalid($el) {
        $el.addClass('is-invalid');
    }

    function clearInvalid($el) {
        $el.removeClass('is-invalid');
    }

    function focusField($el) {
        if (!$el || !$el.length) return;
        $('html, body').animate({
            scrollTop: Math.max($el.offset().top - 120, 0)
        }, 300);
        $el.trigger('focus');
    }

    function validateForm(form) {
        const $form = $(form);
        const financialYear = $form.find('#financialYear');
        if (financialYear.length && !financialYear.val()) {
            markInvalid(financialYear);
            return { message: 'Please select a financial year.', field: financialYear };
        }

        const empId = $form.find('input[name="emp_id"]');
        if (empId.length && !empId.val()) {
            markInvalid(empId);
            return { message: 'Please select an employee first.', field: empId };
        }

        const requiredControls = $form.find('select[required], input[required], textarea[required]');
        for (const el of requiredControls.toArray()) {
            const $el = $(el);
            if ($el.is('[type="hidden"]') || $el.is('[disabled]')) {
                continue;
            }

            const val = $el.val();
            if (Array.isArray(val) ? val.length === 0 : !val) {
                markInvalid($el);
                return {
                    message: 'Please complete all required fields.',
                    field: $el
                };
            }
        }

        return null;
    }

    function bindClear(form) {
        $(form).on('input change blur', 'input, select, textarea', function () {
            clearInvalid($(this));
        });
    }

    function bind(formSelector, submitBtnSelector, calculateTotal, buildFormData, submitUrl) {
        const form = document.querySelector(formSelector);
        if (!form) return;

        bindClear(form);

        $(submitBtnSelector).off('click.reviewValidation').on('click.reviewValidation', function (e) {
            e.preventDefault();

            const validation = validateForm(form);
            if (validation) {
                Swal.fire({ icon: 'error', title: 'Validation Error', text: validation.message }).then(() => {
                    focusField(validation.field);
                });
                return;
            }

            const formData = buildFormData(form, calculateTotal());

            $.ajax({
                url: submitUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message || 'Submitted successfully.'
                    }).then(() => location.reload());
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Error submitting review.'
                    });
                }
            });
        });
    }

    window.reviewValidation = { bind };
});
