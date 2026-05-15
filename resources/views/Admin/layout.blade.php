<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <!-- Firebase App SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-database-compat.js"></script>
</head>
<style>
    body {
        background: #0f172a;
        color: white;
        overflow-x: hidden;
    }

    /* Sidebar */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 280px;
        height: 100vh;
        background: rgba(15, 23, 42, 0.9);
        backdrop-filter: blur(20px);
        border-right: 1px solid rgba(255, 255, 255, 0.08);
        padding: 24px 18px;
        transition: all 0.3s ease;
        z-index: 999;
    }

    .sidebar.collapsed {
        transform: translateX(-100%);
    }

    /* Logo */
    .sidebar-logo {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 40px;
    }

    .logo-icon {
        width: 52px;
        height: 52px;
        border-radius: 18px;
        background: #06b6d4;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: black;
        box-shadow: 0 0 25px rgba(6, 182, 212, 0.5);
    }

    .sidebar-logo h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
    }

    .sidebar-logo p {
        margin: 0;
        color: #94a3b8;
        font-size: 12px;
    }

    /* Sidebar Links */
    .sidebar-links {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .sidebar a {
        text-decoration: none;
        color: #cbd5e1;
        padding: 14px 16px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: all 0.25s ease;
        font-weight: 500;
        border: 1px solid transparent;
    }

    .sidebar a:hover {
        background: rgba(255, 255, 255, 0.06);
        color: white;
        border-color: rgba(255, 255, 255, 0.08);
        transform: translateX(4px);
    }

    .sidebar a.active {
        background: linear-gradient(135deg, #06b6d4, #22d3ee);
        color: black;
        font-weight: 700;
        box-shadow: 0 0 25px rgba(6, 182, 212, 0.35);
    }

    .sidebar a i {
        width: 22px;
        text-align: center;
        font-size: 16px;
    }

    /* Toggle Button */
    #toggleBtn {
        position: fixed;
        top: 20px;
        left: 20px;
        z-index: 1000;
        width: 50px;
        height: 50px;
        border: none;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.08);
        color: white;
        backdrop-filter: blur(12px);
        transition: 0.3s;
        display: none;
    }

    #toggleBtn:hover {
        background: #06b6d4;
        color: black;
    }

    /* Main Content */
    .content {
        margin-left: 280px;
        padding: 30px;
        min-height: 100vh;
    }

    /* Mobile */
    @media(max-width: 992px) {

        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.show {
            transform: translateX(0);
        }

        #toggleBtn {
            display: block;
        }

        .content {
            margin-left: 0;
            padding-top: 90px;
        }
    }

    /* Status Badge */
    .sessionStatus {
        padding: 5px 10px;
        font-size: 12px;
        border-radius: 30px;
        color: white;
    }
</style>

<body>

    <!-- Toggle button -->
    <button id="toggleBtn">☰</button>

    <!-- Sidebar -->
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">

        <!-- Logo -->
        <div class="sidebar-logo">

            <div class="logo-icon">
                💬
            </div>

            <div>
                <h3>SupportFlow</h3>
                <p>Admin Dashboard</p>
            </div>

        </div>

        <!-- Links -->
        <div class="sidebar-links">

            <a href="{{ route('dashboard.index') }}"
                class="{{ request()->routeIs('dashboard.index') ? 'active' : '' }}">

                <i class="fa-solid fa-chart-line"></i>
                Dashboard

            </a>

            <a href="{{ route('dashboard.departments') }}"
                class="{{ request()->routeIs('dashboard.departments') ? 'active' : '' }}">

                <i class="fa-solid fa-building"></i>
                Departments

            </a>

            <a href="{{ route('dashboard.sessions') }}"
                class="{{ request()->routeIs('dashboard.sessions') ? 'active' : '' }}">

                <i class="fa-solid fa-comments"></i>
                Sessions

            </a>

            <a href="{{ route('dashboard.showWaitingSessions') }}"
                class="{{ request()->routeIs('dashboard.showWaitingSessions') ? 'active' : '' }}">

                <i class="fa-solid fa-clock"></i>
                Waiting Sessions

            </a>

            <a href="{{ route('dashboard.showActiveSessions') }}"
                class="{{ request()->routeIs('dashboard.showActiveSessions') ? 'active' : '' }}">

                <i class="fa-solid fa-bolt"></i>
                Active Sessions

            </a>

            <a href="{{ route('dashboard.agents') }}"
                class="{{ request()->routeIs('dashboard.agents') ? 'active' : '' }}">

                <i class="fa-solid fa-headset"></i>
                Agents

            </a>

            <a href="{{ route('dashboard.logout') }}" class="mt-3">

                <i class="fa-solid fa-right-from-bracket"></i>
                Logout

            </a>

        </div>

    </div>

    <!-- Main content -->
    @yield('content')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebar = document.getElementById('sidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('show');
        });
    </script>

    <script>
        const firebaseConfig = {
            databaseURL: "{{ env('FIREBASE_DATABASE_URL') }}"
        };

        if (!firebase.apps.length) {
            firebase.initializeApp(firebaseConfig);
        }

        const database = firebase.database();
    </script>

    @yield('scripts')

</body>

</html>
