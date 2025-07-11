<!DOCTYPE html>
<html>
<head>
    <title>{{ $restaurantName ?? config('app.name') }} - Reset Password</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600&display=swap" rel="stylesheet">
    <style>
        body {
            background: #fff;
            font-family: 'Montserrat', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .auth-page {
            max-width: 420px;
            margin: 40px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 12px #d32f2f22;
            padding: 32px 24px 24px 24px;
        }
        .logo {
            display: block;
            margin: 0 auto 18px auto;
            max-width: 140px;
            height: auto;
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
        .form-group {
            margin-bottom: 18px;
            position: relative;
        }
        .form-control {
            width: 100%;
            padding: 12px 38px 12px 12px;
            border-radius: 6px;
            border: 1px solid #d32f2f;
            font-size: 1rem;
            background: #fff;
            color: #222;
            box-sizing: border-box;
        }
        .form-control:focus {
            outline: none;
            border-color: #b71c1c;
        }
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #d32f2f;
            font-size: 1.1rem;
            cursor: pointer;
            z-index: 2;
        }
        .btn {
            width: 100%;
            background: #d32f2f;
            color: #fff;
            padding: 14px;
            border: none;
            border-radius: 30px;
            font-size: 1.08rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-top: 8px;
            transition: background 0.2s;
        }
        .btn:disabled {
            background: #e57373;
            color: #fff;
            cursor: not-allowed;
        }
        .text-danger {
            color: #d32f2f;
            font-size: 0.97rem;
            margin-top: 4px;
        }
        .footer {
            text-align: center;
            color: #888;
            font-size: 0.9rem;
            margin-top: 32px;
        }
        .toast {
            display: none;
            position: fixed;
            top: 24px;
            left: 50%;
            transform: translateX(-50%);
            min-width: 220px;
            background: #d32f2f;
            color: #fff;
            padding: 14px 28px;
            border-radius: 6px;
            font-size: 1rem;
            z-index: 9999;
            box-shadow: 0 2px 8px #d32f2f33;
            text-align: center;
        }
        .toast.success {
            background: #388e3c;
        }
        @media (max-width: 600px) {
            .auth-page { padding: 18px 4vw; }
            .title { font-size: 1.2rem; }
        }
    </style>
</head>
<body>
    <div class="toast" id="toast"></div>
    <div class="auth-page">
        <img src="{{ $restaurantLogo }}" alt="{{ $restaurantName }} Logo" class="logo">
        <div class="title">Reset Password</div>
        <div class="subtitle">Enter your new password below</div>
        <form method="POST" action="{{ route('password.update') }}" id="resetForm" autocomplete="off">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group">
                <input id="email" type="email" class="form-control"
                       name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" placeholder="Email">
            </div>
            <div class="form-group">
                <input id="password" type="password" class="form-control"
                       name="password" required autocomplete="new-password" placeholder="New Password">
                <button type="button" class="toggle-password" onclick="togglePassword('password', this)">&#128065;</button>
            </div>
            <div class="form-group">
                <input id="password-confirm" type="password" class="form-control"
                       name="password_confirmation" required autocomplete="new-password" placeholder="Confirm New Password">
                <button type="button" class="toggle-password" onclick="togglePassword('password-confirm', this)">&#128065;</button>
                <div class="text-danger" id="matchError" style="display:none;">Passwords do not match.</div>
            </div>
            <button type="submit" class="btn" id="resetBtn" disabled>Reset Password</button>
        </form>
        <div class="footer">
            &copy; {{ date('Y') }} {{ $restaurantName ?? config('app.name') }}. All rights reserved.
        </div>
    </div>
    <script>
        // Show/hide password
        function togglePassword(fieldId, btn) {
            var field = document.getElementById(fieldId);
            if (field.type === 'password') {
                field.type = 'text';
                btn.innerHTML = '&#128064;';
            } else {
                field.type = 'password';
                btn.innerHTML = '&#128065;';
            }
        }
        // Password match validation
        var password = document.getElementById('password');
        var confirm = document.getElementById('password-confirm');
        var matchError = document.getElementById('matchError');
        var resetBtn = document.getElementById('resetBtn');
        function validatePasswords() {
            if (password.value && confirm.value && password.value !== confirm.value) {
                matchError.style.display = 'block';
                resetBtn.disabled = true;
            } else if (password.value && confirm.value && password.value === confirm.value) {
                matchError.style.display = 'none';
                resetBtn.disabled = false;
            } else {
                matchError.style.display = 'none';
                resetBtn.disabled = true;
            }
        }
        password.addEventListener('input', validatePasswords);
        confirm.addEventListener('input', validatePasswords);
        // Toast logic
        function showToast(msg, success) {
            var toast = document.getElementById('toast');
            toast.innerText = msg;
            toast.className = 'toast' + (success ? ' success' : '');
            toast.style.display = 'block';
            setTimeout(function() { toast.style.display = 'none'; }, 4000);
        }
        // Handle form submit
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var form = this;
            var data = new FormData(form); // Collect data BEFORE disabling fields
            resetBtn.disabled = true;
            password.disabled = true;
            confirm.disabled = true;
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': data.get('_token')
                },
                body: data
            })
            .then(response => response.json())
            .then(res => {
                if (res.message && res.status) {
                    showToast(res.message, true);
                } else if (res.message) {
                    showToast(res.message, false);
                    resetBtn.disabled = false;
                    password.disabled = false;
                    confirm.disabled = false;
                } else {
                    showToast('An error occurred.', false);
                    resetBtn.disabled = false;
                    password.disabled = false;
                    confirm.disabled = false;
                }
            })
            .catch(() => {
                showToast('An error occurred.', false);
                resetBtn.disabled = false;
                password.disabled = false;
                confirm.disabled = false;
            });
        });
        // Initial validation
        validatePasswords();
    </script>
</body>
</html>
