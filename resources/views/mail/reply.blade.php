<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['mail_title'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: rgba(209, 46, 46, 1);
            color: #ffffff;
            text-align: center;
            padding: 20px;
            font-size: 22px;
            font-weight: bold;
        }

        .content {
            padding: 20px;
            text-align: center;
        }

        .content p {
            font-size: 16px;
            color: #333333;
            margin: 10px 0;
        }

        .reply-box {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            text-align: left;
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin-top: 20px;
            background: rgba(209, 46, 46, 1);
            color: #ffffff;
            text-decoration: none;
            font-size: 18px;
            border-radius: 5px;
        }

        .footer {
            text-align: center;
            padding: 15px;
            font-size: 14px;
            color: #777777;
            background: #eeeeee;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            {{ $data['mail_title'] }}
        </div>

        <div class="content">
            <p>Hello {{ $data['messenger_name'] }}, 😊</p>
            <p>Thank you for reaching out to <strong>{{ $data['restaurant_name'] }}</strong>! 🤗📩</p>
            <p>We appreciate your message and here’s our response:</p>

            <div class="reply-box">
                <p>{{ $data['content'] }}</p>
            </div>

            <p>If you need further assistance, feel free to call us at <strong>{{ $data['restaurant_phone']
                    }}</strong>.📞</p>
            <p>Or email us at <strong>{{ $data['restaurant_email'] }}</strong>.📧</p>
            <p>Meanwhile, check out our delicious menu and special offers!👇</p>
            <a href="{{ $data['menu_url'] }}" class="btn">View Our Menu</a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ $data['restaurant_name'] }}. All rights reserved.
        </div>
    </div>
</body>

</html>