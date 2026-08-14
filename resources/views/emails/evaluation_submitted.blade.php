<!DOCTYPE html>
<html>
<head>
    <title>Evaluation Submitted</title>
</head>
<body>
    <p>This is to inform you that the self-evaluation for the following employee has been submitted and is now available for your review.</p>

    <h3>Employee Details</h3>
    <p>
        <strong>Employee Name:</strong> {{ $evaluationData['employee_name'] ?? 'N/A' }}<br>
        <strong>Employee ID:</strong> {{ $evaluationData['emp_id'] ?? 'N/A' }}<br>
        <strong>Designation:</strong> {{ $evaluationData['designation'] ?? 'N/A' }}<br>
        <strong>Financial Year:</strong> {{ $evaluationData['financial_year'] ?? 'N/A' }}<br>
        <strong>Submitted By:</strong> {{ $evaluationData['evalutors_name'] ?? ($evaluationData['employee_name'] ?? 'N/A') }}
    </p>

    <p>You are requested to log in to the system, review the submitted self-evaluation, and complete your review accordingly.</p>
    <p>Thank you for your timely attention to this matter.</p>

    <p>
        Regards,<br>
        Delostyle Team
    </p>
</body>
</html>
