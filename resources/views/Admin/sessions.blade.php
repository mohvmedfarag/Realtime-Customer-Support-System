@extends('Admin.layout')
@section('content')
<div class="content">
        <div class="d-flex">
            <h3>Sessions</h3>
            <h3 style="margin-left: 50px">Count: {{ $count }}</h3>
        </div>

        <table class="table">
            <thead>
                <tr class="text-center">
                    <th scope="col">#</th>
                    <th scope="col">Session ID</th>
                    <th scope="col">Session</th>
                    <th scope="col">User ID</th>
                    <th scope="col">User</th>
                    <th scope="col">Status</th>
                    <th scope="col">History</th>
                </tr>
            </thead>
            <tbody>

                @forelse($sessions as $session)
                <tr class="text-center">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $session->session_id }}</td>
                    <td>{{ $session->session_name ?? 'بدون اسم' }}</td>
                    <td>{{ $session->user_id }}</td>
                    <td>{{ $session->user_name }}</td>
                    @if ($session->status == 'closed' || $session->status == 'bot')
                        <td><span class="sessionStatus bg-danger">closed</span></td>
                    @elseif ($session->status == 'waiting_agent')
                        <td><span class="sessionStatus bg-warning">waiting</span></td>
                    @else
                        <td><span class="sessionStatus bg-success">active</span></td>
                    @endif
                    <td><i class="fa-solid fa-eye fa-lg" style="cursor: pointer"></i></td>
                </tr>
                @empty
                There are no sessions
                @endforelse

            </tbody>
        </table>
    </div>
@endsection
