<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Instructor Application</title>
</head>

<body>
    <h5>Hello {{ $name }},</h5>

    <p>Thank you for starting your instructor application.</p>

    <p>Click the button below to continue:</p>

    <p>
        <a href="{{ $url }}" style="
            display:inline-block;
            background-color:#1e87f0;
            color:white;
            padding:10px 20px;
            text-decoration:none;
            border-radius:5px;
        ">Resume Application</a>
    </p>

    <p>If the button doesn’t work, copy and paste this link in your browser:</p>
    <p>{{ $url }}</p>

    <p>Regards,<br>BCCI Team</p>
</body>

</html>
