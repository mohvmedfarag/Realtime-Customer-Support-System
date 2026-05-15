@extends('Admin.layout')

@section('content')
    <div class="content">

        <!-- Page Header -->
        <div
            class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">

            <div>
                <h2 class="fw-bold mb-1 text-white">
                    <i class="fa-solid fa-bolt text-cyan me-2"></i>
                    Active Sessions
                </h2>

                <p class="text-secondary mb-0">
                    Monitor all live conversations in realtime
                </p>
            </div>

            <!-- Count Card -->
            <div class="sessions-counter-card">
                <div class="counter-icon">
                    <i class="fa-solid fa-comments"></i>
                </div>

                <div>
                    <span class="counter-label">Total Active</span>
                    <h4 class="mb-0 counter-value" id="sessions-count">
                        {{ $count }}
                    </h4>
                </div>
            </div>

        </div>

        <!-- Sessions Grid -->
        <div class="row g-4" id="sessionsList">

            @forelse ($sessions as $session)

                <div class="col-md-6 col-xl-4" id="session-{{ $session->id }}">

                    <div class="session-card h-100">

                        <!-- Top -->
                        <div class="session-card-header">

                            <div>
                                <span class="session-label">
                                    Subject
                                </span>

                                <h5 class="session-subject">
                                    {{ $session->name ?? 'Untitled Session' }}
                                </h5>
                            </div>

                            <span class="status-badge active">
                                <span class="status-dot"></span>
                                Active
                            </span>

                        </div>

                        <!-- Body -->
                        <div class="session-card-body">

                            <!-- User -->
                            <div class="session-info-box">

                                <div class="session-info-icon user">
                                    <i class="fa-solid fa-user"></i>
                                </div>

                                <div>
                                    <p class="session-info-title">
                                        User
                                    </p>

                                    <h6 class="session-info-name">
                                        {{ $session->chat->user->name }}
                                    </h6>

                                    <small class="session-info-id">
                                        ID: {{ $session->chat->user_id }}
                                    </small>
                                </div>

                            </div>

                            <!-- Agent -->
                            <div class="session-info-box">

                                <div class="session-info-icon agent">
                                    <i class="fa-solid fa-headset"></i>
                                </div>

                                <div>
                                    <p class="session-info-title">
                                        Agent
                                    </p>

                                    <h6 class="session-info-name">
                                        {{ $session->agent->name }}
                                    </h6>

                                    <small class="session-info-id">
                                        ID: {{ $session->agent_id }}
                                    </small>
                                </div>

                            </div>

                        </div>

                        <!-- Footer -->
                        <div class="session-card-footer">

                            <button class="show-chat-btn show-chat"
                                data-session-id="{{ $session->id }}">

                                <i class="fa-solid fa-eye"></i>
                                View Conversation

                            </button>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12">

                    <div class="empty-state">

                        <div class="empty-icon">
                            <i class="fa-solid fa-comments"></i>
                        </div>

                        <h4>
                            No Active Sessions
                        </h4>

                        <p class="text-secondary mb-0">
                            There are currently no active conversations.
                        </p>

                    </div>

                </div>

            @endforelse

        </div>

        <!-- Chat Modal -->
        <div class="modal fade" id="chatModal" tabindex="-1" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered modal-lg">

                <div class="modal-content custom-modal">

                    <div class="modal-header border-0">

                        <h5 class="modal-title fw-bold">
                            <i class="fa-solid fa-comments me-2 text-info"></i>
                            Live Conversation
                        </h5>

                        <button type="button" class="btn-close btn-close-white"
                            data-bs-dismiss="modal"></button>

                    </div>

                    <div class="modal-body">

                        <div class="chat-box">

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <style>
        .text-cyan {
            color: #22d3ee;
        }

        .sessions-counter-card {
            display: flex;
            align-items: center;
            gap: 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 18px 22px;
            border-radius: 22px;
            backdrop-filter: blur(14px);
        }

        .counter-icon {
            width: 55px;
            height: 55px;
            border-radius: 18px;
            background: linear-gradient(135deg, #06b6d4, #22d3ee);
            display: flex;
            align-items: center;
            justify-content: center;
            color: black;
            font-size: 20px;
            box-shadow: 0 0 25px rgba(34, 211, 238, 0.35);
        }

        .counter-label {
            color: #94a3b8;
            font-size: 13px;
        }

        .counter-value {
            color: white;
            font-weight: 700;
        }

        .session-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 28px;
            overflow: hidden;
            backdrop-filter: blur(18px);
            transition: all 0.3s ease;
        }

        .session-card:hover {
            transform: translateY(-6px);
            border-color: rgba(34, 211, 238, 0.35);
            box-shadow: 0 0 35px rgba(34, 211, 238, 0.12);
        }

        .session-card-header {
            padding: 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
        }

        .session-label {
            color: #94a3b8;
            font-size: 13px;
            display: block;
            margin-bottom: 6px;
        }

        .session-subject {
            color: white;
            margin: 0;
            font-weight: 700;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
        }

        .status-badge.active {
            background: rgba(34, 197, 94, 0.15);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: currentColor;
        }

        .session-card-body {
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .session-info-box {
            display: flex;
            align-items: center;
            gap: 14px;
            background: rgba(255, 255, 255, 0.04);
            padding: 16px;
            border-radius: 18px;
        }

        .session-info-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .session-info-icon.user {
            background: rgba(59, 130, 246, 0.18);
            color: #60a5fa;
        }

        .session-info-icon.agent {
            background: rgba(34, 211, 238, 0.18);
            color: #22d3ee;
        }

        .session-info-title {
            margin-bottom: 4px;
            color: #94a3b8;
            font-size: 13px;
        }

        .session-info-name {
            margin: 0;
            color: white;
            font-weight: 600;
        }

        .session-info-id {
            color: #64748b;
        }

        .session-card-footer {
            padding: 0 24px 24px;
        }

        .show-chat-btn {
            width: 100%;
            border: none;
            border-radius: 18px;
            padding: 14px;
            background: linear-gradient(135deg, #06b6d4, #22d3ee);
            color: black;
            font-weight: 700;
            transition: 0.3s;
        }

        .show-chat-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 25px rgba(34, 211, 238, 0.35);
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 28px;
            border: 1px dashed rgba(255, 255, 255, 0.08);
        }

        .empty-icon {
            width: 90px;
            height: 90px;
            margin: auto auto 22px;
            border-radius: 28px;
            background: rgba(34, 211, 238, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 34px;
            color: #22d3ee;
        }

        .custom-modal {
            background: #0f172a;
            border-radius: 28px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: white;
            overflow: hidden;
        }

        .chat-box {
            max-height: 500px;
            overflow-y: auto;
            padding: 10px;
        }

        .chat-box::-webkit-scrollbar {
            width: 6px;
        }

        .chat-box::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
        }

        @media(max-width:768px) {
            .session-card-header {
                flex-direction: column;
            }

            .sessions-counter-card {
                width: 100%;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        sessionsRef = database.ref('sessions');

        const sessionsList = $("#sessionsList");

        function updateSessionCount() {
            const count = $("#sessionsList .session-card").length;
            $("#sessions-count").text(count);
        }

        function appendSessionCard(id, data) {

            if (document.getElementById('session-' + id)) return;

            const cardHTML = `
                <div class="col-md-6 col-xl-4" id="session-${id}">
                    <div class="session-card h-100">

                        <div class="session-card-header">

                            <div>
                                <span class="session-label">
                                    Subject
                                </span>

                                <h5 class="session-subject">
                                    ${data.name ?? 'Untitled Session'}
                                </h5>
                            </div>

                            <span class="status-badge active">
                                <span class="status-dot"></span>
                                Active
                            </span>

                        </div>

                        <div class="session-card-body">

                            <div class="session-info-box">

                                <div class="session-info-icon user">
                                    <i class="fa-solid fa-user"></i>
                                </div>

                                <div>
                                    <p class="session-info-title">
                                        User
                                    </p>

                                    <h6 class="session-info-name">
                                        ${data.user_name ?? 'Unknown'}
                                    </h6>

                                    <small class="session-info-id">
                                        ID: ${data.user_id ?? '-'}
                                    </small>
                                </div>

                            </div>

                            <div class="session-info-box">

                                <div class="session-info-icon agent">
                                    <i class="fa-solid fa-headset"></i>
                                </div>

                                <div>
                                    <p class="session-info-title">
                                        Agent
                                    </p>

                                    <h6 class="session-info-name">
                                        ${data.agent_name ?? 'Unknown'}
                                    </h6>

                                    <small class="session-info-id">
                                        ID: ${data.agent_id ?? '-'}
                                    </small>
                                </div>

                            </div>

                        </div>

                        <div class="session-card-footer">

                            <button class="show-chat-btn show-chat"
                                data-session-id="${id}">

                                <i class="fa-solid fa-eye"></i>
                                View Conversation

                            </button>

                        </div>

                    </div>
                </div>
            `;

            sessionsList.prepend(cardHTML);

            $(`#session-${id}`).hide().fadeIn(400);

            updateSessionCount();
        }

        sessionsRef.on('child_added', (snapshot) => {

            const data = snapshot.val();
            const id = snapshot.key;

            if (data.status === 'in_agent') {

                appendSessionCard(id, data);

                $('.empty-state').hide();
            }
        });

        sessionsRef.on('child_changed', (snapshot) => {

            const data = snapshot.val();
            const id = snapshot.key;

            if (data.status !== 'in_agent') {

                $(`#session-${id}`).fadeOut(400, function() {

                    $(this).remove();

                    updateSessionCount();
                });

            } else {

                if (!document.getElementById('session-' + id)) {

                    appendSessionCard(id, data);

                    $('.empty-state').hide();
                }
            }
        });

        sessionsRef.on('child_removed', (snapshot) => {

            const id = snapshot.key;

            $(`#session-${id}`).fadeOut(400, function() {

                $(this).remove();

                updateSessionCount();
            });
        });
    </script>

    <script>
        $(document).on('click', '.show-chat', function() {

            const sessionId = $(this).data('session-id');

            const modal = $('#chatModal');

            const chatBox = modal.find('.chat-box');

            chatBox.html(
                '<p class="text-center text-secondary">Loading messages...</p>'
            );

            const messagesRef = database.ref('chats/' + sessionId + '/messages');

            messagesRef.off();

            const existingMessageIds = [];

            messagesRef.on('child_added', function(snapshot) {

                const message = snapshot.val();
                const messageId = snapshot.key;

                if (!message || existingMessageIds.includes(messageId)) return;

                existingMessageIds.push(messageId);

                const time = message.created_at ?
                    new Date(message.created_at).toLocaleTimeString() : '';

                let messageHtml = '';

                if (message.sender === 'agent') {

                    messageHtml = `
                        <div class="d-flex justify-content-start mb-3">

                            <div class="p-3 rounded-4 shadow-sm"
                                style="max-width: 75%; background: rgba(255,255,255,0.08);">

                                <strong class="text-info">
                                    ${message.sender_name || 'Agent'}
                                </strong>

                                <p class="mb-0 mt-1 text-white">
                                    ${message.content}
                                </p>

                                <small class="text-secondary d-block mt-2">
                                    ${time}
                                </small>

                            </div>

                        </div>
                    `;

                } else {

                    messageHtml = `
                        <div class="d-flex justify-content-end mb-3">

                            <div class="p-3 rounded-4 shadow-sm"
                                style="max-width: 75%; background: linear-gradient(135deg, #06b6d4, #22d3ee); color:black;">

                                <p class="mb-0 fw-semibold">
                                    ${message.content}
                                </p>

                                <small class="d-block mt-2">
                                    ${time}
                                </small>

                            </div>

                        </div>
                    `;
                }

                if (chatBox.find('p.text-secondary').length) {

                    chatBox.html('');
                }

                chatBox.append(messageHtml);

                chatBox.scrollTop(chatBox[0].scrollHeight);
            });

            modal.modal('show');
        });
    </script>
@endsection
