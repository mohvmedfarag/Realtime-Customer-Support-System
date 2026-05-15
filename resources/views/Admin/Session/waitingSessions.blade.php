@extends('Admin.layout')

@section('content')
    <div class="content">

        <!-- Header -->
        <div
            class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4 mb-4">

            <div>
                <h2 class="fw-bold mb-2 text-white">
                    <i class="fa-solid fa-clock text-warning me-2"></i>
                    Waiting Sessions
                </h2>

                <p class="text-secondary mb-0">
                    Manage sessions waiting for agent assignment
                </p>
            </div>

            <!-- Count Card -->
            <div class="stats-card">
                <div class="stats-icon warning">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>

                <div>
                    <h3 class="mb-0 fw-bold text-white count-value">
                        {{ $count }}
                    </h3>

                    <small class="text-secondary">
                        Waiting Sessions
                    </small>
                </div>
            </div>

        </div>

        <!-- Sessions Table Card -->
        <div class="custom-card">

            <div class="table-responsive">

                <table class="table custom-table align-middle">

                    <thead>
                        <tr>
                            <th>Session ID</th>
                            <th>Session</th>
                            <th>User</th>
                            <th>Assigned Agent</th>
                            <th>Status</th>
                            <th class="text-center">Assign</th>
                        </tr>
                    </thead>

                    <tbody id="sessions-list">

                        @forelse($sessions as $session)

                            <tr id="session-{{ $session->id }}">

                                <td>
                                    <span class="session-id">
                                        #{{ $session->id }}
                                    </span>
                                </td>

                                <td>

                                    <div class="d-flex align-items-center gap-3">

                                        <div class="session-icon">
                                            <i class="fa-solid fa-comments"></i>
                                        </div>

                                        <div>
                                            <h6 class="mb-1 text-white fw-semibold">
                                                {{ $session->name ?? 'بدون اسم' }}
                                            </h6>

                                            <small class="text-secondary">
                                                Chat Session
                                            </small>
                                        </div>

                                    </div>

                                </td>

                                <td>

                                    <div class="user-box">

                                        <div class="user-avatar">
                                            <i class="fa-solid fa-user"></i>
                                        </div>

                                        <div>
                                            <h6 class="mb-0 text-white">
                                                {{ $session->chat->user->name }}
                                            </h6>

                                            <small class="text-secondary">
                                                User ID: {{ $session->chat->user->id }}
                                            </small>
                                        </div>

                                    </div>

                                </td>

                                <td>

                                    <div class="user-box">

                                        <div class="agent-avatar">
                                            <i class="fa-solid fa-headset"></i>
                                        </div>

                                        <div>
                                            <h6 class="mb-0 text-white">
                                                {{ $session->agent->name }}
                                            </h6>

                                            <small class="text-secondary">
                                                Agent ID: {{ $session->agent_id }}
                                            </small>
                                        </div>

                                    </div>

                                </td>

                                <td>

                                    <span class="status-badge waiting">

                                        <i class="fa-solid fa-clock"></i>

                                        waiting from
                                        {{ $session->waiting_started_at->diffForHumans() }}

                                    </span>

                                </td>

                                <td class="text-center">

                                    <button type="button"
                                        class="action-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#assignModal{{ $session->id }}">

                                        <i class="fa-solid fa-user-plus"></i>
                                        Assign

                                    </button>

                                </td>

                            </tr>

                            <!-- Assign Modal -->
                            <div class="modal fade"
                                id="assignModal{{ $session->id }}"
                                tabindex="-1"
                                aria-hidden="true">

                                <div class="modal-dialog modal-dialog-centered modal-xl">

                                    <div class="modal-content custom-modal">

                                        <!-- Header -->
                                        <div class="modal-header border-0">

                                            <div>

                                                <h5 class="modal-title text-white fw-bold">
                                                    Assign Session
                                                </h5>

                                                <p class="text-secondary mb-0 mt-1">
                                                    Session #{{ $session->id }} -
                                                    {{ $session->name }}
                                                </p>

                                            </div>

                                            <button type="button"
                                                class="btn-close btn-close-white"
                                                data-bs-dismiss="modal"></button>

                                        </div>

                                        <!-- Body -->
                                        <div class="modal-body">

                                            <div class="row g-4">

                                                @foreach ($agents as $agent)

                                                    <div class="col-lg-6">

                                                        <div class="agent-card">

                                                            <div class="d-flex justify-content-between align-items-center">

                                                                <!-- Agent Info -->
                                                                <div class="d-flex align-items-center gap-3">

                                                                    <div class="agent-image">

                                                                        <i class="fa-solid fa-headset"></i>

                                                                    </div>

                                                                    <div>

                                                                        <h6 class="mb-1 fw-bold text-white">
                                                                            {{ $agent->name }}
                                                                        </h6>

                                                                        <p class="text-secondary small mb-2">
                                                                            {{ $agent->department->name ?? 'No Department' }}
                                                                        </p>

                                                                        @if ($agent->status == 'online')

                                                                            <span class="online-badge">
                                                                                <span class="dot"></span>
                                                                                Online
                                                                            </span>

                                                                        @else

                                                                            <span class="offline-badge">
                                                                                Offline
                                                                            </span>

                                                                        @endif

                                                                    </div>

                                                                </div>

                                                                <!-- Button -->
                                                                <button type="button"
                                                                    class="assign-agent-btn assign-btn"
                                                                    data-agent-id="{{ $agent->id }}"
                                                                    data-session-id="{{ $session->id }}">

                                                                    <i class="fa-solid fa-paper-plane"></i>

                                                                    Assign

                                                                </button>

                                                            </div>

                                                        </div>

                                                    </div>

                                                @endforeach

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <tr>

                                <td colspan="6">

                                    <div class="empty-state">

                                        <div class="empty-icon">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </div>

                                        <h4 class="text-white mt-4">
                                            No Waiting Sessions
                                        </h4>

                                        <p class="text-secondary mb-0">
                                            All sessions are currently assigned
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
@endsection

