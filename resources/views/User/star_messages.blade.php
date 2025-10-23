<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Firebase App SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js"></script>
    <!-- Firebase Database SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-database-compat.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/star_messages.css') }}">
</head>

<body>
    <div class="starred-page">
        <header class="starred-header d-flex justify-content-between align-items-center">
            <a href="{{ url()->previous() }}" class="btn btn-light">رجوع</a>
            <h3 class="m-0">⭐ كل الرسائل المميزة</h3>
        </header>

        <main class="starred-list">
            @if ($allStarredMessages->isEmpty())
                <div class="empty-state">
                    <i class="fa-regular fa-star fa-2x mb-3 text-muted"></i>
                    <p>لا توجد رسائل مميزة حتى الآن.</p>
                </div>
            @else
                @foreach ($allStarredMessages as $sm)
                    <div class="starred-container" style="display: flex; justify-content:flex-end;align-items:center"
                        data-msg-id="{{ $sm->id }}">
                        <div style="display: flex; flex-direction:column;">
                            <button class="btn btn-sm btn-link text-warning" data-msg-id="{{ $sm->id }}">
                                <i class="fa-solid fa-star"></i>
                            </button>
                            <button class="btn delete-starred-btn" data-msg-id="{{ $sm->id }}">
                                <i class="fa-solid fa-trash-can color-danger"></i>
                            </button>
                        </div>
                        <div class="starred-item d-flex align-items-start" style="flex: 1;"
                            data-msg-id="{{ $sm->id }}" data-session-uuid="{{ $sm->sessionChat->uuid ?? '' }}">
                            <div class="msg-info">
                                <strong class="d-block">
                                    @if ($sm->sender === 'user')
                                        أنت
                                    @else
                                        الدعم الفني
                                    @endif
                                </strong>
                                <p class="m-0 text-muted">{{ $sm->content }}</p>
                                <small class="text-secondary">
                                    {{ \Carbon\Carbon::parse($sm->created_at)->diffForHumans() }}
                                </small>
                            </div>
                        </div>

                    </div>
                @endforeach
            @endif
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const baseChatUrl = "{{ url('chat') }}";

            // فتح الرسالة عند الضغط
            document.querySelectorAll(".starred-item").forEach(function(item) {
                item.addEventListener('click', function(e) {
                    // تجاهل الضغط على زر الحذف
                    if (e.target.closest('.delete-starred-btn')) return;

                    let session_uuid = this.getAttribute('data-session-uuid');
                    let messageId = this.getAttribute('data-msg-id');

                    if (session_uuid) {
                        window.open(`${baseChatUrl}/${session_uuid}?focusMsg=${messageId}`,
                            "_blank");
                    }
                });
            });

            // زر الحذف
            $(document).on('click', ".delete-starred-btn", function(e) {
                e.preventDefault();
                let messageId = $(this).closest(".starred-container").data("msg-id");

                $.ajax({
                    url: "{{ route('toggleStarMessage') }}",
                    method: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": messageId
                    },
                    success: function(response) {
                        // امسح الرسالة من قائمة المميزة
                        $(`.starred-container[data-msg-id="${messageId}"]`).remove();

                        // امسح النجمة من صفحة الشات (لو موجودة)
                        $(`[data-db-id="${messageId}"]`).find(".star-icon").remove();

                        // لو مفيش رسائل تاني
                        if ($(".starred-container").length === 0) {
                            $(".starred-list").html(`
                            <div class="empty-state">
                                <i class="fa-regular fa-star fa-2x mb-3 text-muted"></i>
                                <p>لا توجد رسائل مميزة حتى الآن.</p>
                            </div>
                        `);
                        }
                    },
                    error: function() {
                        alert("حدث خطأ أثناء حذف الرسالة");
                    }
                });
            });
        });
    </script>

</body>

</html>
