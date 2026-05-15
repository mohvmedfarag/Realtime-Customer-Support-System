<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: sans-serif;
        }

        .admin-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        }

        .admin-header {
            background: #0f172a;
            color: white;
            padding: 35px;
            text-align: center;
        }

        .admin-header i {
            font-size: 55px;
        }

        .admin-body {
            padding: 35px;
        }

        .form-control {
            height: 50px;
            border-radius: 12px;
        }

        .input-group-text {
            border-radius: 12px 0 0 12px;
            background: #f1f5f9;
        }

        .btn-admin {
            height: 50px;
            border-radius: 12px;
            background: #0f172a;
            border: none;
            font-weight: 600;
            transition: .3s;
        }

        .btn-admin:hover {
            background: #1e293b;
        }
    </style>
</head>

<body>

    <div class="admin-card">

        <!-- Header -->
        <div class="admin-header">
            <i class="bi bi-shield-lock-fill"></i>
            <h3 class="mt-3 mb-1">Admin Panel</h3>
            <p class="mb-0 text-light">Secure dashboard login</p>
        </div>

        <!-- Body -->
        <div class="admin-body">

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('dashboard.adminLogin') }}">
                @csrf

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
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="admin@example.com">

                    </div>

                    @error('email')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
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
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Enter password">

                    </div>

                    @error('password')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Button -->
                <button type="submit" class="btn btn-dark w-100 btn-admin">
                    <i class="bi bi-box-arrow-in-right me-1"></i>
                    Login to Dashboard
                </button>

            </form>

        </div>

    </div>

</body>

</html>
