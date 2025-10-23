<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Chat</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Firebase App SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-database-compat.js"></script>
    <!-- Css Style -->
    <link rel="stylesheet" href="{{ asset('assets/css/agent_chat.css') }}">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    #countdown-box {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: transparent;
        color: #dc3545;
        font-size: 120px;
        font-weight: bold;
        text-align: center;
        z-index: 9999;
        animation: scaleUp 1s ease-in-out infinite;
        display: none;
        user-select: none;
    }

    @keyframes scaleUp {
        0% {
            transform: translate(-50%, -50%) scale(0.8);
            opacity: 0.8;
        }

        50% {
            transform: translate(-50%, -50%) scale(1.2);
            opacity: 1;
        }

        100% {
            transform: translate(-50%, -50%) scale(1);
            opacity: 0.9;
        }
    }
</style>

<body>
    <div class="modal fade" id="transferModal" tabindex="-1" aria-labelledby="transferModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">اختر الإدارة لتحويل الجلسة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body">
                    <select id="departmentSelect" class="form-select">
                        <option value="">اختر الإدارة...</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" id="confirmTransfer" class="btn btn-primary">تأكيد التحويل</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid p-0">
        <div class="chat-container">
            <!-- Header -->
            <div class="chat-header">
                <div class="d-flex align-items-center" style="justify-content: space-between;">
                    <div>
                        <button class="btn btn-light" id="endChatBtn">
                             <i class="fa-solid fa-xmark fa-lg"></i> </button>
                        <button id="transferSessionBtn" data-uuid="{{ $session->uuid }}" class="btn btn-light"><i
                                class="fa-solid fa-repeat"></i></button>
                    </div>
                    <div>
                        <h5 class="mb-0"><i class="fas fa-headset me-2"></i>{{ $username }}</h5>
                    </div>
                </div>
            </div>

            <!-- Messages Area -->
            <div class="chat-messages" id="chatMessages">
                <!-- رسائل من الداتابيز -->
                @foreach ($messages as $msg)
                    @if ($msg->sender === 'user')
                        <div class="message message-agent" data-message-id="{{ $msg->firebase_id }}">
                            <div class="message-bubble agent-bubble">
                                @if ($msg->replyTo)
                                    <div class="reply-block">
                                        <strong>رداً على:</strong>
                                        <div class="quoted-message">
                                            {{ $msg->replyTo->content }}
                                        </div>
                                    </div>
                                @endif
                                @if ($msg->type === 'file' && $msg->media_path)
                                    @if (preg_match('/\.(mp3|wav|ogg)$/i', $msg->media_path))
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
                                    @if ($msg->edited)
                                        <p class="mb-0"> <small>معدلة*</small> </p>
                                    @endif
                                    <p class="mb-0 message-text">{{ $msg->content }}</p>
                                @endif
                                <div class="message-time">
                                    <div class="message-options">
                                        <button class="options-btn">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                        <ul class="options-menu">
                                            <li class="reply-option"><i class="fa-solid fa-reply"></i>رد</li>
                                            <li class="star-option"> <i class="fa-solid fa-star"></i>تمييز بنجاح </li>
                                            <li class="pin-option"> <i class="fa-solid fa-thumbtack"></i>تثبيت</li>
                                            <li class="react-option"><i class="fa-solid fa-face-smile"></i>ريأكت</li>
                                        </ul>
                                    </div>
                                    <span>{{ \Carbon\Carbon::parse($msg->created_at)->format('h:i A') }}</span>
                                </div>
                                <div class="reactions">
                                    @foreach ($msg->reactions as $reaction)
                                        <span>{{ $reaction->reaction }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="message message-user" data-message-id="{{ $msg->id }}">
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
                                    @if (preg_match('/\.(mp3|wav|ogg)$/i', $msg->media_path))
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
                                    <p class="mb-0">{{ $msg->content }}</p>
                                @endif
                                <div class="message-time">
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
                                    <span>{{ \Carbon\Carbon::parse($msg->created_at)->format('h:i A') }}</span>
                                </div>
                                <div class="reactions">
                                    @foreach ($msg->reactions as $reaction)
                                        <span>{{ $reaction->reaction }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
                <audio id="typingSound" src="{{ asset('sounds/typing2.mp3') }}"></audio>
            </div>
            <small id="typingIndicator" style="display:none; color:#888;">يكتب الآن...</small>

            <!-- Input -->
            <form id="agentMessageForm" method="POST" action="{{ route('replayByAgent') }}">
                @csrf
                <div class="chat-input">
                    <div class="input-group">
                        <input type="hidden" name="replay_to_id" id="replayToId">
                        <input type="hidden" name="uuid" value="{{ $session->uuid }}">

                        <textarea class="form-control" name="message" placeholder="اكتب رسالتك هنا..." rows="1" id="messageInput"
                            style="resize: none;"></textarea>

                        <!-- Button to choose any file -->
                        <input type="file" name="file" id="fileInput" class="d-none"
                            accept="image/*,audio/*">
                        <button class="btn btn-secondary" type="button" id="fileButton">
                            📎
                        </button>

                        <button class="btn btn-primary" type="submit" id="sendButton">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Firebase Config
        const firebaseConfig = {
            databaseURL: "{{ env('FIREBASE_DATABASE_URL') }}"
        };
        firebase.initializeApp(firebaseConfig);
        const database = firebase.database();

        // Function to mark all user messages as seen
        function markAllMessagesAsSeen() {
            const messagesRef = database.ref(`chats/{{ $session->uuid }}/messages`);

            messagesRef.once('value').then((snapshot) => {
                const updates = {};

                snapshot.forEach((childSnapshot) => {
                    const message = childSnapshot.val();
                    const messageId = childSnapshot.key;

                    // Mark user messages as seen
                    if (message.sender === 'user' && !message.seen) {
                        updates[messageId] = {
                            ...message,
                            seen: true
                        };

                        // Update in Laravel database
                        $.ajax({
                            url: "{{ route('updateSeen') }}",
                            method: "POST",
                            data: {
                                '_token': "{{ csrf_token() }}",
                                'firebase_id': messageId
                            },
                            success: function(response) {
                                console.log('تم تحديث حالة الرسالة كمقروءة:', messageId);
                            },
                            error: function(xhr) {
                                console.error('فشل في تحديث حالة الرسالة:', messageId);
                            }
                        });
                    }
                });

                // Update all in Firebase
                if (Object.keys(updates).length > 0) {
                    messagesRef.update(updates);
                }
            });
        }

        document.getElementById('fileButton').addEventListener('click', function() {
            document.getElementById('fileInput').click();
        });

        // Scroll to bottom on load
        document.addEventListener("DOMContentLoaded", function() {
            markAllMessagesAsSeen();

            let chatMessages = document.getElementById("chatMessages");
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            // Listen for new messages
            const messagesRef = database.ref('chats/{{ $session->uuid }}/messages');
            const reactionsRef = database.ref('messages');

            reactionsRef.on('child_changed', (snapshot) => {
                const messageId = snapshot.key;
                const reactions = snapshot.val().reactions;

                updateMessageReactions(messageId, reactions);
            });
            reactionsRef.on('child_added', (snapshot) => {
                const messageId = snapshot.key;
                const reactions = snapshot.val().reactions;

                updateMessageReactions(messageId, reactions);
            });
            reactionsRef.on('child_removed', (snapshot) => {
                const messageId = snapshot.key;
                const reactions = snapshot.val().reactions;
                console.log('Reactions removed for message:', messageId);
                updateMessageReactions(messageId, null);
            });

            function updateMessageReactions(messageId, reactions) {
                let messageElement = $(`[data-message-id="${messageId}"]`);

                if (messageElement.length) {
                    let reactionsContainer = messageElement.find('.reactions');
                    let reactionsHtml = '';

                    if (reactions) {
                        const reactionsArray = Object.values(reactions);
                        reactionsHtml = reactionsArray.map(reaction =>
                            `<span>${reaction.reaction}</span>`
                        ).join('');
                    }

                    reactionsContainer.html(reactionsHtml);
                }
            }
            messagesRef.on('child_added', (snapshot) => {
                const message = snapshot.val();
                const messageId = snapshot.key;

                if (!message || !message.content || message.content === 'undefined') {
                    console.warn('تم تخطي رسالة غير صالحة:', message);
                    return;
                }

                if (!$(`[data-message-id="${messageId}"]`).length) {
                    addMessageToChat(message, messageId);
                }

                if (message.sender === 'user' && !message.seen) {
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

                if (updatedMsg.seen) {
                    $(`[data-message-id="${msgId}"] .seen-status`).css("color", "chartreuse");
                }

                if (updatedMsg.content) {
                    let messageBubble = $(`[data-message-id="${msgId}"] .message-bubble`);

                    if (updatedMsg.edited) {
                        if (!messageBubble.find(".edited-label").length) {
                            messageBubble.prepend(`<p class="mb-0 edited-label"><small>*معدلة</small></p>`);
                        }
                    }
                    messageBubble.find("p.message-text").text(updatedMsg.content);
                }
            });

            messagesRef.on('child_removed', (snapshot) => {
                const msgId = snapshot.key; // firebase_id
                $(`[data-message-id="${msgId}"]`).remove();
            });
        });

        // For Seen
        window.addEventListener('focus', function() {
            markAllMessagesAsSeen();
        });

        // *******************************************************************************************

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

        // ✅ Check if session closed by user
        const sessionRef = database.ref('sessions/{{ $session->uuid }}');
        const messagesRef = database.ref(`chats/{{ $session->uuid }}/messages`);
        const currentAgentId = "{{ auth()->guard('agent')->user()->id ?? '' }}";
        let initialAgentId = null;
        let userMessageTimer = null;
        let lastAgentMessageTime = Date.now();

        sessionRef.once('value').then(snapshot => {
            const data = snapshot.val();
            if (data && data.agent_id) {
                initialAgentId = data.agent_id;
            }
        });

        const sessionId = "{{ $session->id }}";
        const userId = "{{ $session->chat->user_id }}";

        sessionRef.on('child_changed', snapshot => {
            const key = snapshot.key;
            const value = snapshot.val();

            if (key === 'status' && value === 'closed') {
                Swal.fire({
                    icon: 'info',
                    title: 'تم إنهاء المحادثة من طرف العميل',
                    confirmButtonText: 'موافق',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then(() => {
                    window.location.href = `/rate-agent/${sessionId}/${userId}`;
                });
            }

            if (key === 'agent_id' && initialAgentId && value != currentAgentId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'تنبيه!',
                    text: 'تم تحويل هذه الجلسة إلى وكيل آخر لعدم التفاعل خلال الفترة المحددة.',
                    confirmButtonText: 'موافق',
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then(() => {
                    window.location.href = `/rate-agent/${sessionId}/${userId}`;
                });
            }
        });

        messagesRef.on('child_added', snapshot => {
            const message = snapshot.val();

            if (!message) return;

            if (message.sender === 'user') {
                console.log("👤 المستخدم أرسل رسالة - بدء العد التنازلي");
                clearTimeout(userMessageTimer);

                userMessageTimer = setTimeout(() => {
                    const secondsSinceLastAgentMsg = (Date.now() - lastAgentMessageTime) / 1000;

                    if (secondsSinceLastAgentMsg >= 30) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'تحذير!',
                            text: 'لم ترد على رسالة العميل منذ 30 ثانية، سيتم تحويل الجلسة لوكيل آخر بعد 30 ثانية إضافية إن لم يتم الرد.',
                            confirmButtonText: 'حسنًا',
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });
                    }
                }, 30000);
            }

            if (message.sender === 'agent') {
                lastAgentMessageTime = Date.now();
                clearTimeout(userMessageTimer);
                console.log("✅ الوكيل رد — تم إيقاف التحذير");
            }
        });

        // *******************************************************************************************************

        // Add message to chat dynamically
        function addMessageToChat(message, messageId) {
            // التحقق من وجود المحتوى
            if (!message.content || message.content === 'undefined') {
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

            let reactionsHtml = '';
            if (message.reactions) {
                const reactionsArray = Object.values(message.reactions);
                reactionsHtml = reactionsArray.map(reaction =>
                    `<span>${reaction.reaction}</span>`
                ).join('');
            }

            let reactionsHtmlUser = `<div class="reactions">${reactionsHtml}</div>`;
            let reactionsHtmlAgent = `<div class="reactions">${reactionsHtml}</div>`;

            let messageHtml = "";

            if (message.sender === 'agent') {
                // رسالة من الوكيل
                if (message.type === "file" && message.media_path) {
                    if (/\.(mp3|wav|ogg|webm)$/i.test(message.media_path)) {
                        messageHtml = `
                <div class="message message-user" data-message-id="${messageId}">
                    <div class="message-bubble user-bubble">
                    ${replyHtml}
                        <audio controls src="${message.media_path}"></audio>
                        <div class="message-time">
                                    <div class="message-options">
                                        <button class="options-btn">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                        <ul class="options-menu">
                                            <li class="reply-option"><i class="fa-solid fa-reply"></i>رد</li>
                                            <li class="star-option"> <i class="fa-solid fa-star"></i>تمييز بنجمة </li>
                                            <li class="pin-option"> <i class="fa-solid fa-thumbtack"></i>تثبيت</li>
                                            <li class="react-option"><i class="fa-solid fa-face-smile"></i>ريأكت</li>
                                        </ul>
                                    </div>
                                    <span>${message.time}</span>
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
                                    <div class="message-options">
                                        <button class="options-btn">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                        <ul class="options-menu">
                                            <li class="reply-option"><i class="fa-solid fa-reply"></i>رد</li>
                                            <li class="star-option"> <i class="fa-solid fa-star"></i>تمييز بنجمة </li>
                                            <li class="pin-option"> <i class="fa-solid fa-thumbtack"></i>تثبيت</li>
                                            <li class="react-option"><i class="fa-solid fa-face-smile"></i>ريأكت</li>
                                        </ul>
                                    </div>
                                    <span>${message.time}</span>
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
                                    <div class="message-options">
                                        <button class="options-btn">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                        <ul class="options-menu">
                                            <li class="reply-option"><i class="fa-solid fa-reply"></i>رد</li>
                                            <li class="star-option"> <i class="fa-solid fa-star"></i>تمييز بنجمة </li>
                                            <li class="pin-option"> <i class="fa-solid fa-thumbtack"></i>تثبيت</li>
                                            <li class="react-option"><i class="fa-solid fa-face-smile"></i>ريأكت</li>
                                        </ul>
                                    </div>
                                    <span>${message.time}</span>
                                </div>
                            </div>
                        </div>`;
                    }
                } else {
                    // text
                    messageHtml = `
                <div class="message message-user" data-message-id="${messageId}">
                    <div class="message-bubble user-bubble">
                        ${replyHtml}
                        <p class="mb-0">${message.content}</p>
                        <div class="message-time">
                                    <div class="message-options">
                                        <button class="options-btn">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                        <ul class="options-menu">
                                            <li class="reply-option"><i class="fa-solid fa-reply"></i>رد</li>
                                            <li class="star-option"> <i class="fa-solid fa-star"></i>تمييز بنجمة </li>
                                            <li class="pin-option"> <i class="fa-solid fa-thumbtack"></i>تثبيت</li>
                                            <li class="react-option"><i class="fa-solid fa-face-smile"></i>ريأكت</li>
                                        </ul>
                                    </div>
                                    <span>${message.time}</span>
                                </div>
                            ${reactionsHtmlUser}
                            </div>
                        </div>`;
                }
            } else {
                // user message
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
                                    <div class="message-options">
                                        <button class="options-btn">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                        <ul class="options-menu">
                                            <li class="reply-option"><i class="fa-solid fa-reply"></i>رد</li>
                                            <li class="star-option"> <i class="fa-solid fa-star"></i>تمييز بنجمة </li>
                                            <li class="pin-option"> <i class="fa-solid fa-thumbtack"></i>تثبيت</li>
                                            <li class="react-option"><i class="fa-solid fa-face-smile"></i>ريأكت</li>
                                        </ul>
                                    </div>
                                    <span>${message.time}</span>
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
                                    <div class="message-options">
                                        <button class="options-btn">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                        <ul class="options-menu">
                                            <li class="reply-option"><i class="fa-solid fa-reply"></i>رد</li>
                                            <li class="star-option"> <i class="fa-solid fa-star"></i>تمييز بنجمة </li>
                                            <li class="pin-option"> <i class="fa-solid fa-thumbtack"></i>تثبيت</li>
                                            <li class="react-option"><i class="fa-solid fa-face-smile"></i>ريأكت</li>
                                        </ul>
                                    </div>
                                    <span>${message.time}</span>
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
                                    <div class="message-options">
                                        <button class="options-btn">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                        <ul class="options-menu">
                                            <li class="reply-option"><i class="fa-solid fa-reply"></i>رد</li>
                                            <li class="star-option"> <i class="fa-solid fa-star"></i>تمييز بنجمة </li>
                                            <li class="pin-option"> <i class="fa-solid fa-thumbtack"></i>تثبيت</li>
                                            <li class="react-option"><i class="fa-solid fa-face-smile"></i>ريأكت</li>
                                        </ul>
                                    </div>
                                    <span>${message.time}</span>
                                </div>
                            </div>
                        </div>`;
                    }
                } else {
                    // Text
                    messageHtml = `
            <div class="message message-agent" data-message-id="${messageId}">
                <div class="message-bubble agent-bubble">
                    ${replyHtml}
                    <div class="d-flex align-items-center mb-2">
                    </div>
                    ${message.edited ? '<p class="mb-0"><small>*معدلة</small></p>' : ''}
                    <p class="mb-0 message-text">${message.content}</p>
                    <div class="message-time">
                                    <div class="message-options">
                                        <button class="options-btn">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                        <ul class="options-menu">
                                            <li class="reply-option"><i class="fa-solid fa-reply"></i>رد</li>
                                            <li class="star-option"> <i class="fa-solid fa-star"></i>تمييز بنجمة </li>
                                            <li class="pin-option"> <i class="fa-solid fa-thumbtack"></i>تثبيت</li>
                                            <li class="react-option"><i class="fa-solid fa-face-smile"></i>ريأكت</li>
                                        </ul>
                                    </div>
                                    <span>${message.time}</span>
                                </div>
                                ${reactionsHtmlAgent}

                            </div>
                        </div>`;
                }
            }

            $("#chatMessages").append(messageHtml);
            $("#chatMessages").scrollTop($("#chatMessages")[0].scrollHeight);
        }

        // Reply
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

        // Send message by agent
        $(document).ready(function() {
            $("#agentMessageForm").on("submit", function(e) {
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
                        $("#replayToId").val("");
                        $("#replyPreview").remove();
                    },
                    error: function(xhr) {
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

                        // intialize the form and submit it to laravel
                        let formData = new FormData();
                        formData.append("_token", "{{ csrf_token() }}");
                        formData.append("uuid", "{{ $session->uuid }}");
                        formData.append("file", file);

                        fetch("{{ route('replayByAgent') }}", {
                            method: "POST",
                            body: formData
                        }).then(res => res.json()).then(data => {
                            console.log("تم رفع التسجيل:", data);
                        });
                    });

                    // swtich between button start recording and stop recording
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
    </script>

    {{-- Typing Section --}}
    <script>
        const typingRef = database.ref('chats/{{ $session->uuid }}/typing');
        const typingIndicator = document.getElementById("typingIndicator");
        const messageInput = document.getElementById("messageInput");
        const typingSound = document.getElementById("typingSound");
        let typingTimeout;

        // Agent typing
        messageInput.addEventListener("input", function() {
            typingRef.set({
                sender: "agent",
                typing: messageInput.value.trim() !== ""
            });

            clearTimeout(typingTimeout);
            typingTimeout = setTimeout(() => {
                typingRef.set({
                    sender: "agent",
                    typing: false
                });
            }, 500);
        });

        // Listen for user typing
        typingRef.on("value", (snapshot) => {
            const data = snapshot.val();
            if (!data) return;

            if (data.sender === "user" && data.typing) {
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

    <!-- Options Menu Script -->
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

        $(document).on("click", ".star-option", function() {
            alert("تم تمييز الرسالة بنجمة ⭐");
        });

        $(document).on("click", ".pin-option", function() {
            alert("تم تثبيت الرسالة 📌");
        });

        $(document).on("click", ".react-option", function() {
            let menu = $(this).closest(".message-bubble").find(".reactions-menu").show();
        });
    </script>

    <!-- Transfer Session Script -->
    <script>
        $(document).ready(function() {
            $("#transferSessionBtn").on("click", function() {
                $("#transferModal").modal("show");
            });

            $("#confirmTransfer").on("click", function() {
                let departmentId = $("#departmentSelect").val();
                let uuid = $("#transferSessionBtn").data("uuid");

                if (!departmentId) {
                    alert("من فضلك اختر الإدارة أولاً");
                    return;
                }

                $.ajax({
                    type: "POST",
                    url: "{{ route('transferSession') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        uuid: uuid,
                        department_id: departmentId
                    },
                    success: function(response) {
                        $("#transferModal").modal("hide");
                        alert("تم تحويل الجلسة بنجاح.");
                        window.location.href = "{{ route('agent') }}";
                    },
                    error: function() {
                        alert("حدث خطأ أثناء تحويل الجلسة.");
                    }
                });
            });
        });
    </script>

</body>

</html>
