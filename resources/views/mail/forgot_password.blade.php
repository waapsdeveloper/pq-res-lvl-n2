<!DOCTYPE html>
<html>
<head>
    <title>Password Reset OTP</title>
</head>
<body>
    <p>Hello,</p>
    <p>Your OTP for password reset is: <strong>{{ $otp ?? 'No OTP Provided' }}</strong></p>
    <p>This OTP will expire in 10 minutes.</p>
    <p>Thank you.</p>
</body>
</html>
