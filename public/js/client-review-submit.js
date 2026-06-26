
$(function () {
    let timeout = null;

    function searchUser() {
        const keyword = $('#employee_search').val().trim();

        if (keyword.length < 2) {
            $('#employeeDetails').hide();
            $('#selectLabel').hide();
            return;
        }

        $('#employeeDetails').show();
        $('#employeeTableBody').html('<tr><td colspan="4">Searching...</td></tr>');
        $('#selectLabel').hide();

        clearTimeout(timeout);

        timeout = setTimeout(function () {
            $.ajax({
                url: window.routes.clientSearch,
                type: 'GET',
                data: {
                    keyword: keyword
                },
                success: function (response) {
                    $('#employeeTableBody').empty();

                    if (response.success && response.users.length > 0) {
                        $('#selectLabel').show();

                        response.users.forEach(function (user) {
                            $('#employeeTableBody').append(`
                                        <tr class="selectable-row" data-emp-id="${user.employee_id}">
                                            <td>${user.employee_id}</td>
                                            <td>${user.fname} ${user.lname}</td>
                                            <td>${user.designation}</td>
                                            <td>${user.email}</td>
                                        </tr>
                                    `);
                        });
                    } else {
                        $('#selectLabel').hide();
                        $('#employeeTableBody').html(
                            '<tr><td colspan="4">No users found</td></tr>'
                        );
                    }
                },
                error: function () {
                    alert("An error occurred. Please try again.");
                }
            });
        }, 1000);
    }

    $('#employee_search').on('keyup', searchUser);

    $(document).on('click', '.selectable-row', function () {
        var empId = $(this).data('emp-id');
        $('#emp_id_input').val(empId);

        var selectedRow = $(this).clone().addClass('table-active');
        $('#employeeTableBody').empty().append(selectedRow);

        $('#selectLabel').hide();
    });

});

document.getElementById("ClientReviewSubmit").addEventListener("submit", function (event) {
    event.preventDefault();

    let form = this;
    let formData = new FormData(form);


    let totalRating = document.getElementById("clientTotalReview").textContent.trim();


    formData.append("ClientTotalReview", totalRating);

    $.ajax({
        url: window.routes.clientReviewSubmit,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').getAttribute(
                "content")
        },
        success: function (response) {
            console.log("Success:", response);
            alert("✅ " + (response.message || "Review submitted successfully!"));

            form.reset();
            const totalDisplay = document.getElementById("clientTotalReview");
            if (totalDisplay) {
                totalDisplay.textContent = "";
            }

            document.querySelectorAll("select[id^='cli']").forEach(select => {
                select.selectedIndex = 0;
            });

            document.querySelectorAll("textarea").forEach(textarea => {
                textarea.value = "";
            });
            location.reload();
        },
        error: function (xhr) {
            console.log("XHR Response:", xhr.responseText);

            try {
                const errorData = JSON.parse(xhr.responseText);

                // Handle Laravel validation errors
                if (xhr.status == 422) {

                    let errors = xhr.responseJSON.errors;

                    // Remove old errors
                    $(".text-danger").html("");
                    $(".is-invalid").removeClass("is-invalid");

                    let firstField = null;

                    $.each(errors, function (field, messages) {

                        let input = $("[name='" + field + "']");

                        input.addClass("is-invalid");

                        $(".error-" + field).html(messages[0]);

                        if (!firstField) {
                            firstField = input;
                        }
                    });

                    if (firstField) {
                        $("html, body").animate({
                            scrollTop: firstField.offset().top - 120
                        }, 500);

                        firstField.focus();
                    }

                    return;
                }

                if (xhr.status === 403) {
                    alert("⛔ " + (errorData.message || "This User has no Client."));
                } else if (xhr.status === 404) {
                    alert("🔍 " + (errorData.message || "Employee not found."));
                } else if (xhr.status === 409) {
                    alert("⚠️ " + (errorData.message ||
                        "You already submitted a review for this employee."));
                } else {
                    alert("❌ " + (errorData.message ||
                        "Something went wrong! Please try again."));
                }

            } catch (e) {
                alert("⚠️ Unexpected error occurred.");
                console.error("Parsing error:", e);
            }
        }
    });
});


document.addEventListener("DOMContentLoaded", function () {
    function clientTotalReview() {
        let totalRating = 0;
        document.querySelectorAll("select[id^='cli']").forEach(select => {
            const value = parseInt(select.value);
            if (!isNaN(value)) {
                totalRating += value;
            }
        });

        const totalField = document.getElementById("clientTotalReview");
        if (totalField) {
            totalField.textContent = totalRating;
        }
    }
    document.querySelectorAll("select[id^='cli']").forEach(select => {
        select.addEventListener("input", clientTotalReview);
    });
});