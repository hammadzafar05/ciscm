<!DOCTYPE html>
<html>
<head>
    <title>World Academy Enrollment</title>
</head>
<body>
    <p>Dear {{ $data['userName'] }}, The exam "{{ $data['examName'] }}" for "{{ $data['courseName'] }}" has been scheduled as below:</p>
    
    <ul>
        <li>Date: {{ $data['openingDate'] }}</li>
        <li>Time: {{ $data['time'] }}</li>
        <li>Duration: {{ $data['duration'] }}</li>
        <li>Passmarks: {{ $data['totalMarks'] }}</li>
        <li>No Of Questions: {{ $data['noOfQuestions'] }}</li>
        <li>Type Of Questions: {{ $data['typeOfQuestions'] }}</li>
    </ul>

    <p>For any further query, please contact us via whatsapp +8801792380380</p>


</body>
</html>
