<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الدردشة مع الدعم الفني</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Firebase App SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js"></script>
    <!-- Firebase Database SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-database-compat.js"></script>
    <!-- Css Style -->
    <link rel="stylesheet" href="{{ asset('assets/user_chat.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/session_sidebar.css') }}">
</head>

<body>
    <div class="container-fluid p-0">
        <div class="chat-container">

            <!-- Header -->
            <div class="chat-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="fas fa-headset me-2"></i>
                            <span id="agentNameValue"></span>
                        </h5>
                    </div>
                    <div>
                        <img src="{{ asset('assets/ESTBN.jpeg') }}" style="height: 30px; width:100px">
                    </div>

                    <div class="position-relative">
                        <button class="btn btn-sm btn-light" id="menuToggle">
                            <i class="fa-solid fa-bars"></i>
                        </button>
                        <div class="chat-options">
                            <ul>
                                <li style="display: flex; align-items: center;" id="starredMessagesBtn">
                                    <i class="fa-solid fa-star"></i>
                                    <a href="#">الرسايل المميزة بنجمة</a>
                                </li>
                                <hr style="margin: 8px 0; color:#333;">
                                <li id="endChatBtn" style="background: #dc3545; color:#ddd"
                                    data-uuid="{{ $session->uuid }}">
                                    <i class="fa-solid fa-xmark"></i>انهاء المحادثة
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

            <div class="d-flex">
                <input type="text" id="chatSearch" placeholder="🔍 ابحث في الرسائل" class="form-control">
                <button id="searchCount" class="btn btn-outline-secondary text-muted"></button>
                <button id="prevResult" class="btn btn-outline-secondary">⬆</button>
                <button id="nextResult" class="btn btn-outline-secondary">⬇</button>
            </div>

            @php
                $pinnedMessage = $messages->where('is_pinned', true)->first();
            @endphp

            <div class="pinned-messages-container" style="{{ $pinnedMessage ? '' : 'display:none;' }}">
                <div id="pinned-messages">
                    <h6 class="pin-header"><i class="fa-solid fa-thumbtack-slash"></i></h6>
                    <ul class="list-unstyled" id="pinned-list">
                        @if ($pinnedMessage)
                            <li>{{ $pinnedMessage->content }}</li>
                        @endif
                    </ul>
                </div>
            </div>


            <!-- Messages Area -->
            <div class="chat-messages" id="chatMessages">
                <!-- Welcome Message -->
                <div class="message message-agent">
                    <div class="message-bubble agent-bubble">
                        <div class="d-flex align-items-center mb-2">

                        </div>
                        <p class="mb-0">مرحباً! كيف يمكنني مساعدتك اليوم؟</p>
                    </div>
                </div>

                @php
                    $lastUserMessage = $messages->where('sender', 'user')->last();
                @endphp

                <!-- database messages -->
                @foreach ($messages as $msg)
                    @if ($msg->sender === 'agent')
                        <div class="message message-agent" data-message-id="{{ $msg->firebase_id }}"
                            data-db-id="{{ $msg->id }}">
                            <div class="message-bubble agent-bubble">
                                @if ($msg->replyTo)
                                    <div class="reply-block">
                                        <strong>رداً على:</strong>
                                        <div class="quoted-message">
                                            {{ $msg->replyTo->content }}
                                        </div>
                                    </div>
                                @endif
                                <div class="d-flex align-items-center mb-2">
                                </div>
                                @if ($msg->type === 'file' && $msg->media_path)
                                    @if (preg_match('/\.(mp3|wav|ogg|webm)$/i', $msg->media_path))
                                        <audio controls src="{{ $msg->media_path }}"></audio>
                                    @elseif(preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $msg->media_path))
                                        <img src="{{ $msg->media_path }}" class="chat-image">
                                    @else
                                        <a href="{{ $msg->media_path }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download"></i> تحميل الملف
                                        </a>
                                    @endif
                                @else
                                    <p class="message-text mb-0">{{ $msg->content }}</p>
                                @endif
                                <div class="message-time">
                                    <span>{{ \Carbon\Carbon::parse($msg->created_at)->format('h:i A') }}</span>
                                    <div class="message-options">
                                        <button class="options-btn">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                        <ul class="options-menu">
                                            <li class="reply-option"><i class="fa-solid fa-reply"></i>رد</li>
                                            @if ($msg->is_starred === true)
                                                <li class="star-option">
                                                    <i class="fa-solid fa-star" style="color:#6c757d;"></i> ازالة النجمة
                                                </li>
                                            @else
                                                <li class="star-option">
                                                    <i class="fa-solid fa-star" style="color:gold;"></i>تمييز بنجمة
                                                </li>
                                            @endif
                                            <li class="pin-option"><i class="fa-solid fa-thumbtack"></i>تثبيت</li>
                                            <li class="react-option"><i class="fa-solid fa-face-smile"></i>ريأكت</li>
                                        </ul>
                                    </div>
                                    @if ($msg->is_starred)
                                        <div class="star-icon">
                                            <i class="fa-solid fa-star" style="color: gold"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="reactions">
                                    @foreach ($msg->reactions as $reaction)
                                        <span>{{ $reaction->reaction }}</span>
                                    @endforeach
                                </div>
                                <div class="reactions-menu" style="display: none; bottom: 70px; left: 9%;">
                                    <button class="react-btn" data-reaction="👍">👍</button>
                                    <button class="react-btn" data-reaction="❤️">❤️</button>
                                    <button class="react-btn" data-reaction="😮">😮</button>
                                    <button class="react-btn" data-reaction="😂">😂</button>
                                    <button class="react-btn" data-reaction="🥺">🥺</button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="message message-user" data-message-id="{{ $msg->firebase_id }}"
                            data-db-id="{{ $msg->id }}">
                            <div class="message-bubble user-bubble">
                                @if ($msg->replyTo)
                                    <div class="reply-block">
                                        <strong>رداً على:</strong>
                                        <div class="quoted-message">
                                            {{ $msg->replyTo->content }}
                                        </div>
                                    </div>
                                @endif
                                @if ($msg->type === 'file' && $msg->media_path)
                                    @if (preg_match('/\.(mp3|wav|ogg|webm)$/i', $msg->media_path))
                                        <audio controls src="{{ $msg->media_path }}"></audio>
                                    @elseif(preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $msg->media_path))
                                        <img src="{{ $msg->media_path }}" class="chat-image">
                                    @else
                                        <a href="{{ $msg->media_path }}" target="_blank"
                                            class="btn btn-sm btn-outline-light">
                                            <i class="fas fa-download"></i> تحميل الملف
                                        </a>
                                    @endif
                                @else
                                    <p class="message-text mb-0">{{ $msg->content }}</p>
                                @endif
                                <div class="message-time">
                                    <div>
                                        <span>{{ \Carbon\Carbon::parse($msg->created_at)->format('h:i A') }}</span>
                                        @if ($msg->sender === 'user')
                                            @if ($msg->seen)
                                                <span class="seen-status" style="color:chartreuse;">✔✔</span>
                                            @else
                                                <span class="seen-status">✔✔</span>
                                            @endif
                                        @endif
                                    </div>

                                    @if ($msg->is_starred)
                                        <div class="star-icon">
                                            <i class="fa-solid fa-star" style="color: gold"></i>
                                        </div>
                                    @endif

                                    <div class="message-options">
                                        <button class="options-btn">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                        <ul class="options-menu">
                                            <li class="reply-option"><i class="fa-solid fa-reply"></i>رد</li>
                                            @if ($msg->is_starred === true)
                                                <li class="star-option">
                                                    <i class="fa-solid fa-star" style="color:#6c757d;"></i> ازالة
                                                    النجمة
                                                </li>
                                            @else
                                                <li class="star-option">
                                                    <i class="fa-solid fa-star" style="color:gold;"></i>تمييز بنجمة
                                                </li>
                                            @endif
                                            <li class="pin-option"><i class="fa-solid fa-thumbtack"></i>تثبيت</li>
                                            <li class="react-option"><i class="fa-solid fa-face-smile"></i>ريأكت</li>
                                            @if ($lastUserMessage && $msg->id === $lastUserMessage->id)
                                                <li class="edit-btn" data-message-id="{{ $msg->firebase_id }}">
                                                    <i class="fa-solid fa-pencil"></i>تعديل الرسالة
                                                </li>
                                            @endif
                                            <hr>
                                            <li class="delete-btn" data-message-id="{{ $msg->firebase_id }}">
                                                <i class="fa-solid fa-trash"></i>حزف
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="reactions" style="bottom: -5px">
                                    @foreach ($msg->reactions as $reaction)
                                        <span>{{ $reaction->reaction }}</span>
                                    @endforeach
                                </div>
                                <div class="reactions-menu" style="display: none;">
                                    <button class="react-btn" data-reaction="👍">👍</button>
                                    <button class="react-btn" data-reaction="❤️">❤️</button>
                                    <button class="react-btn" data-reaction="😮">😮</button>
                                    <button class="react-btn" data-reaction="😂">😂</button>
                                    <button class="react-btn" data-reaction="🥺">🥺</button>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
                <audio id="typingSound" src="{{ asset('sounds/typing2.mp3') }}"></audio>
            </div>
            <small id="typingIndicator" style="display:none; color:#888; background:white;">يكتب الآن...</small>

            <!-- Send Message Form -->
            <form id="userMessageForm" method="POST" action="{{ route('sendMessageByUser') }}"
                enctype="multipart/form-data">
                @csrf
                <div class="chat-input">
                    <div class="input-group">
                        <input type="hidden" name="replay_to_id" id="replayToId">
                        <input type="hidden" name="uuid" value="{{ $session->uuid }}">
                        <textarea class="form-control" name="message" placeholder="اكتب رسالتك هنا..." rows="1" id="messageInput"
                            style="resize: none;"></textarea>

                        <input type="file" name="file" id="fileInput" class="d-none"
                            accept="image/*,audio/*">
                        <button class="btn" style="background: white;" type="button" id="fileButton">📎</button>
                        <button id="recordBtn" type="button" style="background: white;" class="btn">🎤</button>
                        <button id="stopBtn" type="button" class="btn d-none">⏹️</button>

                        <button class="btn btn-primary" type="submit" id="sendButton">
                            <i class="fas fa-paper-plane"></i></button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Start Starred Sidebar -->
        <div class="starred-panel" id="starredPanel" aria-hidden="true">
            <div class="starred-header d-flex justify-content-between align-items-center">
                <h6 class="m-0">الرسائل المميزة ⭐</h6>
                <button id="closeStarred" class="btn btn-sm btn-light" aria-label="إغلاق">×</button>
            </div>

            <div class="starred-list" id="starredList">

                @if ($starredMessages->isEmpty())
                    <div class="starred-empty">
                        <p>لا توجد رسائل مميزة.</p>
                    </div>
                @else
                    @foreach ($starredMessages as $sm)
                        <div class="starred-item" data-msg-id="{{ $sm->id }}" tabindex="0">
                            <div class="starred-left">
                                <div class="star-avatar">
                                    @if ($sm->sender === 'user')
                                        أنت
                                    @else
                                        الدعم
                                    @endif
                                </div>
                            </div>
                            <div class="starred-body">
                                <div class="starred-text">{{ Str::limit($sm->content, 60) }}</div>
                                <small
                                    class="starred-time">{{ \Carbon\Carbon::parse($sm->created_at)->diffForHumans() }}</small>
                            </div>
                            <div class="starred-right">
                                <button class="btn btn-sm btn-link unstar-btn" data-msg-id="{{ $sm->id }}"
                                    aria-label="إزالة التمييز">
                                    <i class="fa-solid fa-star" style="color:gold"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach

                @endif
            </div>
        </div>
        <!-- End Starred Sidebar -->
    </div>

    <!-- Bootstrap & jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Initialize Firebase
        const firebaseConfig = {
            databaseURL: "{{ env('FIREBASE_DATABASE_URL') }}"
        };

        firebase.initializeApp(firebaseConfig);
        const database = firebase.database();

        document.getElementById('fileButton').addEventListener('click', function() {
            document.getElementById('fileInput').click();
        });

        let lastUserMessageId = null; // store the last user message ID

        document.addEventListener("DOMContentLoaded", function() {
            let chatMessages = document.getElementById("chatMessages");
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            // Listen for new messages in Firebase
            const messagesRef = database.ref('chats/{{ $session->uuid }}/messages');
            messagesRef.on('child_added', (snapshot) => {
                const message = snapshot.val();
                const messageId = snapshot.key;

                if (!message || !message.content || message.content === 'undefined') {
                    console.warn('تم تخطي رسالة غير صالحة:', message);
                    return;
                }

                if (!$(`[data-message-id="${messageId}"]`).length) {
                    $(".message-user .edit-btn").remove();
                    if (message.sender === 'user') {
                        if (lastUserMessageId) {
                            $(`[data-message-id="${lastUserMessageId}"] .edit-btn`).remove();
                        }
                        lastUserMessageId = messageId;
                    }
                    addMessageToChat(message, messageId);
                }

                if (message.sender === 'agent' && document.hasFocus()) {
                    database.ref(`chats/{{ $session->uuid }}/messages/${messageId}`).update({
                        seen: true
                    });

                    $.ajax({
                        url: "{{ route('updateSeen') }}",
                        method: "POST",
                        data: {
                            '_token': "{{ csrf_token() }}",
                            'firebase_id': messageId
                        },
                        success: function(response) {
                            console.log('تم تحديث حالة الرسالة كمقروءة');
                        },
                        error: function(xhr) {
                            console.error('فشل في تحديث حالة الرسالة');
                        }
                    });
                }
            });

            messagesRef.on('child_changed', (snapshot) => {
                const updatedMsg = snapshot.val();
                const msgId = snapshot.key;

                if (updatedMsg.seen && updatedMsg.sender === 'user') {
                    $(`[data-message-id="${msgId}"] .seen-status`).css("color", "chartreuse");
                }
            });

            messagesRef.on('child_removed', (snapshot) => {
                const msgId = snapshot.key;
                $(`[data-message-id="${msgId}"]`).remove();
            });
        });

        function addMessageToChat(message, messageId) {

            let editBtn = '';

            if (message.sender === 'user' && messageId === lastUserMessageId) {
                editBtn = `
                    <li class="edit-btn" data-message-id="${messageId}">
                        <i class="fa-solid fa-pencil"></i> تعديل الرسالة
                    </li>
                `;
            }
            // Check if content exists
            if (!message || !message.content || message.content === 'undefined') {
                console.error('رسالة غير صالحة:', message);
                return;
            }

            let replyHtml = "";
            if (message.replay_to_id && message.replay_content) {
                replyHtml = `
                <div class="reply-block">
                        <strong>رداً على:</strong>
                        <div class="quoted-message">
                            ${message.replay_content}
                        </div>
                    </div>
                `;
            }

            let reactionsHtmlUser = `
                <div class="reactions" style="bottom: -5px">
                    ${message.reactions ? Object.values(message.reactions).map(r => `<span>${r.reaction}</span>`).join('') : ''}
                </div>
                <div class="reactions-menu" style="display: none;">
                    <button class="react-btn" data-reaction="👍">👍</button>
                    <button class="react-btn" data-reaction="❤️">❤️</button>
                    <button class="react-btn" data-reaction="😮">😮</button>
                    <button class="react-btn" data-reaction="😂">😂</button>
                    <button class="react-btn" data-reaction="🥺">🥺</button>
                </div>`;

            let reactionsHtmlAgent = `
                <div class="reactions">
                    ${message.reactions ? Object.values(message.reactions).map(r => `<span>${r.reaction}</span>`).join('') : ''}
                </div>
                <div class="reactions-menu" style="display: none; bottom: 70px; left: 9%;">
                    <button class="react-btn" data-reaction="👍">👍</button>
                    <button class="react-btn" data-reaction="❤️">❤️</button>
                    <button class="react-btn" data-reaction="😮">😮</button>
                    <button class="react-btn" data-reaction="😂">😂</button>
                    <button class="react-btn" data-reaction="🥺">🥺</button>
                </div>`;

            let messageHtml = "";

            if (message.sender === 'user') {
                // رسالة من المستخدم
                if (message.type === "file" && message.media_path) {
                    if (/\.(mp3|wav|ogg|webm)$/i.test(message.media_path)) {
                        messageHtml = `
                <div class="message message-user" data-message-id="${messageId}">
                    <div class="message-bubble user-bubble">
                    ${replyHtml}
                        <audio controls src="${message.media_path}"></audio>
                        <div class="message-time">
                            <div>
                                <span>${message.time}</span>
                                ${message.seen
                                ? '<span class="seen-status" style="color:chartreuse;">✔✔</span>'
                                : '<span class="seen-status">✔✔</span>'}
                                </div>
                            <div class="message-options">
                                        <button class="options-btn">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                        <ul class="options-menu">
                                            <li class="reply-option"><i class="fa-solid fa-reply"></i>رد</li>
                                            <li class="star-option"><i class="fa-solid fa-star"></i>تمييز بنجمة </li>
                                            <li class="pin-option"><i class="fa-solid fa-thumbtack"></i>تثبيت</li>
                                            <li class="react-option"><i class="fa-solid fa-face-smile"></i>ريأكت</li>
                                            <hr />
                                            <li class="delete-btn" data-message-id="${messageId}">
                                                <i class="fa-solid fa-trash"></i>حزف
                                            </li>
                                        </ul>
                                    </div>
                        </div>
                    </div>
                </div>`;
                    } else if (/\.(jpg|jpeg|png|gif|webp)$/i.test(message.media_path)) {
                        messageHtml = `
                <div class="message message-user" data-message-id="${messageId}">
                    <div class="message-bubble user-bubble">
                        ${replyHtml}
                        <img src="${message.media_path}" class="chat-image"/>
                        <div class="message-time">
                            <div>
                                <span>${message.time}</span>
                                ${message.seen
                                ? '<span class="seen-status" style="color:chartreuse;">✔✔</span>'
                                : '<span class="seen-status">✔✔</span>'}
                                </div>
                            <div class="message-options">
                                        <button class="options-btn">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                        <ul class="options-menu">
                                            <li class="reply-option"><i class="fa-solid fa-reply"></i>رد</li>
                                            <li class="star-option"><i class="fa-solid fa-star"></i>تمييز بنجمة </li>
                                            <li class="pin-option"><i class="fa-solid fa-thumbtack"></i>تثبيت</li>
                                            <li class="react-option"><i class="fa-solid fa-face-smile"></i>ريأكت</li>
                                            <hr />
                                            <li class="delete-btn" data-message-id="${messageId}">
                                                <i class="fa-solid fa-trash"></i>حزف
                                            </li>
                                        </ul>
                                    </div>
                            </div>
                    </div>
                </div>`;
                    } else {
                        messageHtml = `
                <div class="message message-user" data-message-id="${messageId}">
                    <div class="message-bubble user-bubble">
                        ${replyHtml}
                        <a href="${message.media_path}" target="_blank" class="btn btn-sm btn-outline-light">
                            <i class="fas fa-download"></i> تحميل الملف
                        </a>
                        <div class="message-time">
                            <div>
                                <span>${message.time}</span>
                                ${message.seen
                                ? '<span class="seen-status" style="color:chartreuse;">✔✔</span>'
                                : '<span class="seen-status">✔✔</span>'}
                                </div>
                            <div class="message-options">
                                        <button class="options-btn">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                        <ul class="options-menu">
                                            <li class="reply-option"><i class="fa-solid fa-reply"></i>رد</li>
                                            <li class="star-option"><i class="fa-solid fa-star"></i>تمييز بنجمة </li>
                                            <li class="pin-option"><i class="fa-solid fa-thumbtack"></i>تثبيت</li>
                                            <li class="react-option"><i class="fa-solid fa-face-smile"></i>ريأكت</li>
                                            <hr />
                                            <li class="delete-btn" data-message-id="${messageId}">
                                                <i class="fa-solid fa-trash"></i>حزف
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    }
                } else {
                    // نص
                    messageHtml = `
            <div class="message message-user" data-message-id="${messageId}">
                <div class="message-bubble user-bubble">
                    ${replyHtml}
                    ${message.edited ? '<p class="mb-0"><small>(تم التعديل)</small></p>' : ''}
                    <p class="message-text mb-0">${message.content}</p>
                    <div class="message-time">
                        <div>
                                <span>${message.time}</span>
                                ${message.seen
                                ? '<span class="seen-status" style="color:chartreuse;">✔✔</span>'
                                : '<span class="seen-status">✔✔</span>'}
                                </div>
                            <div class="message-options">
                                        <button class="options-btn">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                        <ul class="options-menu">
                                            <li class="reply-option"><i class="fa-solid fa-reply"></i>رد</li>
                                            <li class="star-option"><i class="fa-solid fa-star"></i>تمييز بنجمة </li>
                                            <li class="pin-option"><i class="fa-solid fa-thumbtack"></i>تثبيت</li>
                                            <li class="react-option"><i class="fa-solid fa-face-smile"></i>ريأكت</li>
                                            ${editBtn}
                                            <hr />
                                            <li class="delete-btn" data-message-id="${messageId}">
                                                <i class="fa-solid fa-trash"></i>حزف
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                    ${reactionsHtmlUser}
                            </div>
                        </div>`;
                }
            } else {
                // agent message
                if (message.type === "file" && message.media_path) {
                    if (/\.(mp3|wav|ogg|webm)$/i.test(message.media_path)) {
                        messageHtml = `
                <div class="message message-agent" data-message-id="${messageId}">
                    <div class="message-bubble agent-bubble">
                        ${replyHtml}
                        <div class="d-flex align-items-center mb-2">

                        </div>
                        <audio controls src="${message.media_path}"></audio>
                        <div class="message-time">
                            <span>${message.time}</span>
                            <div class="message-options">
                                        <button class="options-btn">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                        <ul class="options-menu">
                                            <li class="reply-option"><i class="fa-solid fa-reply"></i>رد</li>
                                            <li class="star-option"><i class="fa-solid fa-star"></i>تمييز بنجمة </li>
                                            <li class="pin-option"><i class="fa-solid fa-thumbtack"></i>تثبيت</li>
                                            <li class="react-option"><i class="fa-solid fa-face-smile"></i>ريأكت</li>
                                        </ul>
                                    </div>
                            </div>
                    </div>
                </div>`;
                    } else if (/\.(jpg|jpeg|png|gif|webp)$/i.test(message.media_path)) {
                        messageHtml = `
                <div class="message message-agent" data-message-id="${messageId}">
                    <div class="message-bubble agent-bubble">
                        ${replyHtml}
                        <div class="d-flex align-items-center mb-2">

                        </div>
                        <img src="${message.media_path}" class="chat-image"/>
                        <div class="message-time">
                            <span>${message.time}</span>
                            <div class="message-options">
                                        <button class="options-btn">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                        <ul class="options-menu">
                                            <li class="reply-option"><i class="fa-solid fa-reply"></i>رد</li>
                                            <li class="star-option"><i class="fa-solid fa-star"></i>تمييز بنجمة </li>
                                            <li class="pin-option"><i class="fa-solid fa-thumbtack"></i>تثبيت</li>
                                            <li class="react-option"><i class="fa-solid fa-face-smile"></i>ريأكت</li>
                                        </ul>
                                    </div>
                            </div>
                    </div>
                </div>`;
                    } else {
                        messageHtml = `
                <div class="message message-agent" data-message-id="${messageId}">
                    <div class="message-bubble agent-bubble">
                        ${replyHtml}
                        <div class="d-flex align-items-center mb-2">

                        </div>
                        <a href="${message.media_path}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-download"></i> تحميل الملف
                        </a>
                        <div class="message-time">
                            <span>${message.time}</span>
                            <div class="message-options">
                                        <button class="options-btn">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                        <ul class="options-menu">
                                            <li class="reply-option"><i class="fa-solid fa-reply"></i>رد</li>
                                            <li class="star-option"><i class="fa-solid fa-star"></i>تمييز بنجمة </li>
                                            <li class="pin-option"><i class="fa-solid fa-thumbtack"></i>تثبيت</li>
                                            <li class="react-option"><i class="fa-solid fa-face-smile"></i>ريأكت</li>
                                        </ul>
                                    </div>
                            </div>
                    </div>
                </div>`;
                    }
                } else {
                    // text
                    messageHtml = `
            <div class="message message-agent" data-message-id="${messageId}">
                <div class="message-bubble agent-bubble">
                    ${replyHtml}
                    <div class="d-flex align-items-center mb-2">

                    </div>
                    <p class="message-text mb-0">${message.content}</p>
                    <div class="message-time">
                        <span>${message.time}</span>
                        <div class="message-options">
                                        <button class="options-btn">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                        <ul class="options-menu">
                                            <li class="reply-option"><i class="fa-solid fa-reply"></i>رد</li>
                                            <li class="star-option"><i class="fa-solid fa-star"></i>تمييز بنجمة </li>
                                            <li class="pin-option"><i class="fa-solid fa-thumbtack"></i>تثبيت</li>
                                            <li class="react-option"><i class="fa-solid fa-face-smile"></i>ريأكت</li>
                                        </ul>
                                    </div>
                        </div>
                    ${reactionsHtmlAgent}
                </div>
            </div>`;
                }
            }

            $("#chatMessages").append(messageHtml);
            $("#chatMessages").scrollTop($("#chatMessages")[0].scrollHeight);
        }

        $("#messageInput").on("keypress", function(e) {
            if (e.which === 13 && !e.shiftKey) {
                e.preventDefault();
                $("#userMessageForm").submit();
            }
        });

        $(document).ready(function() {
            $("#userMessageForm").on("submit", function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    type: "POST",
                    url: $(this).attr("action"),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $("#messageInput").val("");
                        $("#fileInput").val("");
                        $("#replayToId").val("");
                        $("#replyPreview").remove();
                    },
                    error: function(xhr) {
                        console.error("خطأ:", xhr.responseText);
                        alert("حصل خطأ أثناء إرسال الرسالة");
                    }
                });
            });


            let mediaRecorder;
            let audioChunks = [];
            document.getElementById("recordBtn").addEventListener("click", async () => {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({
                        audio: true
                    });
                    mediaRecorder = new MediaRecorder(stream);

                    mediaRecorder.start();
                    audioChunks = [];

                    mediaRecorder.addEventListener("dataavailable", event => {
                        audioChunks.push(event.data);
                    });

                    mediaRecorder.addEventListener("stop", () => {
                        const audioBlob = new Blob(audioChunks, {
                            type: "audio/webm"
                        });
                        const file = new File([audioBlob], "recording.webm", {
                            type: "audio/webm"
                        });

                        // intialize form and send data to laravel
                        let formData = new FormData();
                        formData.append("_token", "{{ csrf_token() }}");
                        formData.append("uuid", "{{ $session->uuid }}");
                        formData.append("file", file);

                        fetch("{{ route('sendMessageByUser') }}", {
                            method: "POST",
                            body: formData
                        }).then(res => res.json()).then(data => {
                            console.log("تم رفع التسجيل:", data);
                        });
                    });

                    // switch between start recording and stop recording
                    document.getElementById("recordBtn").classList.add("d-none");
                    document.getElementById("stopBtn").classList.remove("d-none");

                } catch (error) {
                    alert("لا يمكن الوصول للمايكروفون!");
                    console.error(error);
                }
            });

            document.getElementById("stopBtn").addEventListener("click", () => {
                mediaRecorder.stop();
                document.getElementById("recordBtn").classList.remove("d-none");
                document.getElementById("stopBtn").classList.add("d-none");
            });
        });

        const sessionRef = database.ref('sessions/{{ $session->uuid }}');
        sessionRef.on('value', snapshot => {
            const data = snapshot.val();
            if (data && data.agent_name) {
                document.getElementById('agentNameValue').innerHTML =
                data.agent_name + '<br><span style="color: white; font-size: smaller;">Available</span>';
            } else {
                document.getElementById('agentNameValue').textContent = 'جار انتظار الممثل...';
            }
        });
    </script>

    {{-- Close Chat --}}
    <script>
        $(document).ready(function() {
            let agentId = null;
            let sessionId = null;
            $("#endChatBtn").on("click", function(e) {
                e.preventDefault();
                let uuid = $(this).data("uuid");

                $.ajax({
                    type: "POST",
                    url: "{{ route('endSession') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        uuid: uuid
                    },
                    success: function(response) {
                        agentId = response.agent_id;
                        sessionId = response.session_id;

                        // نحول المستخدم مع الـ params
                        window.location.href = `/rate-user/${sessionId}/${agentId}`;
                    },
                    error: function(xhr) {
                        alert("حصل خطأ أثناء إنهاء المحادثة");
                    }
                });
            });

            const database = firebase.database();
            const messagesRef = database.ref('chats/{{ $session->uuid }}/messages');
            const currentUserType = "user";

            let inactivityTimer, countdownTimer;
            const INACTIVITY_LIMIT = 1 * 60 * 1000; // دقيقة واحدة
            const COUNTDOWN_TIME = 10; // العد التنازلي قبل التحذير
            let uuid = $("#endChatBtn").data("uuid");
            let countdownValue = COUNTDOWN_TIME;
            let warningCount = 0; // عدد المحاولات (هيكون 0 أو 1)
            const MAX_WARNINGS = 2; // بعد المحاولتين يقفل السيشن
            const ALERT_TIMEOUT = 30 * 1000;

            const countdownBox = $('<div id="countdown-box"></div>').appendTo("body");

            function autoEndSession() {
                $.ajax({
                    type: "POST",
                    url: "{{ route('endSession') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        uuid: uuid
                    },
                    success: function(response) {
                        if (!sessionId || !agentId) {
                            sessionId = response.session_id;
                            agentId = response.agent_id;
                        }
                        window.location.href = `/rate-user/${sessionId}/${agentId}`;
                    },
                    error: function() {
                        console.error("خطأ أثناء إنهاء المحادثة");
                    }
                });
            }

            function playBeep() {
                const audioCtx = new(window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioCtx.createOscillator();
                const gainNode = audioCtx.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioCtx.destination);

                oscillator.type = "sine";
                oscillator.frequency.setValueAtTime(800, audioCtx.currentTime); // التردد
                gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime); // مستوى الصوت

                oscillator.start();
                oscillator.stop(audioCtx.currentTime + 0.1); // صوت قصير
            }

            function showCountdown(beforeAutoEnd = false) {
                countdownValue = COUNTDOWN_TIME;
                countdownBox.text(` ${beforeAutoEnd ? "" : ""} ${countdownValue} `).fadeIn();

                countdownTimer = setInterval(() => {
                    countdownValue--;
                    countdownBox.text(`${beforeAutoEnd ? "" : ""} ${countdownValue} `);
                    playBeep();

                    if (countdownValue <= 0) {
                        clearInterval(countdownTimer);
                        countdownBox.fadeOut();

                        if (beforeAutoEnd) {
                            autoEndSession(); // في المحاولة الثانية، يقفل السيشن بدون Alert
                        } else {
                            showInactivityWarning(); // في المحاولة الأولى، نعرض Alert
                        }
                    }
                }, 1000);
            }

            function showInactivityWarning() {
                warningCount++;
                Swal.fire({
                    title: 'هل ترغب في الاستمرار بالمحادثة؟',
                    text: 'لم ترسل أي رسالة منذ فترة',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'نعم، أكمل المحادثة',
                    cancelButtonText: 'لا، أنهيها',
                    reverseButtons: true
                }).then((result) => {
                    clearTimeout(autoCloseTimer);
                    if (result.isConfirmed) {
                        resetTimer();
                    } else {
                        autoEndSession();
                    }
                });
                autoCloseTimer = setTimeout(() => {
                    Swal.close(); // نقفل التحذير
                    autoEndSession();
                }, ALERT_TIMEOUT);
            }

            function resetTimer() {
                clearTimeout(inactivityTimer);
                clearInterval(countdownTimer);
                countdownBox.fadeOut();

                if (warningCount < MAX_WARNINGS - 1) {
                    inactivityTimer = setTimeout(() => showCountdown(false), INACTIVITY_LIMIT - (COUNTDOWN_TIME *
                        1000));
                } else if (warningCount === MAX_WARNINGS - 1) {
                    inactivityTimer = setTimeout(() => showCountdown(true), INACTIVITY_LIMIT - (COUNTDOWN_TIME *
                        1000));
                }
            }

            // resetTimer();

            let lastMessageSender = null;
            messagesRef.limitToLast(1).on('child_added', snapshot => {
                const message = snapshot.val();
                lastMessageSender = message.sender;

                // لو آخر رسالة من الـ agent → نبدأ التايمر
                if (lastMessageSender === 'agent') {
                    console.log('آخر رسالة من agent → تشغيل التايمر');
                    resetTimer();
                } else {
                    // لو آخر رسالة من user → نوقف التايمر
                    console.log('آخر رسالة من user → إيقاف التايمر');
                    clearTimeout(inactivityTimer);
                    clearInterval(countdownTimer);
                    countdownBox.fadeOut();
                }
            });

            $("#sendButton").on("click", () => {
                warningCount = 0;
                resetTimer();
            });
            $("#messageInput").on("keypress", function(e) {
                if (e.which === 13 && !e.shiftKey) {
                    warningCount = 0;
                    resetTimer();
                }
            });
        });
    </script>

    {{-- Reply --}}
    <script>
        $(document).on("click", ".reply-option", function() {
            let messageId = $(this).closest(".message").data("message-id");
            $("#replayToId").val(messageId);

            let messageText = $(this).closest(".message").find(".message-text").text();
            $(".chat-input").prepend(`
                <div class="reply-preview" id="replyPreview">
                    <small>الرد على: ${messageText}</small>
                    <button type="button" id="cancelReply">×</button>
                </div>
            `);
        });

        $(document).on("click", "#cancelReply", function() {
            $("#replayToId").val("");
            $("#replyPreview").remove();
        });
    </script>

    {{-- Update Message --}}
    <script>
        $(document).on('click', '.edit-btn', function() {
            let messageId = $(this).data("message-id"); // get firebase_id from this message
            let messageDiv = $(`[data-message-id="${messageId}"] .message-text`); // catch p tag for the message
            let oldText = messageDiv.text().trim(); // get the text of message

            // change p --> input
            messageDiv.replaceWith(`
            <div class="edit-container">
                <input type="text" class="edit-input" value="${oldText}" />
                <button class="save-edit-btn btn btn-sm btn-success" data-message-id="${messageId}">💾</button>
                <button class="cancel-edit-btn btn btn-sm btn-secondary" data-message-id="${messageId}">❌</button>
            </div>
        `);
        });

        //  update message
        $(document).on("click", ".save-edit-btn", function() {
            let messageId = $(this).data("message-id");
            let container = $(`[data-message-id="${messageId}"] .edit-container`);
            let newContent = container.find(".edit-input").val();

            // Update in Firebase
            database.ref(`chats/{{ $session->uuid }}/messages/${messageId}`).update({
                content: newContent,
                edited: true
            });

            // Update in MySQL
            $.ajax({
                url: "{{ route('updateMessage') }}",
                method: "POST",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'firebase_id': messageId,
                    'content': newContent
                },
                success: function() {
                    container.replaceWith(
                        `<p class="mb-0">${newContent} <small>(تم التعديل)</small></p>`);
                },
                error: function() {
                    alert("حصل خطأ أثناء تعديل الرسالة");
                }
            });
        });

        // cancel edit
        $(document).on("click", ".cancel-edit-btn", function() {
            let messageId = $(this).data("message-id");
            let container = $(`[data-message-id="${messageId}"] .edit-container`);
            let oldText = container.find(".edit-input").val();

            container.replaceWith(`<p class="mb-0">${oldText}</p>`);
        });
    </script>

    {{-- Delete User Message --}}
    <script>
        $(document).on('click', ".delete-btn",
            function() { // this document when i click on any element have class delete-btn
                let messageId = $(this).data('message-id'); // get value of this element have property data-message-id

                $.ajax({
                    url: "{{ route('deleteMessage') }}", // route required
                    method: 'POST', // method type required
                    data: { // form data
                        '_token': "{{ csrf_token() }}", // @csrf required
                        'id': messageId,
                        'session_uuid': "{{ $session->uuid }}",
                    },
                    success: function(response) {
                        if (response.success) {
                            $(`[data-message-id="${messageId}"]`).remove();
                        } else {
                            alert(response.error)
                        }
                    }
                })
            });
    </script>

    {{-- Typing Section --}}
    <script>
        const typingRef = database.ref('chats/{{ $session->uuid }}/typing');
        const typingIndicator = document.getElementById("typingIndicator");
        const messageInput = document.getElementById("messageInput");
        const typingSound = document.getElementById("typingSound");
        let typingTimeout;

        // user typing
        messageInput.addEventListener("input", function() {
            typingRef.set({
                sender: "user",
                typing: messageInput.value.trim() !== ""
            });

            clearTimeout(typingTimeout);
            typingTimeout = setTimeout(() => {
                typingRef.set({
                    sender: "user",
                    typing: false
                });
            }, 500);
        });

        // listening for agent typing status
        typingRef.on("value", (snapshot) => {
            const data = snapshot.val();
            if (!data) return;

            if (data.sender === "agent" && data.typing) {
                typingIndicator.innerText = "يكتب الآن...";
                typingIndicator.style.display = "block";

                if (typingSound.paused) {
                    typingSound.play().catch(() => {})
                }
            } else {
                typingIndicator.style.display = "none";
                typingSound.pause();
                typingSound.currentTime = 0;
            }
        });
    </script>

    {{-- Search About Message --}}
    <script>
        let searchResults = [];
        let currentIndex = -1;

        $(document).on("input", "#chatSearch", function() {
            const searchText = $(this).val().toLowerCase();
            searchResults = [];
            currentIndex = -1;

            // امسح أي هايلايت قديم
            $(".message-bubble").each(function() {
                const originalText = $(this).data("original-text") || $(this).html();
                $(this).data("original-text", originalText);
                $(this).html(originalText);
            });

            if (searchText.length > 0) {
                $(".message-bubble").each(function() {
                    const text = $(this).text().toLowerCase();

                    if (text.includes(searchText)) {
                        const regex = new RegExp(`(${searchText})`, "gi");
                        const highlighted = $(this).html().replace(regex,
                            `<mark class="highlighted">$1</mark>`);
                        $(this).html(highlighted);

                        searchResults.push($(this).closest(".message"));
                    }
                });

                if (searchResults.length > 0) {
                    currentIndex = 0;
                    scrollToResult(currentIndex);
                }
            }

            updateSearchCount();
        });

        function scrollToResult(index) {
            if (index >= 0 && index < searchResults.length) {
                const element = searchResults[index];
                $('#chatMessages').animate({
                    scrollTop: element.offset().top - 150
                }, 300);

                $(".highlighted").css("background", "yellow"); // reset
                element.find(".highlighted").css("background", "orange"); // active
            }
            updateSearchCount();
        }

        function updateSearchCount() {
            if (searchResults.length === 0 || $("#chatSearch").val().trim() === "") {
                $("#searchCount").text("").hide();
            } else {
                $("#searchCount").text((currentIndex + 1) + "/" + searchResults.length).show();
            }
        }

        $("#nextResult").on("click", function() {
            if (searchResults.length > 0) {
                currentIndex = (currentIndex + 1) % searchResults.length;
                scrollToResult(currentIndex);
            }
        });

        $("#prevResult").on("click", function() {
            if (searchResults.length > 0) {
                currentIndex = (currentIndex - 1 + searchResults.length) % searchResults.length;
                scrollToResult(currentIndex);
            }
        });
    </script>

    {{-- Message Options --}}
    <script>
        $(document).on("click", ".options-btn", function(e) {
            e.stopPropagation();
            $(".options-menu").hide();
            $(this).siblings(".options-menu").toggle();
        });

        $(document).on("click", function() {
            $(".options-menu").hide();
        });

        $(document).on("click", ".reply-option", function() {
            let messageText = $(this).closest(".message-bubble").find(".message-text").text();
        });
    </script>

    {{-- Star Message --}}
    <script>
        $(document).on("click", ".star-option", function() {
            let messageDiv = $(this).closest(".message");
            let messageId = messageDiv.data("db-id");
            let messageContent = messageDiv.find(".message-text").text().trim();
            let sender = messageDiv.hasClass("user-message") ? "أنت" : "الدعم";
            let messageTime = messageDiv.find(".message-time small").text();

            $.ajax({
                url: "{{ route('toggleStarMessage') }}",
                method: "POST",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'id': messageId
                },
                success: function(response) {
                    let messageDiv = $(`[data-db-id="${messageId}"]`);

                    if (response.is_starred) {
                        messageDiv.find(".star-icon").remove();
                        messageDiv.find(".message-time").append(`
                        <div class="star-icon">
                            <i class="fa-solid fa-star" style="color: gold"></i>
                        </div>
                    `);

                        $("#starredList").prepend(`
                        <div class="starred-item" data-msg-id="${messageId}" tabindex="0">
                            <div class="starred-left">
                                <div class="star-avatar">${sender}</div>
                            </div>
                            <div class="starred-body">
                                <div class="starred-text">${messageContent.substring(0, 60)}</div>
                                <small class="starred-time">${messageTime}</small>
                            </div>
                            <div class="starred-right">
                                <button class="btn btn-sm btn-link unstar-btn"
                                data-msg-id="${messageId}" aria-label="إزالة التمييز">
                                    <i class="fa-solid fa-star" style="color:gold"></i>
                                </button>
                            </div>
                        </div>
                    `);

                        $(".starred-empty").remove();

                    } else {
                        messageDiv.find(".star-icon").remove();

                        $(`#starredList .starred-item[data-msg-id="${messageId}"]`).remove();

                        if ($("#starredList .starred-item").length === 0) {
                            $("#starredList").html(`
                            <div class="starred-empty">
                                <p>لا توجد رسائل مميزة.</p>
                            </div>
                        `);
                        }
                    }
                },
                error: function() {
                    alert('حدث خطأ اثناء تمييز الرسالة بنجمة')
                }
            });
        });
    </script>

    {{-- Pinned Message --}}
    <script>
        $(document).on("click", ".pin-option", function() {
            let messageDiv = $(this).closest(".message");
            let messageId = messageDiv.data("message-id");

            $.ajax({
                url: "{{ route('togglePinMessage') }}",
                method: "POST",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'id': messageId,
                },
                success: function(response) {
                    if (response.is_pinned) {
                        // عرض الكونتينر
                        $(".pinned-messages-container").show();

                        // مسح أي رسالة قديمة
                        $("#pinned-list").empty();

                        // إضافة الرسالة الجديدة
                        $("#pinned-list").append(`<li>${response.content}</li>`);
                    } else {
                        // لو الرسالة اتفكت → نخفي الكونتينر
                        $(".pinned-messages-container").hide();
                        $("#pinned-list").empty();
                    }
                },
                error: function() {
                    alert('حدث خطأ أثناء تثبيت الرسالة');
                }
            });
        });


        $(document).on("click", ".pin-header", function() {
            let sessionId = "{{ $session->id }}";

            $.ajax({
                url: "{{ route('removePinMessage') }}",
                method: "POST",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'session_id': sessionId
                },
                success: function(response) {
                    if (response.success) {
                        $(".pinned-messages-container").hide();
                        $("#pinned-list").empty();
                    }
                },
                error: function() {
                    alert("حصل خطأ أثناء إزالة التثبيت");
                }
            })

        });
    </script>

    {{-- React Message --}}
    <script>
        $(document).on("click", ".react-option", function() {
            let menu = $(this).closest(".message-bubble").find(".reactions-menu").show();
        });

        $(document).on("click", ".react-btn", function(e) {
            let messageId = $(this).closest(".message").data("message-id"); // firebase_id
            let reaction = $(this).data("reaction"); // reaction emoji
            let menu = $(this).closest(".reactions-menu").hide();

            $.ajax({
                url: "{{ route('messages.react') }}",
                method: "POST",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'firebase_id': messageId,
                    'reaction': reaction
                },
                success: function(response) {
                    let reactionsContainer = $(`.message[data-message-id="${messageId}"] .reactions`);
                    if (response.status === "added") {
                        reactionsContainer.html(`<span>${response.reaction}</span>`);
                    } else if (response.status === "replaced") {
                        reactionsContainer.html(`<span>${response.reaction}</span>`);
                    } else if (response.status === "removed") {
                        $(`.message[data-message-id="${messageId}"] .reactions span`).remove();
                    }
                },
            });
        });

        $(document).on("click", function(e) {
            if (!$(e.target).closest(".reactions-menu, .react-option").length) {
                $(".reactions-menu").hide();
            }
        });
    </script>

    {{-- chat options --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const toggleBtn = document.getElementById("menuToggle");
            const menu = document.querySelector(".chat-options");

            toggleBtn.addEventListener("click", (e) => {
                e.stopPropagation();
                menu.style.display = (menu.style.display === "block") ? "none" : "block";
            });

            // يقفل المنيو لو دوست برة
            document.addEventListener("click", () => {
                menu.style.display = "none";
            });
        });
    </script>

    {{-- Starred Messages Panel --}}
    <script>
        $(document).ready(function() {
            // Button to open starred messages panel
            $(document).on("click", "#starredMessagesBtn", function(e) {
                e.preventDefault();

                // Add the panel to container-fluid if not already there
                if (!$("#starredPanel").parent().hasClass("container-fluid")) {
                    $(".container-fluid").append($("#starredPanel"));
                }

                $("#starredPanel").fadeIn().attr("aria-hidden", "false");
            });

            // Button to close starred messages panel
            $(document).on("click", "#closeStarred", function() {
                $("#starredPanel").fadeOut().attr("aria-hidden", "true");
            });

            // Close panel when clicking outside
            $(document).on("click", function(e) {
                if (
                    $("#starredPanel").is(":visible") &&
                    !$(e.target).closest("#starredPanel, #starredMessagesBtn").length
                ) {
                    $("#starredPanel").fadeOut().attr("aria-hidden", "true");
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".starred-item").forEach(function(item) {
                item.addEventListener("click", function() {
                    let msgId = this.getAttribute('data-msg-id'); // star message id

                    let targetMessage = document.querySelector(
                        '.message[data-db-id="' + msgId + '"]'
                    );

                    if (targetMessage) {
                        targetMessage.scrollIntoView({
                            behavior: "smooth",
                            block: "center"
                        });

                        targetMessage.classList.add("highlighted");
                        setTimeout(() => {
                            targetMessage.classList.remove("highlighted");
                        }, 2000);
                    }
                })
            });

            const urlParams = new URLSearchParams(window.location.search);
            const focusMsgId = urlParams.get("focusMsg");

            if (focusMsgId) {
                let targetMessage = document.querySelector(`.message[data-db-id="${focusMsgId}"]`);

                if (targetMessage) {
                    targetMessage.scrollIntoView({
                        behavior: "smooth",
                        block: "center",
                    });

                    targetMessage.classList.add('highlighted');

                    setTimeout(() => {
                        targetMessage.classList.remove('highlighted');
                    }, 2000);
                }
            }
        })
    </script>

    {{-- Hide and Show Sidebar sessions --}}
    <script>
        $(document).on("click", "#close-sidebar", function() {
            // hide sidebar smoothly
            $('.sessions-sidebar').addClass('hidden');

            // animate icons
            $('#close-sidebar').addClass('hidden');
            setTimeout(() => {
                $('#close-sidebar').addClass('d-none');
                $('#open-sidebar').removeClass('d-none').removeClass('hidden');
            }, 300); // wait for fade
        });

        $(document).on("click", "#open-sidebar", function() {
            // show sidebar smoothly
            $('.sessions-sidebar').removeClass('hidden');

            // animate icons
            $('#open-sidebar').addClass('hidden');
            setTimeout(() => {
                $('#open-sidebar').addClass('d-none');
                $('#close-sidebar').removeClass('d-none').removeClass('hidden');
            }, 300); // wait for fade
        });
    </script>

</body>

</html>
