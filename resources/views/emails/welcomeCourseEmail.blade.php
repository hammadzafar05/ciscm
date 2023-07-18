 <!DOCTYPE html>
<html>
<head>
    <title>Enrollment Confirmation</title>
</head>
<body>
    <p>Dear {{ $data->users.name }},</p>
    <p>Greetings from World Academy</p>
   <p>Thanks for your enrollment for {{ $data->courses.name }}..</p>
    <p>Please note the below information regarding the course management:</p>
    <ul>
        <li>You will get all materials in the learning management portal</li>
        <li>Please find the attachment/click <a href="#">here</a> to get the operational guideline for the learning management system</li>
        <li>You will receive notifications through the WhatsApp group/email</li>
    </ul>
    <p>For any immediate assistance, please contact +8801792380380 (WhatsApp is available).</p>
    <hr>
    <p>
        <strong>Footer Note:</strong> World Academy is one of the pioneer Professional Education & Certification institutes,
        operating in 57 countries, and the number is increasing day by day. More than 15,000 professionals have graduated
        from this Institute.
    </p>
</body>
</html>