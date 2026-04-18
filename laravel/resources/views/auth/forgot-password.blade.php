<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password — MedIntern</title>
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
            background: #d6eaf8;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; color: var(--brand-primary);
            margin-bottom: 20px;
        }
        h2 { font-family: 'Lora', serif; font-size: 1.45rem; color: var(--brand-primary); margin-bottom: 6px; }
        p.sub { color: #6b7c93; font-size: .85rem; margin-bottom: 28px; line-height: 1.6; }
        .form-label { font-weight: 600; font-size: .82rem; color: #4a5568; }
        .input-group-text {
            background: #f8fafc; border: 1.5px solid #dde3ea; border-right: none;
            color: #6b7c93; border-radius: 9px 0 0 9px;
        }
        .form-control {
            border-radius: 0 9px 9px 0; border: 1.5px solid #dde3ea; border-left: none;
            padding: 10px 14px; font-size: .9rem;
            transition: border-color .15s, box-shadow .15s;
        }
        .form-control:focus {
            border-color: var(--brand-accent);
            box-shadow: 0 0 0 3px rgba(46,134,193,.15);
            border-left: none;
        }
        .btn-primary {
            background: var(--brand-primary); border-color: var(--brand-primary);
            border-radius: 9px; padding: 11px; font-weight: 700; font-size: .95rem; width: 100%;
            transition: background .2s;
        }
        .btn-primary:hover { background: var(--brand-accent); border-color: var(--brand-accent); }
        .back-link { text-align: center; margin-top: 20px; }
        .back-link a { color: var(--brand-accent); font-size: .85rem; text-decoration: none; font-weight: 600; }
        .back-link a:hover { text-decoration: underline; }
        .alert { border-radius: 10px; border: none; font-size: .875rem; }
    </style>
</head>
<body>
<div class="card">
    <div class="icon-wrap"><i class="bi bi-key"></i></div>
    <h2>Forgot Password?</h2>
    <p class="sub">Enter your email address and we'll send you a link to reset your password.</p>

    @if(session('status'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-check-circle-fill flex-shrink-0"></i>
            <div>{{ session('status') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
            <div>{{ $errors->first() }}</div>
        </div>
    @endif

    <form action="{{ route('password.email') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="form-label">Email Address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email') }}"
                       placeholder="your@email.com" required autofocus>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-send me-2"></i>Send Reset Link
        </button>
    </form>

    <div class="back-link">
        <a href="{{ route('login') }}"><i class="bi bi-arrow-left me-1"></i>Back to Sign In</a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
