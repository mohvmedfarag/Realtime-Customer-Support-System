@extends('Agent.layout')
@section('content')
    <div class="container mt-4">
    <h3 class="mb-4 text-primary fw-bold">Waiting Sessions</h3>

    <div class="row" id="sessionsList">
        <!-- Example Session Card -->
        @forelse ($sessions as $session)
        <div class="col-md-6 col-lg-4 mb-4">
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
                    <a href="{{ route('agent.sessions.join', $session->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fa fa-eye me-1"></i> Join
                    </a>
                </div>
            </div>
        </div>
        @empty
        There is no sessions waiting right now.
        @endforelse
        <!-- /Example -->

    </div>
</div>
@endsection
