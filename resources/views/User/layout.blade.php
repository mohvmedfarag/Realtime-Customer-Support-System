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
            <h6>خدمة العملاء</h6>
            <i class="fa-solid fa-xmark" id="closeChat" style="cursor: pointer;"></i>
        </div>

        <div class="topics-section" id="topicsSection">
            <div class="topics-list" id="topicsList">
                {{-- المواضيع --}}
                @forelse ($topics as $topic)
                    <div class="topic-item" data-id="{{ $topic->id }}" data-name="{{ $topic->title }}"
                        data-final="{{ $topic->is_final }}">
                        {{ $topic->title }}
                    </div>
                @empty
                    <p>لا توجد مواضيع حالياً</p>
                @endforelse

                {{-- الجلسات السابقة --}}
                @forelse ($sessions as $session)
                    <div class="topic-item session-item" data-id="{{ $session->id }}" data-name="{{ $session->name }}">
                        {{ $session->name }}
                    </div>
                @empty
                    <p>لا توجد جلسات حالياً</p>
                @endforelse

                {{-- إنشاء جلسة جديدة --}}
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
    <script src="{{ asset('assets/js/user/layout.js') }}"></script>
    <script>
        $(document).ready(function() {
            let currentSessionId = null;

            // عند فتح جلسة جديدة أو قديمة، خزّن الـ ID بتاعها
            $(document).on("click", ".session-item, .topic-item[data-final='1']", function() {
                currentSessionId = $(this).data("id");
                $("#chatBody").removeClass("d-none");
                $("#chatFooter").removeClass("d-none");
                $("#chatBody").html(''); // ممكن هنا لاحقًا تجيب الرسائل القديمة
            });

            // إرسال رسالة
            $("#send-message").on("submit", function(e) {
                e.preventDefault();

                const message = $("#chatInput").val().trim();
                if (!message) return;

                if (!currentSessionId) {
                    alert("يرجى اختيار جلسة أولاً قبل إرسال الرسالة");
                    return;
                }

                $.ajax({
                    url: "{{ route('user.sendMessage') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        session_id: currentSessionId,
                        content: message
                    },
                    success: function(response) {
                        // عرض الرسالة في الواجهة
                        $("#chatBody").append(`
                    <div class="chat-message user">${message}</div>
                `);
                        $("#chatInput").val('');
                        $("#chatBody").scrollTop($("#chatBody")[0].scrollHeight);
                    },
                    error: function(xhr) {
                        let error = xhr.responseJSON?.message || "حدث خطأ أثناء إرسال الرسالة";
                        alert(error);
                    }
                });
            });
        });
    </script>
</body>

</html>
