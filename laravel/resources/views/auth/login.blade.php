<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — MedIntern</title>
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
        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 44px 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 60px rgba(0,0,0,.35);
        }
        .login-logo {
            width: 56px; height: 56px;
            background: var(--brand-primary);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; color: #fff;
            margin-bottom: 20px;
        }
        .login-card h2 {
            font-family: 'Lora', serif;
            font-size: 1.5rem;
            color: var(--brand-primary);
            margin-bottom: 4px;
        }
        .login-card p.sub { color: #6b7c93; font-size: .85rem; margin-bottom: 28px; }
        .form-label { font-weight: 600; font-size: .82rem; color: #4a5568; }
        .form-control {
            border-radius: 9px;
            border: 1.5px solid #dde3ea;
            padding: 10px 14px;
            font-size: .9rem;
            transition: border-color .15s, box-shadow .15s;
        }
        .form-control:focus {
            border-color: var(--brand-accent);
            box-shadow: 0 0 0 3px rgba(46,134,193,.15);
        }
        .btn-login {
            background: var(--brand-primary);
            color: #fff;
            border: none;
            border-radius: 9px;
            padding: 11px;
            font-weight: 700;
            font-size: .95rem;
            width: 100%;
            transition: background .2s;
        }
        .btn-login:hover { background: var(--brand-accent); color: #fff; }
        .login-footer { text-align: center; color: #a0aab4; font-size: .78rem; margin-top: 28px; }
        .input-group-text {
            background: #f8fafc; border: 1.5px solid #dde3ea; border-right: none;
            color: #6b7c93; border-radius: 9px 0 0 9px;
        }
        .input-group .form-control { border-left: none; border-radius: 0 9px 9px 0; }
        .input-group .form-control:focus { border-left: none; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-logo"><i class="bi bi-hospital"></i></div>
    <h2>Welcome Back</h2>
    <p class="sub">Sign in to the Medical Internship Portal</p>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3" style="border-radius:9px;font-size:.85rem;">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember" style="font-size:.83rem;">Remember me</label>
            </div>
            <a href="{{ route('password.request') }}" style="font-size:.83rem;color:#2e86c1;text-decoration:none;font-weight:600;">
                Forgot password?
            </a>
        </div>

        <button type="submit" class="btn-login">
            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
        </button>
    </form>

    <div class="login-footer">
        &copy; {{ date('Y') }} MedIntern &mdash; Medical Internship Management System<br>
        <span class="text-muted">Accounts are created by your administrator</span>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