@section('scripts')

    <style>
        .custom-card {
            background: rgba(15, 23, 42, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 28px;
            overflow: hidden;
            backdrop-filter: blur(20px);
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.25);
        }

        .stats-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 18px 24px;
            border-radius: 22px;
            display: flex;
            align-items: center;
            gap: 18px;
            min-width: 230px;
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stats-icon.warning {
            background: rgba(251, 191, 36, 0.15);
            color: #fbbf24;
            box-shadow: 0 0 25px rgba(251, 191, 36, 0.25);
        }

        .custom-table {
            margin: 0;
            color: white;
        }

        .custom-table thead {
            background: rgba(255, 255, 255, 0.04);
        }

        .custom-table thead th {
            padding: 22px;
            border: none;
            color: #94a3b8;
            font-size: 14px;
            font-weight: 600;
        }

        .custom-table tbody tr {
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            transition: 0.25s ease;
        }

        .custom-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        .custom-table tbody td {
            padding: 22px;
            border: none;
            vertical-align: middle;
        }

        .session-id {
            background: rgba(6, 182, 212, 0.12);
            color: #22d3ee;
            padding: 10px 14px;
            border-radius: 14px;
            font-weight: 700;
        }

        .session-icon,
        .user-avatar,
        .agent-avatar {
            width: 50px;
            height: 50px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .session-icon {
            background: rgba(6, 182, 212, 0.15);
            color: #22d3ee;
        }

        .user-avatar {
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
        }

        .agent-avatar {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
        }

        .user-box {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .status-badge {
            padding: 10px 16px;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
        }

        .status-badge.waiting {
            background: rgba(251, 191, 36, 0.15);
            color: #fbbf24;
        }

        .action-btn {
            border: none;
            background: linear-gradient(135deg, #06b6d4, #22d3ee);
            color: black;
            padding: 12px 20px;
            border-radius: 14px;
            font-weight: 700;
            transition: 0.3s;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 25px rgba(6, 182, 212, 0.35);
        }

        .custom-modal {
            background: #0f172a;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 28px;
        }

        .agent-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 22px;
            padding: 20px;
            transition: 0.3s;
        }

        .agent-card:hover {
            border-color: rgba(6, 182, 212, 0.3);
            transform: translateY(-3px);
        }

        .agent-image {
            width: 58px;
            height: 58px;
            border-radius: 18px;
            background: rgba(6, 182, 212, 0.15);
            color: #22d3ee;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .assign-agent-btn {
            border: none;
            background: rgba(6, 182, 212, 0.15);
            color: #22d3ee;
            padding: 10px 18px;
            border-radius: 14px;
            font-weight: 700;
            transition: 0.3s;
        }

        .assign-agent-btn:hover {
            background: #06b6d4;
            color: black;
        }

        .online-badge,
        .offline-badge {
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .online-badge {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
        }

        .offline-badge {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #34d399;
        }

        .empty-state {
            padding: 80px 20px;
            text-align: center;
        }

        .empty-icon {
            width: 100px;
            height: 100px;
            margin: auto;
            border-radius: 30px;
            background: rgba(16, 185, 129, 0.12);
            color: #34d399;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
        }

        @media(max-width: 992px) {

            .custom-table thead {
                display: none;
            }

            .custom-table tbody tr {
                display: block;
                margin: 16px;
                border-radius: 20px;
                background: rgba(255, 255, 255, 0.03);
            }

            .custom-table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 14px 18px;
            }

            .stats-card {
                width: 100%;
            }
        }
    </style>

    <script>
        const agents = @json($agents);
    </script>

    {{-- Assign session --}}
    <script>
        $(document).ready(function() {

            $(document).on('click', '.assign-btn', function() {

                let agentId = $(this).data('agent-id');
                let sessionId = $(this).data('session-id');
                let button = $(this);

                $.ajax({
                    url: "{{ route('dashboard.transferSessionToAgent') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        agent_id: agentId,
                        session_id: sessionId
                    },

                    success: function(response) {

                        button.closest('.modal').modal('hide');

                        const flash = `
                            <div class="position-fixed top-0 end-0 p-3" style="z-index:9999">
                                <div class="alert alert-success shadow border-0 rounded-4">
                                    ${response.message}
                                </div>
                            </div>
                        `;

                        $('body').append(flash);

                        setTimeout(() => {
                            $('.alert').parent().fadeOut(400, function() {
                                $(this).remove();
                            });
                        }, 2000);
                    },

                    error: function(xhr) {

                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON?.message ||
                                'حدث خطأ أثناء التعيين',
                            icon: 'error'
                        });

                    }
                });

            });

        });
    </script>

@endsection
