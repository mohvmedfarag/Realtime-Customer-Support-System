<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: sans-serif;
        }

        .register-card {
            width: 100%;
            max-width: 500px;
            background: white;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .register-title {
            font-weight: 700;
            color: #111827;
        }

        .register-subtitle {
            color: #6b7280;
        }

        .form-control {
            height: 50px;
            border-radius: 12px;
        }

        .input-group-text {
            border-radius: 12px 0 0 12px;
            background: #f3f4f6;
        }

        .btn-register {
            height: 50px;
            border-radius: 12px;
            font-weight: 600;
            background: #4f46e5;
            border: none;
            transition: .3s;
        }

        .btn-register:hover {
            background: #4338ca;
        }
    </style>
</head>

<body>

    <div class="register-card">

        <!-- Header -->
        <div class="text-center mb-4">
            <h2 class="register-title">
                Create Account
            </h2>

            <p class="register-subtitle">
                Register to continue
            </p>
        </div>

        <!-- Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">

                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Full Name
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        <i class="bi bi-person-fill"></i>
                    </span>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-control"
                        placeholder="Enter your name">

                </div>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Email Address
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        <i class="bi bi-envelope-fill"></i>
                    </span>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control"
                        placeholder="Enter your email">

                </div>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Password
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        <i class="bi bi-lock-fill"></i>
                    </span>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Create password">

                </div>
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    Confirm Password
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        <i class="bi bi-shield-lock-fill"></i>
                    </span>

                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Confirm password">

                </div>
            </div>

            <!-- Button -->
            <button type="submit" class="btn btn-primary w-100 btn-register">
                <i class="bi bi-person-plus-fill me-1"></i>
                Create Account
            </button>

            <!-- Login -->
            <div class="text-center mt-4">
                <small class="text-muted">
                    Already have an account?

                    <a href="{{ route('showLoginForm') }}" class="text-decoration-none fw-semibold">
                        Login
                    </a>
                </small>
            </div>

        </form>

    </div>

</body>

</html>
