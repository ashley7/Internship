<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — MedIntern</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Lora:ital@0;1&display=swap" rel="stylesheet">
    <style>
        :root { --brand-primary: #1a5276; --brand-accent: #2e86c1; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0d2137 0%, #1a5276 50%, #154360 100%);
            display: flex; align-items: center; justify-content: center;
            padding: 20px;
        }
        .card {
            background: #fff;
            border-radius: 20px;
            padding: 44px 40px;
            width: 100%;
            max-width: 430px;
            box-shadow: 0 25px 60px rgba(0,0,0,.35);
            border: none;
        }
        .icon-wrap {
            width: 56px; height: 56px;
            background: #d5f5e3;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; color: #1e8449;
            margin-bottom: 20px;
        }
        h2 { font-family: 'Lora', serif; font-size: 1.45rem; color: var(--brand-primary); margin-bottom: 6px; }
        p.sub { color: #6b7c93; font-size: .85rem; margin-bottom: 28px; line-height: 1.6; }
        .form-label { font-weight: 600; font-size: .82rem; color: #4a5568; }
        .input-group-text {
            background: #f8fafc; border: 1.5px solid #dde3ea; border-right: none;
            color: #6b7c93; border-radius: 9px 0 0 9px; cursor: pointer;
        }
        .form-control {
            border: 1.5px solid #dde3ea; border-left: none;
            padding: 10px 14px; font-size: .9rem;
            transition: border-color .15s, box-shadow .15s;
        }
        .form-control:focus {
            border-color: var(--brand-accent);
            box-shadow: 0 0 0 3px rgba(46,134,193,.15);
            border-left: none;
        }
        .toggle-end {
            border-radius: 0 9px 9px 0 !important;
            border-left: none;
            border: 1.5px solid #dde3ea;
            background: #f8fafc;
            cursor: pointer;
        }
        .toggle-end:focus { outline: none; box-shadow: none; }
        .btn-primary {
            background: var(--brand-primary); border-color: var(--brand-primary);
            border-radius: 9px; padding: 11px; font-weight: 700; font-size: .95rem; width: 100%;
            transition: background .2s;
        }
        .btn-primary:hover { background: var(--brand-accent); border-color: var(--brand-accent); }
        .password-strength { height: 4px; border-radius: 2px; margin-top: 8px; background: #e8edf3; overflow: hidden; }
        .password-strength-bar { height: 100%; border-radius: 2px; transition: width .3s, background .3s; width: 0; }
        .strength-label { font-size: .72rem; color: #6b7c93; margin-top: 4px; }
        .back-link { text-align: center; margin-top: 20px; }
        .back-link a { color: var(--brand-accent); font-size: .85rem; text-decoration: none; font-weight: 600; }
        .alert { border-radius: 10px; border: none; font-size: .875rem; }
    </style>
</head>
<body>
<div class="card">
    <div class="icon-wrap"><i class="bi bi-shield-lock"></i></div>
    <h2>Set New Password</h2>
    <p class="sub">Choose a strong password for <strong>{{ $email }}</strong>.</p>

    @if ($errors->any())
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
            <div>{{ $errors->first() }}</div>
        </div>
    @endif

    <form action="{{ route('password.reset') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="mb-3">
            <label class="form-label">New Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" id="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Min. 8 characters" required autofocus>
                <button type="button" class="toggle-end btn" onclick="togglePassword('password', this)">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            <div class="password-strength"><div class="password-strength-bar" id="strength-bar"></div></div>
            <div class="strength-label" id="strength-label"></div>
        </div>

        <div class="mb-4">
            <label class="form-label">Confirm New Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="form-control" placeholder="Repeat new password" required>
                <button type="button" class="toggle-end btn" onclick="togglePassword('password_confirmation', this)">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            <div id="match-msg" class="strength-label mt-1"></div>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check2-circle me-2"></i>Reset Password
        </button>
    </form>

    <div class="back-link">
        <a href="{{ route('login') }}"><i class="bi bi-arrow-left me-1"></i>Back to Sign In</a>
    </div>
</div>

<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

const pwInput    = document.getElementById('password');
const confirmPw  = document.getElementById('password_confirmation');
const bar        = document.getElementById('strength-bar');
const label      = document.getElementById('strength-label');
const matchMsg   = document.getElementById('match-msg');

pwInput.addEventListener('input', function () {
    const val = this.value;
    let score = 0;
    if (val.length >= 8)          score++;
    if (/[A-Z]/.test(val))        score++;
    if (/[0-9]/.test(val))        score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const colors = ['#e74c3c', '#e67e22', '#f1c40f', '#27ae60'];
    const labels = ['Weak', 'Fair', 'Good', 'Strong'];

    bar.style.width    = (score * 25) + '%';
    bar.style.background = colors[score - 1] || '#e8edf3';
    label.textContent  = val.length ? labels[score - 1] || '' : '';
    label.style.color  = colors[score - 1] || '#6b7c93';
});

confirmPw.addEventListener('input', function () {
    if (!this.value) { matchMsg.textContent = ''; return; }
    if (this.value === pwInput.value) {
        matchMsg.textContent = '✓ Passwords match';
        matchMsg.style.color = '#27ae60';
    } else {
        matchMsg.textContent = '✗ Passwords do not match';
        matchMsg.style.color = '#e74c3c';
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
