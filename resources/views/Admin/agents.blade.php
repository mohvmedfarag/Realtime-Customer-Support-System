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
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Status</th>
                    <th scope="col">Show</th>
                    <th scope="col">Record</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($agents as $agent)
                    <tr class="text-center">
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $agent->id }}</td>
                        <td>{{ $agent->name }}</td>
                        <td>{{ $agent->status }}</td>
                        <td><a href="{{ route('dashboard.agents.show', $agent->id) }}"><i class="fa-solid fa-eye fa-lg" style="cursor: pointer"></i></a></td>
                        <td>
                            <i class="fa-solid fa-circle text-success"></i> 50%
                        </td>
                    </tr>
                @empty
                There are no agents
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
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Status</th>
                    <th scope="col">Show</th>
                    <th scope="col">Record</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($agents as $agent)
                    <tr class="text-center">
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $agent->id }}</td>
                        <td>{{ $agent->name }}</td>
                        <td>{{ $agent->status }}</td>
                        <td><i class="fa-solid fa-eye fa-lg" style="cursor: pointer"></i></td>
                        <td>
                            <i class="fa-solid fa-circle text-success"></i> 50%
                        </td>
                    </tr>
                @empty
                There are no agents
                @endforelse

            </tbody>
        </table>
    </div>
</body>

</html> --}}
