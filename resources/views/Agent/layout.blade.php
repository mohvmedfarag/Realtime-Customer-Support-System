<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Agent Dashboard</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Firebase App SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-database-compat.js"></script>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    body {
        margin: 0;
        padding: 0;
        display: flex;
        min-height: 100vh;
        background-color: #f8f9fa;
        overflow-x: hidden;
    }

    /* Sidebar styles */
    .sidebar {
        width: 250px;
        background-color: #343a40;
        color: white;
        transition: all 0.3s ease;
        padding-top: 20px;
        flex-shrink: 0;
    }

    .sidebar h3 {
        color: #fff;
        text-align: center;
        margin-bottom: 30px;
    }

    .sidebar a {
        color: #ddd;
        text-decoration: none;
        padding: 10px 15px;
        border-radius: 6px;
        display: block;
        transition: 0.3s;
    }

    .sidebar a:hover,
    .sidebar a.active {
        background-color: #495057;
        color: #fff;
    }

    .sidebar.collapsed {
        width: 0;
        padding: 0;
        overflow: hidden;
    }

    .content {
        flex: 1;
        padding: 30px;
        transition: margin-left 0.3s ease;
    }

    #toggleBtn {
        position: fixed;
        top: 15px;
        left: 0;
        background-color: #343a40;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 8px 12px;
        font-size: 20px;
        cursor: pointer;
        z-index: 1000;
    }

    #toggleBtn:hover {
        background-color: #495057;
    }
</style>

<body>

    <!-- Toggle button -->
    <button id="toggleBtn">☰</button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h4 style="margin-left: 45px;">Agent</h4>
        <div style="margin-top: 15px;">
            <a href="{{ route('agent.dashboard') }}"
                class="{{ request()->routeIs('agent.dashboard') ? 'active' : '' }}">Dashboard</a>

            <a href="{{ route('agent.sessions.show') }}"
                class="{{ request()->routeIs('agent.sessions.show') ? 'active' : '' }}" style="display: flex;">
                <div>
                    Waiting Sessions
                </div>
                <div style="position:relative; margin-left: 5px;">
                    <i class="fa-solid fa-comments fa-lg"></i>
                    <span
                        style="font-size: 12px; background-color: #ffc107; padding: 0px 5px; border-radius: 50%; position: absolute; top: -6px; right: -5px; color:#343a40;">{{ $sessionsCount }}</span>
                </div>
            </a>

            @if ($session)
                @if ($session->agent_id === Auth::guard('agent')->user()->id)
                    <a href="{{ route('agent.sessions.join', $session->id) }}" class="" style="display: flex;">
                        <div>
                            My Active Sessions
                        </div>
                        <div style="position:relative; margin-left: 5px;">
                            <i class="fa-solid fa-comments fa-lg"></i>
                            <span
                                style="font-size: 12px; background-color: #198754; padding: 0px 5px; border-radius: 50%; position: absolute; top: -6px; right: -5px;">
                                1</span>
                        </div>
                    </a>
                @else
                    <a href="#" class="" style="display: flex;">
                        <div>
                            My Active Sessions
                        </div>
                        <div style="position:relative; margin-left: 5px;">
                            <i class="fa-solid fa-comments fa-lg"></i>
                            <span
                                style="font-size: 12px; background-color: #198754; padding: 0px 5px; border-radius: 50%; position: absolute; top: -6px; right: -5px;">
                                0</span>
                        </div>
                    </a>
                @endif
            @endif

            <a href="javascript:void(0)" onclick="document.getElementById('agent-logout').submit()"
                class="">Logout</a>
            <form action="" method="post" id="agent-logout">@csrf</form>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Sidebar toggle functionality
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebar = document.getElementById('sidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>
    @yield('scripts')
</body>

</html>
