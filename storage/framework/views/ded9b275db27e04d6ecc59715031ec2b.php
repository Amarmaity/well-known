<!DOCTYPE html>
<html>
<head>
    <title>Evaluation Submitted</title>
</head>
<body>
    <h2>New Evaluation Submitted</h2>
    <p><strong>Employee Name:</strong> <?php echo e($evaluationData['employee_name']); ?></p>
    <p><strong>Employee ID:</strong> <?php echo e($evaluationData['emp_id']); ?></p>
    
    <p><strong>Designation:</strong> <?php echo e($evaluationData['designation']); ?></p>
    <p><strong>Submitted By:</strong> <?php echo e($evaluationData['evalutors_name']); ?></p>

    
    <p>Please fill your review for the employee.</p>
</body>
</html>
<?php /**PATH /var/www/vhosts/relaxed-tereshkova.74-208-156-247.plesk.page/modest-gagarin.74-208-156-247.plesk.page/resources/views/emails/evaluation_submitted.blade.php ENDPATH**/ ?>