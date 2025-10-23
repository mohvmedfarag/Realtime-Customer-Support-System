@extends('Admin.layout')
@section('content')
<div class="content">
        <div>
            <h1>Agents</h1>
        </div>

        <table class="table">
            <thead>
                <tr class="text-center">
                    <th scope="col">#</th>
                    <th scope="col">Session ID</th>
                    <th scope="col">Session</th>
                    <th scope="col">User ID</th>
                    <th scope="col">User</th>
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
                    <td><i class="fa-solid fa-eye fa-lg" style="cursor: pointer"></i></td>
                </tr>
                @empty
                There are no sessions
                @endforelse

            </tbody>
        </table>
    </div>
@endsection

{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="container">
        <div>
            <h1>Agents</h1>
        </div>

        <table class="table">
            <thead>
                <tr class="text-center">
                    <th scope="col">#</th>
                    <th scope="col">Session ID</th>
                    <th scope="col">Session</th>
                    <th scope="col">User ID</th>
                    <th scope="col">User</th>
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
                    <td><i class="fa-solid fa-eye fa-lg" style="cursor: pointer"></i></td>
                </tr>
                @empty
                There are no sessions
                @endforelse

            </tbody>
        </table>
    </div>
</body>

</html> --}}
