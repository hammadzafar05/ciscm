<!DOCTYPE html>
<html>
<head>
    <title>World Academy Enrollment</title>
</head>
<body>
    {{-- <p>Dear {{ $data->users.name }},</p> --}}
    <p>Dear {{ $data['user']['name'] }},</p>

    <p>Thanks for your enrollment for {{ $data['student_courses'][0]['course']['name'] }}.</p>

    <p>It is appreciable that you have decided to develop your professional competency despite of the busy schedule. You have last logged in to the dashboard on {{ $lastLoginDate }}. So for the last {{ $numberOfDays }} you could not able to log in to the system.</p>

    <p>If you need any assistance please contact us via whatsapp +8801792380380 or email us <a href="mailto:info@worldacademy.uk">info@worldacademy.uk</a></p>

    <p>Happy learning ahead!</p>

    <p>Best regards,</p>
    <p>World Academy for Research & Development [WARD]</p>
</body>
</html>
