<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Firebase App SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js"></script>
    <!-- Firebase Database SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-database-compat.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/user/layout.css') }}">
</head>

<body>
    <div class="btn-icon" id="toggleSessions">
        <i class="fa-solid fa-headset fa-lg"></i>
    </div>

    <div class="chat-popup" id="chatPopup">
        <div class="chat-header">
            <div>
                <h6 style="margin: 0">خدمة العملاء</h6>
            </div>
            <div>
                <button id="endChatBtn" class="btn btn-danger btn-sm d-none" style="margin-right:10px;">
                    إنهاء المحادثة
                </button>
                <i class="fa-solid fa-up-right-and-down-left-from-center" id="expandChat"
                    style="cursor:pointer; margin-right:10px;"></i>
                <i class="fa-solid fa-down-left-and-up-right-to-center d-none" id="minimizeChat"
                    style="cursor:pointer; margin-right:10px;"></i>
                <i class="fa-solid fa-xmark" id="closeChat" style="cursor: pointer;"></i>
            </div>
        </div>

        <div class="topics-section" id="topicsSection">
            <div class="topics-list" id="topicsList">
                {{-- Topics --}}
                @forelse ($topics as $topic)
                    <div class="topic-item" data-id="{{ $topic->id }}" data-name="{{ $topic->title }}"
                        data-final="{{ $topic->is_final }}">
                        {{ $topic->title }}
                    </div>
                @empty
                    <p>لا توجد مواضيع حالياً</p>
                @endforelse

                {{-- previous sessions --}}
                @forelse ($sessions as $session)
                    <div class="topic-item session-item" data-id="{{ $session->id }}" data-name="{{ $session->name }}">
                        {{ $session->name }}
                    </div>
                @empty
                    <p>لا توجد جلسات حالياً</p>
                @endforelse

                {{-- create new session --}}
                <form id="session-form" class="mt-3" style="width: 100%">
                    @csrf
                    <div class="d-flex flex-row align-items-center">
                        <input type="text" name="name" class="form-control" placeholder="ادخل موضوع المحادثة">
                        <button type="submit" id="submit-create-session" class="btn btn-dark">confirm</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="chat-body d-none" id="chatBody"></div>
        <small id="typingIndicator" style="display: none; color:#888; margin-left: 5px;">يكتب الان...</small>
        <audio id="typingSound" preload="auto">
            <source src="{{ asset('sounds/typing.mp3') }}" type="audio/mpeg">
        </audio>
        <form id="send-message">
            <div class="chat-footer d-none" id="chatFooter">
                <input type="text" name="message" id="chatInput" placeholder="اكتب رسالتك..." />
                <button id="sendBtn" type="submit"><i class="fa-solid fa-paper-plane"></i></button>
            </div>
        </form>
    </div>



    <!-- Toggle button -->
    <button id="toggleBtn">☰</button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h4 style="margin-left: 45px;">{{ $user->name }}</h4>
        <div style="margin-top: 15px;">
            <a href="{{ route('user.dashboard') }}"
                class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="javascript:void(0)" onclick="document.getElementById('user-logout').submit()"
                class="{{ request()->routeIs('dashboard.logout') ? 'active' : '' }}">Logout</a>
            <form action="{{ route('user.logout') }}" method="post" id="user-logout">@csrf</form>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        @yield('content')
    </div>

    <!-- Bootstrap & jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.activeSession = @json($activeSession);

        function openSession(sessionId) {

            currentSessionId = sessionId;

            $("#chatBody").removeClass("d-none");
            $("#chatFooter").removeClass("d-none");
            $("#topicsSection").addClass("d-none");

            $("#chatBody").html('');

            if (!firebaseAppInitialized) {

                const firebaseConfig = {
                    databaseURL: "{{ env('FIREBASE_DATABASE_URL') }}"
                };

                firebase.initializeApp(firebaseConfig);

                firebaseDatabase = firebase.database();

                firebaseAppInitialized = true;
            }

            if (firebaseListener) {
                firebaseListener.off();
            }

            firebaseListener = firebaseDatabase.ref(
                'chats/' + currentSessionId + '/messages'
            );

            firebaseListener.on('child_added', function(snapshot) {

                const message = snapshot.val();

                appendMessage(message.sender, message.content);

                $("#chatBody").scrollTop(
                    $("#chatBody")[0].scrollHeight
                );
            });

            const sessionRef = firebaseDatabase.ref(
                'sessions/' + currentSessionId
            );

            sessionRef.on('value', (snapshot) => {

                const data = snapshot.val();

                if (!data) return;

                let header = 'خدمة العملاء';

                if (data.agent_name) {

                    header = `
                <span style="color:#0d6efd;">
                    ${data.agent_name}
                </span>
                <small>available</small>
            `;
                }

                $(".chat-header h6").html(header);

                if (data.status === 'in_agent') {

                    $(".chat-header small").text("available");

                } else if (data.status === 'waiting_agent') {

                    $(".chat-header h6").html(`
                <small>في انتظار الرد...</small>
            `);
                }
            });

            $('#chatBody').data('session-id', currentSessionId);

            $('#endChatBtn').removeClass('d-none');
        }

        $(document).on(
            "click",
            ".session-item, .topic-item[data-final='1']",
            function() {

                const sessionId = $(this).data("id");

                openSession(sessionId);
            }
        );
    </script>
    <script src="{{ asset('assets/js/user/layout.js') }}"></script>
    <script src="{{ asset('assets/js/user/sendMessage.js') }}"></script>
    <script>
        window.csrfToken = "{{ csrf_token() }}";
        window.createSessionFromTopicUrl = "{{ route('createSessionFromTopic') }}";
        window.createNewSession = "{{ route('user.createSession') }}";
        window.currentSessionId = null;
        window.firebaseDatabaseUrl = "{{ env('FIREBASE_DATABASE_URL') }}";
    </script>


    <script>
        let firebaseAppInitialized = false;
        let firebaseDatabase = null;
        let firebaseListener = null;

        function formatMessage(content) {
            const maxLength = 50; // count of letters
            if (content.length <= maxLength) {
                return `<p style="margin:0;">${content}</p>`;
            }

            const shortText = content.substring(0, maxLength);
            return `
                <p class="message-text">
                    <span class="short-text">${shortText}</span>
                    <span class="full-text d-none">${content}</span>
                </p>
                <a href="#" class="toggle-more text-primary">عرض المزيد</a>
            `;
        }

        function appendMessage(sender, content) {
            const formatted = formatMessage(content);
            const messageClass = sender === 'user' ? 'chat-message user' : 'chat-message';
            $("#chatBody").append(`
                <div class="${messageClass}">
                    ${formatted}
                </div>
            `);

            $("#chatBody").scrollTop($("#chatBody")[0].scrollHeight);
        }

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

        $(document).on("click", ".session-item, .topic-item[data-final='1']", function() {
            currentSessionId = $(this).data("id");
            $("#chatBody").removeClass("d-none");
            $("#chatFooter").removeClass("d-none");
            $("#topicsSection").addClass("d-none");
            $("#chatBody").html('');

            if (!firebaseAppInitialized) {
                const firebaseConfig = {
                    databaseURL: "{{ env('FIREBASE_DATABASE_URL') }}"
                };
                firebase.initializeApp(firebaseConfig);
                firebaseDatabase = firebase.database();
                firebaseAppInitialized = true;
            }

            if (firebaseListener) {
                firebaseListener.off();
            }

            firebaseListener = firebaseDatabase.ref('chats/' + currentSessionId + '/messages');

            firebaseListener.on('child_added', function(snapshot) {
                const message = snapshot.val();
                console.log("🔥 New message from Firebase:", message);

                appendMessage(message.sender, message.content);

                $("#chatBody").scrollTop($("#chatBody")[0].scrollHeight);
            });

            // Listen for session
            const sessionRef = firebaseDatabase.ref('sessions/' + currentSessionId);
            sessionRef.on('value', (snapshot) => {
                const data = snapshot.val();
                if (!data) return;
                let header = 'خدمة العملاء';
                if (data.agent_name) {
                    header = `<span style="color:#0d6efd;">${data.agent_name}</span>
                              <small>available</small>
                            `;
                }
                $(".chat-header h6").html(header);
                if (data.status === 'in_agent') {
                    $(".chat-header small").text("available");
                } else if (data.status === 'waiting_agent') {
                    $(".chat-header h6").html(`<small>في انتظار الرد...</small>`);
                }
            });

            const typingRef = firebaseDatabase.ref('sessions/' + currentSessionId + '/typing');

            $("input[name='message']").on("input", function() {
                typingRef.update({
                    user_typing: true,
                });

                clearTimeout(window.typingTimeout);
                window.typingTimeout = setTimeout(() => {
                    typingRef.update({
                        user_typing: false,
                    });
                }, 2000);
            });

            typingIndicator = document.getElementById('typingIndicator');
            const typingSound = document.getElementById("typingSound");

            typingSound.loop = true;

            typingRef.on('value', (snapshot) => {

                console.log("typing snapshot:", snapshot.val());
                console.log("audioUnlocked:", audioUnlocked);

                const data = snapshot.val();

                if (!data || data.agent_typing !== true) {
                    typingIndicator.style.display = "none";

                    typingSound.pause();
                    typingSound.currentTime = 0;

                    return;
                }

                typingIndicator.innerText = "يكتب الآن...";
                typingIndicator.style.display = "block";

                typingSound.currentTime = 0;

                typingSound.play()
                    .then(() => {
                        console.log("sound played");
                    })
                    .catch(err => {
                        console.log("play failed:", err);
                    });
            });
        });
    </script>

    <script>
        $(document).on('click', '#endChatBtn', function() {
            if (!confirm('هل أنت متأكد أنك تريد إنهاء المحادثة؟')) return;

            let sessionId = $('#chatBody').data('session-id');
            console.log("🔍 Current session ID:", sessionId);
            if (!sessionId) {
                alert('لا توجد جلسة مفتوحة حالياً.');
                return;
            }

            $.ajax({
                url: `/chat/${sessionId}/close`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $(".chat-header h6").html(`<small>خدمة العملاء</small>`);
                    $('#endChatBtn').addClass('d-none');
                    $('#chatFooter').addClass('d-none');

                    $('#chatBody').html(`
                        <div class="chat-message text-center text-muted mb-3" style="width:100%;">
                            تم إنهاء المحادثة بنجاح. نرجو منك تقييم تجربتك مع خدمة العملاء 🙏
                        </div>

                        <div id="ratingSection" class="text-center mt-3">
                            <h6>هل كانت المحادثة مفيدة؟</h6>
                            <div class="rating-buttons my-2">
                                <button class="btn btn-outline-success me-2" id="rateHelpful">
                                    <i class="fa-solid fa-thumbs-up"></i> نعم
                                </button>
                                <button class="btn btn-outline-danger" id="rateNotHelpful">
                                    <i class="fa-solid fa-thumbs-down"></i> لا
                                </button>
                            </div>

                            <div id="commentBox" class="mt-3 d-none">
                                <textarea class="form-control mb-2" id="ratingComment" rows="3"
                                style="height: 74px" placeholder="يمكنك ترك تعليق (اختياري)"></textarea>
                                <button class="btn btn-primary" id="submitRating">إرسال التقييم</button>
                            </div>
                        </div>
                    `);
                    let userRating = null;

                    $('#rateHelpful').on('click', function() {
                        userRating = 1;
                        $('#rateHelpful').addClass('btn-success text-white');
                        $('#rateNotHelpful').removeClass('btn-danger text-white');
                        $('#commentBox').removeClass('d-none');
                    });
                    $('#rateNotHelpful').on('click', function() {
                        userRating = 0;
                        $('#rateNotHelpful').addClass('btn-danger text-white');
                        $('#rateHelpful').removeClass('btn-success text-white');
                        $('#commentBox').removeClass('d-none');
                    });

                    $('#submitRating').on('click', function() {
                        const comment = $('#ratingComment').val();

                        $.ajax({
                            url: `/chat/${sessionId}/rate`,
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                rating: userRating,
                                comment: comment
                            },
                            success: function(response) {
                                $('#ratingSection').html(`
                                    <div class="alert alert-success mt-3">
                                        ${response.message}
                                    </div>
                                `);
                            },
                            error: function() {
                                alert('حدث خطأ أثناء إرسال التقييم');
                            }
                        });
                    });
                },
                error: function(xhr) {
                    alert('حدث خطأ أثناء إنهاء الجلسة.');
                }
            });
        });

        $(document).ready(function() {

            if (window.activeSession) {

                openSession(window.activeSession.id);
            }
        });

        let audioUnlocked = false;

        $(document).ready(function() {

            const typingSound = document.getElementById("typingSound");

            // unlock audio
            $(document).one('click', function() {

                typingSound.volume = 1;

                typingSound.play()
                    .then(() => {

                        typingSound.pause();
                        typingSound.currentTime = 0;

                        audioUnlocked = true;

                        console.log("Audio unlocked");

                    })
                    .catch(console.log);

            });

        });
    </script>

</body>

</html>
