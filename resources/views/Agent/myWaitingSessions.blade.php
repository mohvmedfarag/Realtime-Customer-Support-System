@extends('Agent.layout')
@section('content')
    <div class="container mt-4">
        <h3 class="mb-4 text-primary fw-bold">Waiting Sessions</h3>

        <div class="row" id="sessionsList">
            <!-- Example Session Card -->
            @forelse ($sessions as $session)
                <div class="col-md-6 col-lg-4 mb-4" id="session-{{ $session->id }}">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-title text-dark mb-0"><strong>Subject:</strong>{{ $session->name }}</p>
                                @if ($session->status === 'waiting_agent')
                                    <span class="badge bg-warning text-dark">Waiting</span>
                                @endif
                                @if ($session->status === 'in_agent')
                                    <span class="badge bg-success text-dark">active</span>
                                @endif
                            </div>
                            <p class="mb-1"><strong>User:</strong>{{ $session->chat->user->name }}</p>
                            <p class="mb-1"><strong>Registered:</strong>
                                {{ \Carbon\Carbon::parse($session->chat->user->created_at)->format('d M Y') }}</p>
                            <p class="mb-1"><strong>Orders Count:</strong> 12</p>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-end">
                            <a href="{{ route('agent.sessions.join', $session->id) }}"
                                class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-eye me-1"></i> Join
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <p id="emptySessions" class="d-none">There is no sessions waiting right now.</p>
            @endforelse
            <!-- /Example -->

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const existingSessionIds = [
            @foreach ($sessions as $session)
                "{{ $session->id }}",
            @endforeach
        ];

        function toggleEmptyMessage() {
            const hasSessions = $("#sessionsList").children().length > 0;
            if (hasSessions) {
                $("#emptySessions").addClass("d-none");
            } else {
                $("#emptySessions").removeClass("d-none");
            }
        }

        function appendSessionCard(id, data) {
            // تأكد إن الكارد مش مكرر
            if (document.getElementById('session-' + id)) return;

            const cardHTML = `
                <div class="col-md-6 col-lg-4 mb-4" id="session-${id}">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-title text-dark mb-0"><strong>Subject:</strong> ${data.name}</p>
                                <span class="badge bg-warning text-dark">Waiting</span>
                            </div>
                            <p class="mb-1"><strong>User:</strong> ${data.user_name ?? 'Unknown'}</p>
                            <p class="mb-1"><strong>Registered:</strong> ${data.user_registered ?? '-'}</p>
                            <p class="mb-1"><strong>Orders Count:</strong> ${data.orders_count ?? 0}</p>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-end">
                            <a href="/agent/sessions/${id}/join" class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-eye me-1"></i> Join
                            </a>
                        </div>
                    </div>
                </div>
            `;

            $("#sessionsList").prepend(cardHTML);

            $(`#session-${id}`).hide().fadeIn(500);
            toggleEmptyMessage();
        }

        sessionsRef.on('child_added', (snapshot) => {
            const data = snapshot.val();
            const sessionId = snapshot.key;

            // ✅ ignore sessions from mysql
            if (existingSessionIds.includes(sessionId.toString())) return;

            if (data.status === 'waiting_agent' && data.agent_id == currentAgentId) {
                appendSessionCard(sessionId, data);
            }
        });

        sessionsRef.on('value', (snapshot) => {
            const sessions = snapshot.val() || {};

            for (const sessionId in sessions) {
                const data = sessions[sessionId];

                const card = $(`#session-${sessionId}`);
                if (data.status !== 'waiting_agent' || data.agent_id != currentAgentId) {
                    card.fadeOut(400, function() { $(this).remove(); });
                    toggleEmptyMessage();
                } else if (!card.length) {
                    appendSessionCard(sessionId, data);
                }
            }

            toggleEmptyMessage();
        });

        $(document).ready(() => {
            toggleEmptyMessage();
        });
    </script>
@endsection
