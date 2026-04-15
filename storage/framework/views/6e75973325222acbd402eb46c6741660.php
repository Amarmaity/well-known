<!DOCTYPE html>
<html>
<head>
    <title>Evaluation Credentials</title>
</head>
<body>
    <h2>Hello <?php echo e($user->fname); ?> <?php echo e($user->lname); ?></h2>

    <p>You have been granted access for <?php echo e(config('app.name')); ?>.</p>

    <p><strong>Your evaluation login credentials are as follows:</strong></p>
    <ul>
        <li><strong>Email:</strong> <?php echo e($user->email); ?></li>
        <li><strong>Password:</strong> <?php echo e($password); ?></li>
    </ul>

    <p>You can log in using the link below:</p>
    <p><a href="<?php echo e($loginUrl); ?>"><?php echo e($loginUrl); ?></a></p>

    

    <br>
    <p>Regards,<br>
    
    Delostyle Team</p>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\well-known\resources\views/emails/evaluation_credentials.blade.php ENDPATH**/ ?>