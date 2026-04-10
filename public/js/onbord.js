
function showBtnLoader(btn) {
    const $btn = $(btn);
    $btn.addClass('btn-loading');

    if ($btn.is("#send-otp-btn")) {
        $btn.text("Sending OTP...");
    } else if ($btn.is("#login-btn")) {
        $btn.text("Verifying...");
    }
}

function hideBtnLoader(btn) {
    const $btn = $(btn);
    $btn.removeClass('btn-loading');

    if ($btn.is("#send-otp-btn")) {
        $btn.text("Send OTP");
    } else if ($btn.is("#login-btn")) {
        $btn.text("Verify OTP");
    }
}

$(document).ready(function () {
    const otpCooldownSeconds = 30;
    let resendTimeout;
    let isOtpSending = false;

    function startCooldown() {
        let secondsLeft = otpCooldownSeconds;
        const $resendBtn = $("#resend-otp-btn");

        $resendBtn.text(`Resend OTP in ${secondsLeft}s`).show().prop("disabled", true);

        resendTimeout = setInterval(() => {
            secondsLeft--;
            if (secondsLeft > 0) {
                $resendBtn.text(`Resend OTP in ${secondsLeft}s`);
            } else {
                clearInterval(resendTimeout);
                $resendBtn.text("Resend OTP").prop("disabled", false);
                isOtpSending = false;
            }
        }, 1000);
    }

    function sendOtp(isResend = false) {
        if (isOtpSending) return;

        const email = $("#email").val();
        const userType = $("#user_type_dropdown").val();
        const password = $("#password").val();
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        isOtpSending = true;
        $("#send-otp-btn, #resend-otp-btn").prop("disabled", true);

        $.ajax({
            url: "{{ route('log-in') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                email,
                user_type: userType,
                password
            },
            success: function (response) {
                hideBtnLoader("#send-otp-btn");
                alert(response.message);

                if (response.status === 'success') {
                    $("#password-field").hide();
                    $("#otp-field").show();
                    $("#send-otp-btn").hide();
                    $("#login-btn").show().text("Verify OTP").removeClass("btn-primary")
                        .addClass("btn-success");
                    $("#resend-otp-btn").hide();
                    startCooldown();
                } else {
                    isOtpSending = false;
                    $("#send-otp-btn, #resend-otp-btn").prop("disabled", false);
                }
            },
            error: function (xhr) {
                hideBtnLoader("#send-otp-btn");
                alert("Failed to send OTP: " + (xhr.responseJSON?.message || "Server Error"));
                isOtpSending = false;
                $("#send-otp-btn, #resend-otp-btn").prop("disabled", false);
            }
        });
    }

    $("#send-otp-btn").click(function () {
        const email = $("#email").val();
        const userType = $("#user_type_dropdown").val();
        const password = $("#password").val();

        if (!email || !userType || (!password && $("#password-field").is(":visible"))) {
            alert("Please enter email, user type, and password before requesting OTP.");
            return;
        }

        showBtnLoader(this);
        sendOtp(false);
    });

    $("#resend-otp-btn").click(function () {
        showBtnLoader(this);
        sendOtp(true);
    });

    $("#login-form").submit(function (e) {
        e.preventDefault();
        const email = $("#email").val();
        const otp = $("#otp").val();
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        if (otp) {
            showBtnLoader("#login-btn");

            $.ajax({
                url: "{{ route('verify-otp-login-users') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    email,
                    otp
                },
                success: function (response) {
                    hideBtnLoader("#login-btn");
                    alert(response.message);
                    if (response.status === 'success') {
                        clearTimeout(resendTimeout);
                        window.location.href = response.redirect;
                    }
                },
                error: function (xhr) {
                    hideBtnLoader("#login-btn");
                    alert("OTP verification failed: " + (xhr.responseJSON?.message ||
                        "Server Error"));
                }
            });
        } else {
            alert("Please enter the OTP.");
        }
    });
});



// Auto-refresh page after 5 seconds if alert is present
window.addEventListener('DOMContentLoaded', function () {
    const alertBox = document.getElementById('alert-message');
    if (alertBox) {
        setTimeout(() => {
            location.reload();
        }, 1000);
    }
});






$(document).ready(function () {
    $("#send-otp-btn").click(function () {
        var email = $("#email").val();
        var userType = $("#user_type_dropdown").val();
        var password = $("#password").val();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        if (email && userType && password) {
            $.ajax({
                url: "{{ route('log-in') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    email: email,
                    user_type: userType,
                    password: password
                },
                success: function (response) {
                    alert(response.message);

                    if (response.status === 'success' || response.status ===
                        'new_user') {
                        $("#password-field").hide();
                        $("#otp-field").show();
                        $("#send-otp-btn").hide();
                        $("#login-btn").text("Verify OTP").removeClass("btn-primary")
                            .addClass("btn-success").show();
                    }
                },
                error: function (xhr) {
                    alert("Failed to send OTP: " + (xhr.responseJSON?.message || xhr
                        .responseText || "Server Error"));
                }
            });
        } else {
            alert("Please enter Email, User Type, and Password before requesting OTP.");
        }
    });

    $("#login-form").submit(function (e) {
        e.preventDefault();

        var email = $("#email").val();
        var otp = $("#otp").val();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        if (!otp) {
            alert("Please enter the OTP.");
            return;
        }

        $.ajax({
            url: "{{ route('verify-otp-login-users') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                email: email,
                otp: otp
            },
            success: function (response) {
                alert(response.message);
                if (response.status === 'success') {
                    window.location.href = response.redirect;
                }
            },
            error: function (xhr) {
                alert("OTP verification failed: " + (xhr.responseJSON?.message || xhr
                    .responseText || "Server Error"));
            }
        });
    });
});

