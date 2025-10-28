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
                                        {{ $msg->content }}
                                    </div>
                                </div>
                            @else
                                <div class="d-flex justify-content-end mb-3">
                                    <div class="bg-primary text-white p-2 rounded-3 shadow-sm">
                                        {{ $msg->content }}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Message Input -->
                    <div class="card-footer bg-white">
                        <form id="send-message" class="d-flex align-items-center">
                            <input type="hidden" name="session_id" value="{{ $session->id }}">
                            <input type="text" name="message" class="form-control me-2 rounded-pill"
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
        $(document).ready(function() {
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
                        // إضافة الرسالة في واجهة الشات مباشرة
                        $(".chat-box").append(`
                    <div class="d-flex justify-content-end mb-3">
                        <div class="bg-primary text-white p-2 rounded-3 shadow-sm">
                            ${response.data.content}
                        </div>
                    </div>
                `);

                        // تفريغ الحقل بعد الإرسال
                        messageInput.val('');
                        // التمرير للأسفل
                        $(".chat-box").scrollTop($(".chat-box")[0].scrollHeight);
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.message || "حدث خطأ أثناء إرسال الرسالة");
                    }
                });
            });
        });
    </script>
@endsection
