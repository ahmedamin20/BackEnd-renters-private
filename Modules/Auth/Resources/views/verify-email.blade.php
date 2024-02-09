<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Reset some default styles */
        body, h1, p {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            margin-bottom: 30px;
        }

        .verification-code {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            border-radius: 5px;
            font-size: 18px;
        }

        .note {
            font-size: 14px;
            color: #888888;
            margin-top: 20px;
        }
    </style>
    <title>Verify Email</title>
</head>
<body>
<div class="container">
    <h1>Email Verification</h1>
    <p>Thank you for signing up! To verify your email address, please use the following verification code:</p>
    <div class="verification-code">{{$data['code']}}</div>
    <p class="note">Note: This code will expire after {{$data['expire_after']}} hours</p>
</div>
</body>
</html>
