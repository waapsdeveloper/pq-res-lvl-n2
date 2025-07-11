<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $restaurantName ?? config('app.name') }} - Password Reset</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background: #fff;
            color: #222;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 480px;
            margin: 40px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 12px #d32f2f22;
            padding: 32px 24px 24px 24px;
        }
        .logo-container {
            text-align: center;
            margin-bottom: 18px;
        }
        .logo {
            max-width: 140px;
            height: auto;
            margin-bottom: 8px;
        }
        .debug-logo-url {
            color: #d32f2f;
            font-size: 0.8rem;
            word-break: break-all;
            margin-bottom: 8px;
        }
        .title {
            font-size: 1.7rem;
            font-weight: 600;
            color: #d32f2f;
            text-align: center;
            margin-bottom: 8px;
        }
        .subtitle {
            text-align: center;
            color: #b71c1c;
            font-weight: 500;
            margin-bottom: 24px;
            font-size: 1.05rem;
        }
        .content {
            line-height: 1.6;
            color: #333;
            font-size: 1rem;
            margin-bottom: 18px;
        }
        .btn-container {
            text-align: center;
            margin: 28px 0 18px 0;
        }
        .btn {
            display: inline-block;
            background: #d32f2f;
            color: #fff;
            padding: 14px 38px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.08rem;
            letter-spacing: 0.5px;
            transition: background 0.2s;
            border: none;
        }
        .btn:hover {
            background: #b71c1c;
        }
        .reset-link {
            background: #f8d7da;
            border-radius: 8px;
            padding: 12px;
            font-family: monospace;
            font-size: 0.95rem;
            color: #b71c1c;
            word-break: break-all;
            border: 1px solid #f5c6cb;
            margin-top: 10px;
        }
        .footer {
            text-align: center;
            color: #888;
            font-size: 0.9rem;
            margin-top: 32px;
        }
        @media (max-width: 600px) {
            .container { padding: 18px 4vw; }
            .title { font-size: 1.2rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img src="cid:{{ $restaurantLogoCid }}" alt="{{ $restaurantName ?? config('app.name') }} Logo" class="logo">
        </div>
        <div class="title">{{ $restaurantName ?? config('app.name') }}</div>
        <div class="subtitle">Password Reset Request</div>
        <div class="content">
            Hello,<br>
            We received a request to reset your password for your {{ $restaurantName ?? config('app.name') }} account.<br>
            Click the button below to reset your password:
        </div>
        <div class="btn-container">
            <a href="{{ $resetUrl ?? '#' }}" class="btn">Reset Password</a>
        </div>
        <div class="content">
            If the button above doesn't work, copy and paste this link into your browser:
            <div class="reset-link">{{ $resetUrl ?? '' }}</div>
        </div>
        <div class="content" style="font-size:0.97rem; color:#b71c1c;">
            This link will expire in 60 minutes. If you did not request a password reset, you can safely ignore this email.
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ $restaurantName ?? config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>