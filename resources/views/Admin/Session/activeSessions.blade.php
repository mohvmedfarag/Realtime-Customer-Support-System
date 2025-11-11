@extends('Admin.layout')
@section('content')
    <div class="container mt-4">
        <div class="d-flex">
            <h3>Active Sessions</h3>
            <h3 style="margin-left: 50px">count: {{ $count }}</h3>
        </div>

        <div class="row" id="sessionsList">
            @forelse ($sessions as $session)
                <div class="col-md-6 col-lg-4 mb-4" id="session-{{ $session->id }}">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <p class="card-title text-dark mb-0">
                                    <strong>Subject:</strong> {{ $session->name }}
                                </p>
                                <span class="badge bg-success text-light">active</span>
                            </div>
                            <div class="d-flex mb-2">
                                <p class="mb-1">
                                    <strong>User <i class="fa-solid fa-user"></i>:</strong> {{ $session->chat->user->name }}
                                </p>
                                <p class="mb-1 ms-2">
                                    <strong>ID:</strong> {{ $session->chat->user_id }}
                                </p>
                            </div>
                            <div class="d-flex">
                                <p class="mb-1">
                                    <strong>Agent <i class="fa-solid fa-headset"></i>:</strong> {{ $session->agent->name }}
                                </p>
                                <p class="mb-1 ms-2">
                                    <strong>ID:</strong> {{ $session->agent_id }}
                                </p>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-end">
                            <button class="btn btn-sm btn-outline-primary show-chat" data-session-id="{{ $session->id }}">
                                <i class="fa fa-eye me-1"></i> Show
                            </button>

                        </div>
                    </div>
                </div>

                {{-- <!-- Modal Chat View -->
                <div class="modal fade" id="chatModal{{ $session->id }}" tabindex="-1" aria-labelledby="chatModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="chatModalLabel">
                                    المحادثة بين {{ $session->chat->user->name }} و {{ $session->agent->name }}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                                <div class="chat-box p-3">
                                    @forelse ($session->messages as $message)
                                        @if ($message->sender === 'agent')
                                            <div class="d-flex justify-content-start mb-3"
                                                data-message-id="{{ $message->id }}">
                                                <div class="p-3 rounded-4 bg-light shadow-sm" style="max-width: 70%;">
                                                    <strong class="text-primary">
                                                        {{ $message->senderAgent->name ?? 'Agent غير معروف' }}:
                                                    </strong>
                                                    <p class="mb-0">{{ $message->content }}</p>
                                                    <small
                                                        class="text-muted d-block mt-1">{{ $message->created_at->format('h:i A') }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <div class="d-flex justify-content-end mb-3"
                                                data-message-id="{{ $message->id }}">
                                                <div class="p-3 rounded-4 bg-primary text-white shadow-sm"
                                                    style="max-width: 70%;">
                                                    <p class="mb-0">{{ $message->content }}</p>
                                                    <small
                                                        class="text-light d-block mt-1">{{ $message->created_at->format('h:i A') }}</small>
                                                </div>
                                            </div>
                                        @endif
                                    @empty
                                        <p class="text-center text-muted">لا توجد رسائل في هذه الجلسة</p>
                                    @endforelse
                                </div>
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                            </div>
                        </div>
                    </div>
                </div> --}}
            @empty
                <p class="text-muted">There are no active sessions</p>
            @endforelse
            <!-- ثابت في آخر الصفحة -->
            <div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="chatModalLabel">المحادثة</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                            <div class="chat-box p-3"></div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        sessionsRef = database.ref('sessions');
        const sessionsList = $("#sessionsList");
        const countElement = $("h3:contains('count')");

        function updateSessionCount() {
            const count = $("#sessionsList .card").length;
            countElement.text(`count: ${count}`);
        }

        function appendSessionCard(id, data) {
            if (document.getElementById('session-' + id)) return;

            const cardHTML = `
                <div class="col-md-6 col-lg-4 mb-4" id="session-${id}">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <p class="card-title text-dark mb-0">
                                    <strong>Subject:</strong> ${data.name ?? 'بدون اسم'}
                                </p>
                                <span class="badge bg-success text-light">active</span>
                            </div>
                            <div class="d-flex mb-2">
                                <p class="mb-1">
                                    <strong>User <i class="fa-solid fa-user"></i>:</strong> ${data.user_name ?? 'غير معروف'}
                                </p>
                                <p class="mb-1 ms-2">
                                    <strong>ID:</strong> ${data.user_id ?? '-'}
                                </p>
                            </div>
                            <div class="d-flex">
                                <p class="mb-1">
                                    <strong>Agent <i class="fa-solid fa-headset"></i>:</strong> ${data.agent_name ?? 'غير محدد'}
                                </p>
                                <p class="mb-1 ms-2">
                                    <strong>ID:</strong> ${data.agent_id ?? '-'}
                                </p>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-end">
                            <button class="btn btn-sm btn-outline-primary show-chat" data-session-id="${id}">
                                <i class="fa fa-eye me-1"></i> Show
                            </button>
                        </div>
                    </div>
                </div>
            `;

            sessionsList.prepend(cardHTML);
            $(`#session-${id}`).hide().fadeIn(500);
            updateSessionCount();
        }

        sessionsRef.on('child_added', (snapshot) => {
            const data = snapshot.val();
            const id = snapshot.key;

            if (data.status === 'in_agent') {
                appendSessionCard(id, data);
                $('.text-muted').hide();
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
                // في حالة رجعت تاني active بعد closed
                if (!document.getElementById('session-' + id)) {
                    appendSessionCard(id, data);
                    $('.text-muted').hide();
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

            chatBox.html('<p class="text-center text-muted">Loading messages...</p>');

            const messagesRef = database.ref('chats/' + sessionId + '/messages');
            messagesRef.off();

            const existingMessageIds = [];

            messagesRef.on('child_added', function(snapshot) {
                const message = snapshot.val();
                const messageId = snapshot.key;

                if (!message || existingMessageIds.includes(messageId)) return;
                existingMessageIds.push(messageId);

                const time = message.created_at ? new Date(message.created_at).toLocaleTimeString() : '';

                let messageHtml = '';

                if (message.sender === 'agent') {
                    messageHtml = `
                <div class="d-flex justify-content-start mb-3">
                    <div class="p-3 rounded-4 bg-light shadow-sm" style="max-width: 70%;">
                        <strong class="text-primary">${message.sender_name || 'Agent'}:</strong>
                        <p class="mb-0">${message.content}</p>
                        <small class="text-muted d-block mt-1">${time}</small>
                    </div>
                </div>
            `;
                } else {
                    messageHtml = `
                <div class="d-flex justify-content-end mb-3">
                    <div class="p-3 rounded-4 bg-primary text-white shadow-sm" style="max-width: 70%;">
                        <p class="mb-0">${message.content}</p>
                        <small class="text-light d-block mt-1">${time}</small>
                    </div>
                </div>
            `;
                }

                if (chatBox.find('p.text-muted').length) chatBox.html('');
                chatBox.append(messageHtml);
                chatBox.scrollTop(chatBox[0].scrollHeight);
            });

            modal.modal('show');
        });
    </script>
@endsection
