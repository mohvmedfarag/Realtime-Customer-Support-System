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
    .sessionStatus{
        padding: 3px 8px;
        font-size: 12px;
        border-radius: 13px;
        color: white;
    }
</style>
<body>

    <!-- Toggle button -->
    <button id="toggleBtn">☰</button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h3>Admin Panel</h3>
        <a href="{{ route('dashboard.index') }}"
            class="{{ request()->routeIs('dashboard.index') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('dashboard.departments') }}"
            class="{{ request()->routeIs('dashboard.departments') ? 'active' : '' }}">Departments</a>
        <a href="{{ route('dashboard.sessions') }}"
            class="{{ request()->routeIs('dashboard.sessions') ? 'active' : '' }}">Sessions</a>
        <a href="{{ route('dashboard.showWaitingSessions') }}"
            class="{{ request()->routeIs('dashboard.showWaitingSessions') ? 'active' : '' }}">Waiting Sessions</a>
        <a href="{{ route('dashboard.showActiveSessions') }}"
            class="{{ request()->routeIs('dashboard.showActiveSessions') ? 'active' : '' }}">Active Sessions</a>
        <a href="{{ route('dashboard.agents') }}"
            class="{{ request()->routeIs('dashboard.agents') ? 'active' : '' }}">Agents</a>
        <a href="{{ route('dashboard.logout') }}"
            class="{{ request()->routeIs('dashboard.logout') ? 'active' : '' }}">Logout</a>
    </div>

    <!-- Main content -->
    @yield('content')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebar = document.getElementById('sidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
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
