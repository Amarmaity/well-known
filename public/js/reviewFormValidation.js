$(function () {
    function markInvalid(el) {
        $(el).addClass('is-invalid');
    }

    function clearInvalid(el) {
        $(el).removeClass('is-invalid');
    }

    function focusField(el) {
        const $el = $(el);
        if (!$el.length) return;
        $('html, body').animate({
            scrollTop: Math.max($el.offset().top - 120, 0)
        }, 300);
        $el.trigger('focus');
    }

    function showError(message, field) {
        Swal.fire({ icon: 'error', title: 'Validation Error', text: message }).then(() => {
            if (field) focusField(field);
        });
    }

    function bindClear(form) {
        $(form).on('input change blur', 'input, select, textarea', function () {
            clearInvalid(this);
        });
    }

    function firstInvalidField(form) {
        return form.querySelector('select[required]:invalid, input[required]:invalid, textarea[required]:invalid');
    }

    function validateCommon(form) {
        const $form = $(form);
        let invalid = null;

        const empId = $form.find('input[name="emp_id"]').val();
        const financialYear = $form.find('select[name="financial_year"]').val();

        if (!financialYear) {
            invalid = $form.find('select[name="financial_year"]')[0];
            markInvalid(invalid);
            return { message: 'Please select a financial year.', field: invalid };
        }

        if (!empId) {
            invalid = $form.find('input[name="emp_id"]')[0];
            markInvalid(invalid);
            return { message: 'Please select an employee first.', field: invalid };
        }

        const requiredFields = form.querySelectorAll('select[required], input[required]:not([type="hidden"]), textarea[required]');
        for (const field of requiredFields) {
            if (!field.value || !field.value.trim || !field.value.trim()) {
                markInvalid(field);
                const label = $(field).prev('label').text().trim() || 'Required field';
                return { message: `${label} is required.`, field };
            }
        }

        const browserInvalid = firstInvalidField(form);
        if (browserInvalid) {
            markInvalid(browserInvalid);
            return { message: 'Please complete all required fields.', field: browserInvalid };
        }

        return null;
    }

    function bindReviewForm(formSelector, submitButtonSelector, totalSelector, totalCalcFn, submitAjaxFn) {
        const form = document.querySelector(formSelector);
        if (!form) return;

        bindClear(form);

        $(submitButtonSelector).on('click', function (e) {
            e.preventDefault();

            const validation = validateCommon(form);
            if (validation) {
                showError(validation.message, validation.field);
                return;
            }

            submitAjaxFn(form, totalSelector, totalCalcFn);
        });
    }

    window.reviewValidation = {
        bindReviewForm,
    };
});
