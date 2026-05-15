@extends('Admin.layout')

@section('content')

<div class="content p-4">

    <!-- Header -->
    <div class="mb-4">

        <a href="{{ route('dashboard.agents') }}"
            class="text-decoration-none text-secondary mb-3 d-inline-block">

            <i class="fa-solid fa-arrow-left me-2"></i>
            Back to Agents

        </a>

        <h2 class="fw-bold text-white">
            <i class="fa-solid fa-user-plus text-info me-2"></i>
            Create New Agent
        </h2>

        <p class="text-secondary">
            Add a new support agent to your platform
        </p>

    </div>

    <!-- Form Card -->
    <div class="create-agent-card">

        <form action="{{ route('dashboard.agents.store') }}" method="POST">

            @csrf

            <!-- Name -->
            <div class="mb-4">

                <label class="form-label text-light">
                    Full Name
                </label>

                <input type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="form-control custom-input @error('name') is-invalid @enderror"
                    placeholder="Enter agent name">

                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <!-- Email -->
            <div class="mb-4">

                <label class="form-label text-light">
                    Email Address
                </label>

                <input type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control custom-input @error('email') is-invalid @enderror"
                    placeholder="Enter email">

                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <!-- Password -->
            <div class="mb-4">

                <label class="form-label text-light">
                    Password
                </label>

                <input type="password"
                    name="password"
                    class="form-control custom-input @error('password') is-invalid @enderror"
                    placeholder="Enter password">

                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <!-- Department -->
            <div class="mb-4">

                <label class="form-label text-light">
                    Department
                    <span class="text-secondary">(Optional)</span>
                </label>

                <select name="department_id"
                    class="form-select custom-input">

                    <option value="">
                        No Department
                    </option>

                    @foreach ($departments as $department)

                        <option value="{{ $department->id }}"
                            {{ old('department_id') == $department->id ? 'selected' : '' }}>

                            {{ $department->name }}

                        </option>

                    @endforeach

                </select>

            </div>

            <!-- Buttons -->
            <div class="d-flex gap-3">

                <button type="submit"
                    class="btn btn-info px-4 py-2 rounded-4 fw-semibold">

                    <i class="fa-solid fa-check me-2"></i>
                    Create Agent

                </button>

                <a href="{{ route('dashboard.agents') }}"
                    class="btn btn-outline-light px-4 py-2 rounded-4">

                    Cancel

                </a>

            </div>

        </form>

    </div>

</div>

<style>
    .create-agent-card{
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 30px;
        padding: 35px;
        backdrop-filter: blur(18px);
        max-width: 750px;
    }

    .custom-input{
        background: rgba(255,255,255,0.06) !important;
        border: 1px solid rgba(255,255,255,0.08) !important;
        color: white !important;
        border-radius: 18px;
        padding: 14px 18px;
    }

    .custom-input:focus{
        box-shadow: 0 0 0 4px rgba(34,211,238,0.15) !important;
        border-color: #22d3ee !important;
    }

    .custom-input::placeholder{
        color: #94a3b8;
    }

    .form-select option{
        background: #0f172a;
        color: white;
    }

    .form-label{
        margin-bottom: 10px;
        font-weight: 500;
    }
</style>

@endsection
