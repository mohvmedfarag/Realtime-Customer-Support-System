@extends('Admin.layout')

@section('content')
    <div class="content p-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

            <div>
                <h2 class="fw-bold mb-1">
                    <i class="fa-solid fa-headset text-primary"></i>
                    Agents Management
                </h2>

                <p class="text-muted mb-0" style="color: rgb(150 150 150 / 75%) !important;">
                    Manage support agents and monitor their activity
                </p>
            </div>

            <a href="{{ route('dashboard.agents.create') }}"
                class="btn btn-primary px-4 py-2 rounded-3 shadow-sm">

                <i class="fa-solid fa-plus me-2"></i>
                New Agent

            </a>

        </div>

        <!-- Card -->
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

            <!-- Table Header -->
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-semibold">
                    Agents List
                </h5>
            </div>

            <!-- Table -->
            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead class="table-light">

                        <tr class="text-center">
                            <th>#</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Ratings</th>
                            <th>Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($agents as $agent)
                            <tr class="text-center">

                                <td class="fw-semibold">
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    #{{ $agent->id }}
                                </td>

                                <td>

                                    <div class="d-flex align-items-center justify-content-center gap-2">

                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                            style="width:40px;height:40px;">

                                            {{ strtoupper(substr($agent->name, 0, 1)) }}

                                        </div>

                                        <span class="fw-medium">
                                            {{ $agent->name }}
                                        </span>

                                    </div>

                                </td>

                                <td class="text-muted">
                                    {{ $agent->email }}
                                </td>

                                <td>

                                    @if ($agent->status == 'online')
                                        <span class="badge bg-success px-3 py-2 rounded-pill">
                                            Online
                                        </span>
                                    @elseif($agent->status == 'offline')
                                        <span class="badge bg-secondary px-3 py-2 rounded-pill">
                                            Offline
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                            Busy
                                        </span>
                                    @endif

                                </td>

                                <td>

                                    <span class="badge bg-info text-dark px-3 py-2 rounded-pill">

                                        <i class="fa-solid fa-star me-1"></i>

                                        {{ $agent->receivedRatings->count() }}

                                    </span>

                                </td>

                                <td>

                                    <div class="d-flex justify-content-center gap-2">

                                        <!-- Show -->
                                        <a href="{{ route('dashboard.agents.show', $agent->id) }}"
                                            class="btn btn-sm btn-light border rounded-3">

                                            <i class="fa-solid fa-eye text-primary"></i>

                                        </a>

                                        <!-- Edit -->
                                        <a href="#"
                                            class="btn btn-sm btn-light border rounded-3">

                                            <i class="fa-solid fa-pen text-warning"></i>

                                        </a>

                                        <!-- Delete -->
                                        <a href="#"
                                            class="btn btn-sm btn-light border rounded-3">

                                            <i class="fa-solid fa-trash text-danger"></i>

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="text-center py-5">

                                    <div class="d-flex flex-column align-items-center">

                                        <i class="fa-solid fa-users text-muted mb-3"
                                            style="font-size: 50px;"></i>

                                        <h5 class="fw-semibold">
                                            No Agents Found
                                        </h5>

                                        <p class="text-muted mb-3">
                                            Start by creating your first support agent.
                                        </p>

                                        <a href="{{ route('dashboard.agents.create') }}"
                                            class="btn btn-primary rounded-3">

                                            <i class="fa-solid fa-plus me-2"></i>
                                            Create Agent

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>
@endsection
