@extends('Admin.layout')
@section('content')
    <div class="content">
        <div class="d-flex">
            <h3>waiting sessions</h3>
            <h3 style="margin-left: 50px">count: {{ $count }}</h3>
        </div>

        <table class="table">
            <thead>
                <tr class="text-center">
                    <th scope="col">Session ID</th>
                    <th scope="col">Session</th>
                    <th scope="col">User</th>
                    <th scope="col">Assigned Agent</th>
                    <th scope="col">status</th>
                    <th scope="col">Assign</th>
                </tr>
            </thead>
            <tbody id="sessions-list">
                @forelse($sessions as $session)
                    <tr class="text-center" id="session-{{ $session->id }}">
                        <td>{{ $session->id }}</td>
                        <td>{{ $session->name ?? 'بدون اسم' }}</td>
                        <td><i class="fa-solid fa-user"></i>{{ $session->chat->user->name }}({{ $session->id }})</td>
                        <td><i class="fa-solid fa-headset"></i>{{ $session->agent->name }}({{ $session->agent_id }})</td>
                        @if ($session->status == 'waiting_agent')
                            <td>waiting from {{ $session->waiting_started_at->diffForHumans() }}</td>
                        @endif
                        <td>
                            <button type="button" class="btn btn-outline-dark btn-sm" data-bs-toggle="modal"
                                data-bs-target="#assignModal{{ $session->id }}">Assign</button>

                            <div class="modal fade" id="assignModal{{ $session->id }}" tabindex="-1"
                                aria-labelledby="assignModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-dark text-white">
                                            <h5 class="modal-title" id="assignModalLabel">تعيين الجلسة رقم
                                                {{ $session->id }} عن موضوع {{ $session->name }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                @foreach ($agents as $agent)
                                                    <div class="col-md-6">
                                                        <div class="card border-0 shadow-sm">
                                                            <div
                                                                class="card-body d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <h6 class="mb-1 fw-bold">{{ $agent->name }}</h6>
                                                                    <small class="text-muted">
                                                                        الإدارة:
                                                                        {{ $agent->department->name ?? 'غير محددة' }}
                                                                    </small>
                                                                    <div>
                                                                        @if ($agent->status == 'online')
                                                                            <span
                                                                                class="badge bg-success mt-1">Online</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-outline-dark assign-btn"
                                                                    data-agent-id="{{ $agent->id }}"
                                                                    data-session-id="{{ $session->id }}">
                                                                    Assign
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">There are no waiting sessions</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        const agents = @json($agents);
    </script>

    {{-- Assign session to another agent --}}
    <script>
        $(document).ready(function() {
            $(document).on('click', '.assign-btn', function() {
                let agentId = $(this).data('agent-id');
                let sessionId = $(this).data('session-id');
                let button = $(this);

                $.ajax({
                    url: "{{ route('dashboard.transferSessionToAgent') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        agent_id: agentId,
                        session_id: sessionId
                    },
                    success: function(response) {
                        button.closest('.modal').modal('hide'); // close modal
                        // Show flash message
                        const flashContainer = $(
                            '<div class="flasher-container position-fixed top-0 end-0 p-3" style="z-index:9999;"></div>'
                            );
                        const alert = $(
                                '<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert"></div>'
                                )
                            .text(response.message)
                            .append(
                                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>'
                                );
                        flashContainer.append(alert);
                        $('body').append(flashContainer);
                        setTimeout(() => {
                            flashContainer.fadeOut(500, () => flashContainer.remove());
                        }, 2000);
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'خطأ!',
                            text: xhr.responseJSON?.message || 'حدث خطأ أثناء التعيين',
                            icon: 'error'
                        });
                    }
                });
            });

        });
    </script>

    {{-- Realtime update --}}
    <script>
        const sessionsRef = database.ref('sessions');
        const sessionsList = $("#sessions-list");
        const countElement = $("h3:contains('count')");

        function updateSessionCount() {
            const count = $("#sessions-list tr").length;
            countElement.text(`count: ${count}`);
        }

        function appendSessionRow(id, data) {
            if (document.getElementById('session-' + id)) return;

            let agentsHTML = '';
            agents.forEach(agent => {
                agentsHTML += `
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 fw-bold">${agent.name}</h6>
                                    <small class="text-muted">الإدارة: ${agent.department?.name ?? 'غير محددة'}</small>
                                    <div>
                                        ${agent.status === 'online'
                                            ? '<span class="badge bg-success mt-1">Online</span>'
                                            : '<span class="badge bg-secondary mt-1">Offline</span>'
                                        }
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-dark assign-btn"
                                    data-agent-id="${agent.id}" data-session-id="${id}">
                                    Assign
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            const rowHTML = `
                <tr class="text-center" id="session-${id}">
                    <td>${id}</td>
                    <td>${data.name ?? 'بدون اسم'}</td>
                    <td><i class="fa-solid fa-user"></i> ${data.user_name ?? 'غير معروف'}</td>
                    <td><i class="fa-solid fa-headset"></i> ${data.agent_name ?? 'غير محدد'}</td>
                    <td>waiting</td>
                    <td>
                        <button type="button" class="btn btn-outline-dark btn-sm"
                            data-bs-toggle="modal" data-bs-target="#assignModal${id}">
                            Assign
                        </button>
                    </td>
                </tr>
            `;

            const modalHTML = `
                <div class="modal fade" id="assignModal${id}" tabindex="-1" aria-labelledby="assignModalLabel${id}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header bg-dark text-white">
                                <h5 class="modal-title" id="assignModalLabel${id}">تعيين الجلسة رقم ${id} عن موضوع ${data.name ?? ''}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">${agentsHTML}</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // ✅ أضف الصف داخل التابل
            sessionsList.prepend(rowHTML);
            $(`#session-${id}`).hide().fadeIn(500);

            // ✅ أضف المودال في نهاية البودي
            $('body').append(modalHTML);

            updateSessionCount();
        }

        sessionsRef.on('child_added', (snapshot) => {
            const data = snapshot.val();
            const id = snapshot.key;

            if (data.status === 'waiting_agent' && data.agent_id) {
                appendSessionRow(id, data);
            }
        });

        sessionsRef.on('child_changed', (snapshot) => {
            const data = snapshot.val();
            const id = snapshot.key;

            // ✅ لو الجلسة لسه waiting_agent، نحدث بياناتها بدل ما نحذفها
            if (data.status === 'waiting_agent') {
                const row = $(`#session-${id}`);

                if (row.length) {
                    // Update existing row (agent name, etc.)
                    row.find('td:nth-child(4)').html(
                        `<i class="fa-solid fa-headset"></i> ${data.agent_name ?? 'غير محدد'} (${data.agent_id ?? '-'})`
                    );
                } else {
                    // If row not exist (new waiting session), append it
                    appendSessionRow(id, data);
                }

                updateSessionCount();
                return;
            }

            $(`#session-${id}`).fadeOut(400, function() {
                $(this).remove();
                updateSessionCount();
            });
        });


        sessionsRef.on('child_removed', (snapshot) => {
            const id = snapshot.key;
            $(`#session-${id}`).fadeOut(400, function() {
                $(this).remove();
                updateSessionCount();
            });
        });
    </script>
@endsection
