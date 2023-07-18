<!DOCTYPE html>
<html>

<head>
    <title>Weekly Reminder - World Academy</title>
</head>

<body>
    <p>Dear {{ $data['user']['name'] }},</p>

    <p>Thanks for your enrollment for {{ $data['student_courses'][0]['course']['name'] }}..</p>

    <p>It is appreciable that you have decided to develop your professional competency despite of the busy schedule.</p>

    <p>You have last logged in to the dashboard on [date]. Congratulations! You are maintaining the schedule like
        professionals!</p>
    <p>Keep it up. SUCCESS will follow you.</p>

    <p>If you need any assistance, please contact us via WhatsApp at +8801792380380 or email us at info@worldacademy.uk.
    </p>

    <p>Happy learning ahead!</p>

    <p>Best regards,</p>
    <p>World Academy Team</p>
</body>

</html>
