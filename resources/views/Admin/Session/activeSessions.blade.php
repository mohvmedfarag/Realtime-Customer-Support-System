@extends('Admin.layout')
@section('content')
    <div class="container mt-4">
        <div class="d-flex">
            <h3>Active Sessions</h3>
            <h3 style="margin-left: 50px">count: {{ $count }}</h3>
        </div>

        <div class="row" id="sessionsList">
            @forelse ($sessions as $session)
                <div class="col-md-6 col-lg-4 mb-4">
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
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#chatModal{{ $session->id }}">
                                <i class="fa fa-eye me-1"></i> Show
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal Chat View -->
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
                                            <div class="d-flex justify-content-start mb-3">
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
                                            <div class="d-flex justify-content-end mb-3">
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
                </div>
            @empty
                <p class="text-muted">There are no active sessions</p>
            @endforelse
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            $('[data-bs-toggle="modal"]').on('click', function(){
                const modalId = $(this).data('bs-target'); // Example: #chatModal5
                const sessionId = modalId.replace('#chatModal', ''); // Get Session ID


                const chatBox = $(`${modalId} .chat-box`);

                const messagesRef = database.ref('chats/' + sessionId + '/messages');

                messagesRef.off();

                messagesRef.on('child_added', function(snapshot) {
                    const message = snapshot.val();
                    if (!message) return;

                    let messageHtml = '';

                    if (message.sender == 'agent'){
                        messageHtml = `
                            <div class="d-flex justify-content-start mb-3">
                                <div class="p-3 rounded-4 bg-light shadow-sm" style="max-width: 70%;">
                                    <strong class="text-primary">${message.sender_name || 'Agent'}:</strong>
                                    <p class="mb-0">${message.content}</p>
                                    <small class="text-muted d-block mt-1">
                                    ${new Date(message.created_at).toLocaleTimeString()}</small>
                                </div>
                            </div>
                        `;
                    }else{
                        messageHtml = `
                            <div class="d-flex justify-content-end mb-3">
                                <div class="p-3 rounded-4 bg-primary text-white shadow-sm" style="max-width: 70%;">
                                    <p class="mb-0">${message.content}</p>
                                    <small class="text-light d-block mt-1">
                                    ${new Date(message.created_at).toLocaleTimeString()}</small>
                                </div>
                            </div>
                        `;
                    }

                    chatBox.append(messageHtml);
                    chatBox.scrollTop(chatBox[0].scrollHeight);
                });
            });

            $('.modal').on('shown.bs.modal', function () {
                const scrollContainer = $(this).find('.modal-body');
                scrollContainer.scrollTop(scrollContainer[0].scrollHeight);
            });
        });
    </script>
@endsection
