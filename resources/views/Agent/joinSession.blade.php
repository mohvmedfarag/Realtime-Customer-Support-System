@extends('Agent.layout')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row">
            <!-- 🧍‍♂️ User Details (Left Side) -->
            <div class="col-md-4 col-lg-3 mb-3">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body text-center">
                        <img src="{{ asset('assets/images/default.png') }}" width="90px" height="80px" alt="User"
                            class="rounded-circle mb-3">
                        <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                        <p class="text-muted mb-3">{{ $user->email }}</p>

                        <hr>

                        <div class="text-start">
                            <p class="mb-1"><strong>Phone:</strong> +201234567890</p>
                            <p class="mb-1"><strong>Joined:</strong>
                                {{ \Carbon\Carbon::parse($user->created_at)->format('d-m-y') }}</p>
                            <p class="mb-1"><strong>Orders:</strong> 8</p>
                            <p class="mb-1"><strong>Subject:</strong> {{ $session->name }}</p>
                        </div>

                        {{-- <button class="btn btn-outline-danger btn-sm">
                        <i class="fa fa-ban me-1"></i> Block User
                    </button> --}}
                    </div>
                </div>
            </div>

            <!-- 💬 Chat Section (Right Side) -->
            <div class="col-md-8 col-lg-9">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Chat with <strong>{{ $user->name }}</strong></h6>
                        @if ($session->status === 'in_agent')
                            <span class="badge bg-success">Active</span>
                        @endif
                        @if ($session->status === 'waiting_agent')
                            <span class="badge bg-warning">Waiting</span>
                        @endif
                    </div>
                    <div class="card-body chat-box p-3" style="height: 60vh; overflow-y: auto; background-color: #f8f9fa;">
                        <!-- Example Messages -->
                        @foreach ($messages as $msg)
                            @if ($msg->sender === 'user')
                                <div class="d-flex mb-3">
                                    <div class="bg-light p-2 rounded-3 shadow-sm">
                                        @if ($msg->is_long)
                                            <p class="mb-0 message-text">
                                                <span class="short-text">{{ Str::limit($msg->content, 50, '') }}</span>
                                                <span class="full-text d-none">{{ $msg->content }}</span>
                                            </p>
                                            <a href="#" class="toggle-more text-decoration-none text-primary small">عرض المزيد</a>
                                        @else
                                            {{ $msg->content }}
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="d-flex justify-content-end mb-3">
                                    <div class="bg-primary text-white p-2 rounded-3 shadow-sm">
                                        @if ($msg->is_long)
                                            <p class="mb-0 message-text">
                                                <span class="short-text">{{ Str::limit($msg->content, 50, '') }}</span>
                                                <span class="full-text d-none">{{ $msg->content }}</span>
                                            </p>
                                            <a href="#" class="toggle-more text-decoration-none text-light small">عرض
                                                المزيد</a>
                                        @else
                                            {{ $msg->content }}
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach

                    </div>
                    <small id="typingIndicator"
                        style="display: none; color:#888; background-color: #f8f9fa; padding-left: 15px;">يكتب
                        الان...</small>
                    <!-- Message Input -->
                    <div class="card-footer bg-white">
                        <form id="send-message" class="d-flex align-items-center">
                            <input type="hidden" name="session_id" value="{{ $session->id }}">
                            <input type="text" name="message" class="form-control me-2 rounded-pill" id="messageInput"
                                placeholder="Type a message...">
                            <button type="submit" class="btn btn-primary rounded-pill">
                                <i class="fa fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        function formatMessage(content, sender) {
            const maxLength = 50; // الحد الأقصى لعدد الأحرف قبل "عرض المزيد"
            if (content.length <= maxLength) {
                return `<p class="mb-0">${content}</p>`;
            }

            const shortText = content.substring(0, maxLength);

            if (sender === 'agent') {
                return `
                    <p class="mb-0 message-text">
                        <span class="short-text">${shortText}</span>
                        <span class="full-text d-none">${content}</span>
                    </p>
                    <a href="#" class="toggle-more text-decoration-none small" style="color:white;">عرض المزيد</a>
                `;
            } else {
                return `
                    <p class="mb-0 message-text">
                        <span class="short-text">${shortText}</span>
                        <span class="full-text d-none">${content}</span>
                    </p>
                    <a href="#" class="toggle-more text-decoration-none text-primary small">عرض المزيد</a>
                `;
            }

        }

        // التعامل مع زر عرض المزيد / عرض أقل
        $(document).on("click", ".toggle-more", function(e) {
            e.preventDefault();
            const message = $(this).prev(".message-text");
            message.find(".short-text, .full-text").toggleClass("d-none");
            if ($(this).text() === "عرض المزيد") {
                $(this).text("عرض أقل");
            } else {
                $(this).text("عرض المزيد");
            }
        });
        const firebaseConfig = {
            databaseURL: "{{ env('FIREBASE_DATABASE_URL') }}"
        };
        firebase.initializeApp(firebaseConfig);
        const database = firebase.database();

        const sessionId = "{{ $session->id }}";
        const messagesRef = database.ref('chats/' + sessionId + '/messages');

        let existingMessages = @json($messages->pluck('firebase_id')->filter()->values());
        existingMessages = existingMessages.filter(id => id !== null);

        // Listen for new messages
        messagesRef.on('child_added', function(snapshot) {
            const message = snapshot.val();
            const firebaseId = snapshot.key;
            if (existingMessages.includes(firebaseId)) {
                return;
            }
            existingMessages.push(firebaseId);
            console.log("New message received from Firebase:", message);

            if (message.sender === 'agent') {
                $(".chat-box").append(`
                    <div class="d-flex justify-content-end mb-3">
                        <div class="bg-primary text-white p-2 rounded-3 shadow-sm">
                            ${formatMessage(message.content, 'agent')}
                        </div>
                    </div>
                `);
            } else {
                $(".chat-box").append(`
                    <div class="d-flex mb-3">
                        <div class="bg-light p-2 rounded-3 shadow-sm">
                            ${formatMessage(message.content, 'user')}
                        </div>
                    </div>
                `);
            }

            // scroll down
            $(".chat-box").scrollTop($(".chat-box")[0].scrollHeight);
        });
    </script>

    <script>
        $(document).ready(function() {
            const typingRef = database.ref('sessions/' + sessionId + '/typing');
            // إرسال رسالة من الـ Agent
            $("#send-message").on("submit", function(e) {
                e.preventDefault();

                const messageInput = $(this).find('input[name="message"]');
                const message = messageInput.val().trim();
                const sessionId = $(this).find('input[name="session_id"]').val();

                if (!message) return;

                $.ajax({
                    url: "{{ route('agent.sendMessage') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        session_id: sessionId,
                        message: message
                    },
                    success: function(response) {
                        typingRef.set({
                            agent_typing: false,
                        });
                        messageInput.val('');
                        $(".chat-box").scrollTop($(".chat-box")[0].scrollHeight);
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.message || "حدث خطأ أثناء إرسال الرسالة");
                    }
                });
            });
        });
    </script>
    <script>
        const typingRef = database.ref('sessions/{{ $session->id }}/typing');

        $("input[name='message']").on("input", function() {
            typingRef.set({
                agent_typing: true,
            });

            // نوقف الحالة بعد ثانيتين من التوقف عن الكتابة
            clearTimeout(window.typingTimeout);
            window.typingTimeout = setTimeout(() => {
                typingRef.set({
                    agent_typing: false,
                });
            }, 9000);
        });

        typingIndicator = document.getElementById('typingIndicator');
        typingRef.on("value", (snapshot) => {
            const data = snapshot.val();
            if (data.user_typing === true) {
                typingIndicator.innerText = "يكتب الآن...";
                typingIndicator.style.display = "block";
            } else {
                typingIndicator.style.display = "none";
            }
        });
    </script>
@endsection
