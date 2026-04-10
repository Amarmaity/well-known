<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo e(asset('css/modestcustom.css')); ?>?v=14" rel="stylesheet">
    <link href="<?php echo e(asset('css/onbord.css')); ?>?v=14" rel="stylesheet">
</head>

<body class="login-page">
    <section class="login__page--wrapper">
        <div class="container">
            <div class="login__page--container">
                <div class="login__page--logo text-center mb-5">
                    <img src="<?php echo e(asset('images/evalon-black.png')); ?>" alt="Delostyle Logo" class="mw-100">
                </div>
                <h1>Sign in to your Evalon Panel</h1>
                <?php if(session('success')): ?>
                    <div id="alert-message" class="alert alert-success text-center">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div id="alert-message" class="alert alert-danger text-center">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>
                <form action="<?php echo e(route('log-in')); ?>" method="post" autocomplete="off" id="login-form" class="login__page--form">
                    <?php echo csrf_field(); ?>

                    <!-- Email Input -->
                    <div class="form-group">
                        <label for="email">Enter Email:</label>
                        <div class="input-group">
                            <input type="email" class="form-control" name="email" id="email" placeholder="Email"
                                required>
                            <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                        </div>
                    </div>

                    <!-- User Type Dropdown -->

                    <div class="form-group">
                        <label for="user_type_dropdown" class="form-label">User Type:</label>
                        <select class="form-control" id="user_type_dropdown" name="user_type" required>
                            <option value="" selected disabled>Select User Type</option>

                            <option value="Super User">Super User</option>
                            <?php if($superUser == null): ?>
                                <option value="admin">Admin</option>
                                <option value="hr">HR</option>
                                <option value="users">Users</option>
                                <option value="manager">Manager</option>
                                <option value="client">Client</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Password Input (Initially Shown) -->
                    <div id="password-field" class="form-group">
                        <label for="password">Enter Password:</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="Enter Password" required>
                            <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                        </div>
                    </div>

                    <!-- OTP Input (Initially Hidden) -->
                    <div id="otp-field" class="form-group" style="display: none">
                        <label for="otp">Enter OTP:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="otp" id="otp"
                                placeholder="Enter OTP">
                            <div class="input-group-text"><span class="bi bi-key"></span></div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-grid gap-2">
                        <button type="button" class="btn__theme" id="send-otp-btn">Send OTP</button>
                        <button type="submit" class="btn__theme" id="login-btn" style="display: none;">Verify
                            OTP</button>
                        <button type="button" class="btn__theme resend__otp" id="resend-otp-btn"
                            style="display: none;">Resend
                            OTP</button>
                    </div>

                    <div class="text-center mt-2">
                        <a href="<?php echo e(route('forgot-password')); ?>" class="forget__pass">Forgot Password?</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>

</html>

<script>
    $(document).ready(function () {
        $("#send-otp-btn").click(function () {
            var email = $("#email").val();
            var userType = $("#user_type_dropdown").val();
            var password = $("#password").val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            if (email && userType && password) {
                $.ajax({
                    url: "<?php echo e(route('log-in')); ?>",
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

                        if (response.status === 'success' || response.status === 'new_user') {
                            $("#password-field").hide();
                            $("#otp-field").show();
                            $("#send-otp-btn").hide();
                            $("#login-btn").text("Verify OTP").removeClass("btn-primary").addClass("btn-success").show();
                        }
                    },
                    error: function (xhr) {
                        alert("Failed to send OTP: " + (xhr.responseJSON?.message || xhr.responseText || "Server Error"));
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
                url: "<?php echo e(route('verify-otp-login-users')); ?>",
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
                    alert("OTP verification failed: " + (xhr.responseJSON?.message || xhr.responseText || "Server Error"));
                }
            });
        });
    });
</script>
<?php /**PATH /opt/lampp/htdocs/well-known/resources/views/loginusers/userlogin.blade.php ENDPATH**/ ?>