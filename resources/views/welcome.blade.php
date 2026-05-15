<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SupportFlow</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #0f172a, #111827);
            min-height: 100vh;
            overflow-x: hidden;
            color: white;
            font-family: sans-serif;
        }

        .navbar-custom {
            background: rgba(255,255,255,0.03);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .brand-icon {
            width: 45px;
            height: 45px;
            border-radius: 14px;
            background: #06b6d4;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 10px 30px rgba(6,182,212,.3);
        }

        .hero-title {
            font-size: 65px;
            font-weight: 800;
            line-height: 1.1;
        }

        .hero-title span {
            color: #22d3ee;
        }

        .hero-text {
            color: #94a3b8;
            font-size: 18px;
            line-height: 1.8;
        }

        .btn-primary-custom {
            background: #06b6d4;
            border: none;
            color: black;
            font-weight: 700;
            border-radius: 14px;
            padding: 14px 28px;
            transition: .3s;
        }

        .btn-primary-custom:hover {
            background: #22d3ee;
        }

        .btn-outline-custom {
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
            border-radius: 14px;
            padding: 14px 28px;
            background: rgba(255,255,255,0.03);
        }

        .chat-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
            border-radius: 30px;
            padding: 30px;
            box-shadow: 0 20px 50px rgba(0,0,0,.3);
        }

        .message-support {
            background: rgba(255,255,255,0.08);
            padding: 14px 18px;
            border-radius: 18px 18px 18px 4px;
            display: inline-block;
            max-width: 260px;
            color: #e2e8f0;
        }

        .message-user {
            background: #06b6d4;
            color: black;
            padding: 14px 18px;
            border-radius: 18px 18px 4px 18px;
            display: inline-block;
            max-width: 260px;
            font-weight: 600;
        }

        .chat-input {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            height: 55px;
            color: white;
        }

        .chat-input::placeholder {
            color: #94a3b8;
        }

        .floating-widget {
            position: fixed;
            bottom: 25px;
            left: 25px;
            width: 70px;
            height: 70px;
            border-radius: 22px;
            background: #06b6d4;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: black;
            box-shadow: 0 15px 40px rgba(6,182,212,.4);
            animation: bounce 2s infinite;
            cursor: pointer;
            z-index: 999;
        }

        @keyframes bounce {
            0%,100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .stat-number {
            color: #22d3ee;
            font-size: 32px;
            font-weight: 700;
        }

        .stat-text {
            color: #94a3b8;
        }

        @media(max-width: 768px) {
            .hero-title {
                font-size: 42px;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom py-3">
        <div class="container">

            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center gap-3" href="#">

                <div class="brand-icon">
                    <i class="fa-solid fa-comments"></i>
                </div>

                <div>
                    <div class="fw-bold">
                        SupportFlow
                    </div>

                    <small class="text-secondary">
                        Realtime Support
                    </small>
                </div>

            </a>

            <!-- Auth -->
            <div class="d-flex align-items-center gap-3">

                @auth

                    <a href="#"
                       class="btn btn-outline-light rounded-pill px-4">
                        <i class="fa-solid fa-user me-2"></i>
                        Profile
                    </a>

                @else

                    <a href="{{ route('showLoginForm')}}"
                       class="text-decoration-none text-light">
                        Login
                    </a>

                    <a href="{{ route('showRegisterForm') }}"
                       class="btn btn-primary-custom">
                        Register
                    </a>

                @endauth

            </div>

        </div>
    </nav>

    <!-- Hero -->
    <section class="py-5">

        <div class="container py-5">

            <div class="row align-items-center gy-5">

                <!-- Left -->
                <div class="col-lg-6">

                    <div class="mb-4">

                        <span class="badge rounded-pill bg-info bg-opacity-25 text-info px-4 py-3">
                            ⚡ Firebase Realtime Database
                        </span>

                    </div>

                    <h1 class="hero-title mb-4">

                        Smart Realtime
                        <span>Support Chat</span>

                    </h1>

                    <p class="hero-text mb-5">

                        Create live support sessions between users,
                        technical support teams, and administrators with
                        realtime messaging and monitoring.

                    </p>

                    <div class="d-flex flex-wrap gap-3 mb-5">

                        <a href="{{ route('showRegisterForm') }}"
                           class="btn btn-primary-custom">

                            Get Started

                        </a>

                        <a href="{{ route('showLoginForm') }}"
                           class="btn btn-outline-custom">

                            Login

                        </a>

                    </div>

                    <!-- Stats -->
                    <div class="row">

                        <div class="col-4">
                            <div class="stat-number">
                                24/7
                            </div>

                            <div class="stat-text">
                                Live Support
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="stat-number">
                                Instant
                            </div>

                            <div class="stat-text">
                                Messaging
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="stat-number">
                                Secure
                            </div>

                            <div class="stat-text">
                                Sessions
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Right -->
                <div class="col-lg-6">

                    <div class="chat-card">

                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div class="d-flex align-items-center gap-3">

                                <div class="brand-icon">
                                    <i class="fa-solid fa-headset"></i>
                                </div>

                                <div>

                                    <h5 class="mb-1">
                                        Technical Support
                                    </h5>

                                    <small class="text-success">
                                        Online now
                                    </small>

                                </div>

                            </div>

                            <div class="text-success">
                                ●
                            </div>

                        </div>

                        <!-- Messages -->
                        <div class="mb-3">
                            <div class="message-support">
                                Hello 👋
                                <br>
                                How can we help you today?
                            </div>
                        </div>

                        <div class="mb-3 text-end">
                            <div class="message-user">
                                I have an issue with my account.
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="message-support">
                                Support team joined the session.
                            </div>
                        </div>

                        <!-- Input -->
                        <div class="d-flex gap-3">

                            <input type="text"
                                   class="form-control chat-input"
                                   placeholder="Type your message...">

                            <button class="btn btn-primary-custom px-4">
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- Floating Chat -->
    <div class="floating-widget">

        <i class="fa-solid fa-comments"></i>

    </div>

</body>

</html>
