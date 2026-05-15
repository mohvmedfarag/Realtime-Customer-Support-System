@extends('Admin.layout')

@section('content')

    <div class="content">

        <!-- Header -->
        <div
            class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">

            <div>

                <h1 class="fw-bold text-white mb-1">
                    Sessions
                </h1>

                <p class="text-secondary mb-0">
                    Monitor all support chat sessions in real time.
                </p>

            </div>

            <!-- Stats -->
            <div class="sessions-stats">

                <div class="stats-box">

                    <div class="stats-icon">
                        <i class="fa-solid fa-comments"></i>
                    </div>

                    <div>

                        <h4 class="mb-0">
                            {{ $count }}
                        </h4>

                        <span>
                            Total Sessions
                        </span>

                    </div>

                </div>

            </div>

        </div>

        <!-- Sessions Table -->
        <div class="glass-card">

            <div class="table-responsive">

                <table class="table custom-table align-middle text-center mb-0">

                    <thead>

                        <tr>

                            <th>#</th>
                            <th>Session ID</th>
                            <th>Session</th>
                            <th>User ID</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>History</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($sessions as $session)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <!-- Session ID -->
                                <td>

                                    <span class="session-id">

                                        #{{ $session->session_id }}

                                    </span>

                                </td>

                                <!-- Session Name -->
                                <td>

                                    <div
                                        class="d-flex align-items-center justify-content-center gap-3">

                                        <div class="session-icon">

                                            <i
                                                class="fa-solid fa-comment-dots"></i>

                                        </div>

                                        <div class="text-start">

                                            <h6
                                                class="mb-0 fw-semibold text-white">

                                                {{ $session->session_name ?? 'Unnamed Session' }}

                                            </h6>

                                            <small
                                                class="text-secondary">

                                                Live Support Session

                                            </small>

                                        </div>

                                    </div>

                                </td>

                                <!-- User ID -->
                                <td>

                                    <span class="user-id">

                                        {{ $session->user_id }}

                                    </span>

                                </td>

                                <!-- User -->
                                <td>

                                    <div
                                        class="d-flex align-items-center justify-content-center gap-2">

                                        <div class="user-avatar">

                                            {{ strtoupper(substr($session->user_name, 0, 1)) }}

                                        </div>

                                        <span class="fw-medium">

                                            {{ $session->user_name }}

                                        </span>

                                    </div>

                                </td>

                                <!-- Status -->
                                <td>

                                    @if ($session->status == 'closed' || $session->status == 'bot')

                                        <span
                                            class="status-badge closed-status">

                                            <i
                                                class="fa-solid fa-circle"></i>

                                            Closed

                                        </span>

                                    @elseif ($session->status == 'waiting_agent')

                                        <span
                                            class="status-badge waiting-status">

                                            <i
                                                class="fa-solid fa-circle"></i>

                                            Waiting

                                        </span>

                                    @else

                                        <span
                                            class="status-badge active-status">

                                            <i
                                                class="fa-solid fa-circle"></i>

                                            Active

                                        </span>

                                    @endif

                                </td>

                                <!-- Action -->
                                <td>

                                    <a href="#"
                                        class="history-btn">

                                        <i
                                            class="fa-solid fa-eye"></i>

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7">

                                    <div class="empty-state">

                                        <i
                                            class="fa-solid fa-comments"></i>

                                        <h5 class="mt-3">
                                            No Sessions Found
                                        </h5>

                                        <p
                                            class="text-secondary mb-0">

                                            There are no support sessions yet.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <style>
        /* Glass Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(18px);
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.35);
        }

        /* Stats */
        .sessions-stats {
            display: flex;
            gap: 15px;
        }

        .stats-box {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 16px 20px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .stats-icon {
            width: 55px;
            height: 55px;
            border-radius: 18px;
            background: linear-gradient(135deg, #06b6d4, #22d3ee);
            color: black;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            box-shadow: 0 0 25px rgba(6, 182, 212, 0.35);
        }

        .stats-box h4 {
            color: white;
            font-weight: 800;
            font-size: 26px;
        }

        .stats-box span {
            color: #94a3b8;
            font-size: 13px;
        }

        /* Table */
        .custom-table {
            color: white;
        }

        .custom-table thead {
            background: rgba(255, 255, 255, 0.03);
        }

        .custom-table thead th {
            padding: 22px;
            border: none;
            color: #94a3b8;
            font-size: 14px;
            font-weight: 600;
        }

        .custom-table tbody td {
            padding: 22px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            vertical-align: middle;
        }

        .custom-table tbody tr {
            transition: 0.25s;
        }

        .custom-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        /* Session ID */
        .session-id {
            background: rgba(255, 255, 255, 0.05);
            padding: 10px 16px;
            border-radius: 30px;
            font-size: 13px;
            color: #cbd5e1;
        }

        /* Session Icon */
        .session-icon {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            background: rgba(6, 182, 212, 0.15);
            color: #22d3ee;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            border: 1px solid rgba(34, 211, 238, 0.2);
        }

        /* User Avatar */
        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #06b6d4, #22d3ee);
            color: black;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* User ID */
        .user-id {
            color: #cbd5e1;
            font-size: 14px;
        }

        /* Status */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
        }

        .status-badge i {
            font-size: 8px;
        }

        .active-status {
            background: rgba(34, 197, 94, 0.12);
            color: #4ade80;
        }

        .waiting-status {
            background: rgba(250, 204, 21, 0.12);
            color: #facc15;
        }

        .closed-status {
            background: rgba(239, 68, 68, 0.12);
            color: #ef4444;
        }

        /* History Button */
        .history-btn {
            width: 46px;
            height: 46px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.05);
            color: #cbd5e1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: 0.25s;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .history-btn:hover {
            background: linear-gradient(135deg, #06b6d4, #22d3ee);
            color: black;
            transform: translateY(-2px);
        }

        /* Empty State */
        .empty-state {
            padding: 80px 20px;
            text-align: center;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 70px;
            color: #334155;
        }

        /* Mobile */
        @media(max-width: 768px) {

            .stats-box {
                width: 100%;
            }

            .sessions-stats {
                width: 100%;
            }
        }
    </style>

@endsection
