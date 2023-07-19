<!DOCTYPE html>
<html>
<head>
    <title>World Academy Enrollment</title>
</head>
<body>
    {{-- <p>Dear {{ $data->users.name }},</p> --}}
    <p>Dear {{ $data['user']['name'] }},</p>

    <p>Greetings from World Academy!</p>

    {{-- <p>Thanks for your enrollment for {{ $data->courses.name }}. Please note the below information regarding the course management:</p> --}}
    <p>Thanks for your enrollment for {{ $data['student_courses'][0]['course']['name'] }}. Please note the below information regarding the course management:</p>

    <ul>
        <li>You will get all materials in the learning management portal.</li>
        <li>Please find the <a href="#">attachment</a> or click <a href="#">here</a> to get the operational guideline for the learning management system.</li>
        <li>You will receive notifications through the WhatsApp group/email.</li>
        <li>For any immediate assistance, please contact <a href="tel:+8801792380380">+8801792380380</a> (WhatsApp is available).</li>
    </ul>

    <p>Footer Note: World Academy is one of the pioneer Professional Education & Certification institutes, operating in 57 countries and constantly expanding. We have proudly graduated over 15,000 professionals from our institute.</p>

    <p>Best regards,</p>
    <p>World Academy for Research & Development [WARD]</p>
</body>
</html>
