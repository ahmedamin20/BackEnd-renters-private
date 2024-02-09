<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            width: 80%;
            max-width: 400px;
        }

        .logo {
            margin-bottom: 20px;
        }

        .logo img {
            max-width: 100%;
            height: auto;
        }

        h1 {
            color: #333333;
            font-size: 24px;
            margin-bottom: 10px;
        }

        p {
            color: #666666;
            font-size: 16px;
            margin-bottom: 30px;
        }

        .verification-code {
            background-color: #f2f2f2;
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 18px;
            width: 100%;
            margin-bottom: 20px;
            color: #333333;
        }

        .verify-button {
            background-color: #4caf50;
            border: none;
            border-radius: 8px;
            color: white;
            cursor: pointer;
            font-size: 16px;
            padding: 12px 20px;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        .verify-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="logo">
        <img src="your_logo.png" alt="Company Logo">
    </div>
    <h1>Email Verification</h1>
    <p>Please enter the verification code sent to your email address.</p>
    <input type="text" class="verification-code" placeholder="Verification Code">
    <button class="verify-button">Verify Email</button>
</div>
</body>
</html>
