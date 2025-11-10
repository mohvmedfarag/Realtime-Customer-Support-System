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
                    <th scope="col">#</th>
                    <th scope="col">Session ID</th>
                    <th scope="col">Session</th>
                    <th scope="col">User ID</th>
                    <th scope="col">User</th>
                    <th scope="col">Assigned Agent</th>
                    <th scope="col">Assigned Agent ID</th>
                    <th scope="col">status</th>
                    <th scope="col">Assign</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $session)
                    <tr class="text-center">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $session->id }}</td>
                        <td>{{ $session->name ?? 'بدون اسم' }}</td>
                        <td>{{ $session->chat->user_id }}</td>
                        <td>{{ $session->chat->user->name }}</td>
                        <td>{{ $session->agent->name }}</td>
                        <td>{{ $session->agent_id }}</td>
                        <td>{{ $session->status }}</td>
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
                                                                        @else
                                                                            <span
                                                                                class="badge bg-secondary mt-1">Offline</span>
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
        $(document).ready( function(){
            $('.assign-btn').on('click', function(){
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
                    success: function(response){
                        button.closest('.modal').modal('hide'); // close modal
                        // remove row
                        button.closest('tr').fadeOut(500, function() {
                            $(this).remove();
                        });

                        const flashContainer = $('<div class="flasher-container position-fixed top-0 end-0 p-3" style="z-index:9999;"></div>');
                        const alert = $('<div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert"></div>')
                            .text(response.message)
                            .append('<button type="button" class="btn-close" data-bs-dismiss="alert"></button>');
                        flashContainer.append(alert);
                        $('body').append(flashContainer);

                        setTimeout(() => {
                            flashContainer.fadeOut(500, () => flashContainer.remove());
                        }, 2000);
                    },
                    error: function(xhr){
                        Swal.fire({
                            title: 'خطأ!',
                            text: xhr.responseJSON?.message || 'حدث خطأ أثناء التعيين',
                            icon: 'error'
                        });
                    }
                });
            })
        } );
    </script>
@endsection
