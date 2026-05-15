@extends('Admin.layout')

@section('content')
    <div class="content">

        <!-- Header -->
        <div
            class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">

            <div>
                <h1 class="fw-bold mb-1 text-white">
                    Departments
                </h1>

                <p class="text-secondary mb-0">
                    Manage support departments and organize your agents.
                </p>
            </div>

            <button class="btn add-btn" data-bs-toggle="modal"
                data-bs-target="#addDepartmentModal">

                <i class="fa-solid fa-plus"></i>
                New Department

            </button>

        </div>

        <!-- Departments Card -->
        <div class="glass-card">

            <div class="table-responsive">

                <table class="table align-middle text-center mb-0 custom-table">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Department</th>
                            <th>Agents</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($departments as $department)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>

                                    <div
                                        class="d-flex align-items-center justify-content-center gap-3">

                                        <div class="department-icon">
                                            <i class="fa-solid fa-building"></i>
                                        </div>

                                        <div class="text-start">

                                            <h6 class="mb-0 fw-semibold text-white">
                                                {{ ucfirst($department->name) }}
                                            </h6>

                                            <small class="text-secondary">
                                                Department :
                                                {{ $department->name }}
                                            </small>

                                        </div>

                                    </div>

                                </td>

                                <td>

                                    <span class="agents-badge">

                                        <i class="fa-solid fa-headset"></i>

                                        {{ $department->agents_count ?? 0 }}

                                    </span>

                                </td>

                                <td>

                                    <div
                                        class="d-flex justify-content-center gap-2 flex-wrap">

                                        <!-- Edit -->
                                        <button class="action-btn edit-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editDepartmentModal{{ $department->id }}">

                                            <i class="fa-solid fa-pen"></i>

                                        </button>

                                        <!-- Delete -->
                                        <form action="" method="POST">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="action-btn delete-btn"
                                                onclick="return confirm('Are you sure you want to delete this department?')">

                                                <i class="fa-solid fa-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade"
                                id="editDepartmentModal{{ $department->id }}"
                                tabindex="-1" aria-hidden="true">

                                <div class="modal-dialog modal-dialog-centered">

                                    <div class="modal-content custom-modal">

                                        <form action="" method="POST">

                                            @csrf
                                            @method('PUT')

                                            <div class="modal-header border-0">

                                                <h5 class="modal-title text-white">
                                                    Edit Department
                                                </h5>

                                                <button type="button"
                                                    class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal"></button>

                                            </div>

                                            <div class="modal-body">

                                                <label
                                                    class="form-label text-secondary mb-2">

                                                    Department Name

                                                </label>

                                                <input type="text" name="name"
                                                    class="form-control custom-input"
                                                    value="{{ $department->name }}"
                                                    required>

                                            </div>

                                            <div
                                                class="modal-footer border-0">

                                                <button type="button"
                                                    class="btn cancel-btn"
                                                    data-bs-dismiss="modal">

                                                    Cancel

                                                </button>

                                                <button type="submit"
                                                    class="btn save-btn">

                                                    <i
                                                        class="fa-solid fa-floppy-disk"></i>

                                                    Save

                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <tr>

                                <td colspan="4">

                                    <div class="empty-state">

                                        <i
                                            class="fa-solid fa-building-circle-xmark"></i>

                                        <h5 class="mt-3">
                                            No Departments Found
                                        </h5>

                                        <p class="text-secondary mb-0">
                                            Start by creating your first
                                            department.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <!-- Add Department Modal -->
    <div class="modal fade" id="addDepartmentModal" tabindex="-1"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content custom-modal">

                <form action="{{ route('dashboard.departments.store') }}"
                    method="POST">

                    @csrf

                    <div class="modal-header border-0">

                        <h5 class="modal-title text-white">
                            Create New Department
                        </h5>

                        <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"></button>

                    </div>

                    <div class="modal-body">

                        <label class="form-label text-secondary mb-2">

                            Department Name

                        </label>

                        <input type="text" name="name"
                            class="form-control custom-input"
                            placeholder="Enter department name" required>

                    </div>

                    <div class="modal-footer border-0">

                        <button type="button" class="btn cancel-btn"
                            data-bs-dismiss="modal">

                            Cancel

                        </button>

                        <button type="submit" class="btn save-btn">

                            <i class="fa-solid fa-plus"></i>

                            Create

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <style>
        /* Glass Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(18px);
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.35);
        }

        /* Table */
        .custom-table {
            color: white;
            margin: 0;
        }

        .custom-table thead {
            background: rgba(255, 255, 255, 0.03);
        }

        .custom-table thead th {
            padding: 22px;
            border: none;
            color: #94a3b8;
            font-size: 14px;
            font-weight: 600;
        }

        .custom-table tbody td {
            padding: 22px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            vertical-align: middle;
        }

        .custom-table tbody tr {
            transition: 0.25s;
        }

        .custom-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        /* Department Icon */
        .department-icon {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            background: rgba(6, 182, 212, 0.15);
            color: #22d3ee;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            border: 1px solid rgba(34, 211, 238, 0.2);
        }

        /* Badge */
        .agents-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(6, 182, 212, 0.12);
            color: #67e8f9;
            padding: 10px 16px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            border: 1px solid rgba(34, 211, 238, 0.15);
        }

        /* Buttons */
        .add-btn {
            background: linear-gradient(135deg, #06b6d4, #22d3ee);
            color: black;
            border: none;
            border-radius: 18px;
            padding: 12px 22px;
            font-weight: 700;
            box-shadow: 0 0 25px rgba(6, 182, 212, 0.35);
        }

        .add-btn:hover {
            transform: translateY(-2px);
            color: black;
        }

        .action-btn {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            border: none;
            transition: 0.25s;
        }

        .edit-btn {
            background: rgba(250, 204, 21, 0.12);
            color: #facc15;
        }

        .edit-btn:hover {
            background: #facc15;
            color: black;
        }

        .delete-btn {
            background: rgba(239, 68, 68, 0.12);
            color: #ef4444;
        }

        .delete-btn:hover {
            background: #ef4444;
            color: white;
        }

        /* Empty */
        .empty-state {
            padding: 70px 20px;
            text-align: center;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 70px;
            color: #334155;
        }

        /* Modal */
        .custom-modal {
            background: #0f172a;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
        }

        .custom-input {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: white;
            height: 52px;
            border-radius: 16px;
        }

        .custom-input:focus {
            background: rgba(255, 255, 255, 0.06);
            border-color: #06b6d4;
            box-shadow: 0 0 0 0.2rem rgba(6, 182, 212, 0.15);
            color: white;
        }

        .custom-input::placeholder {
            color: #64748b;
        }

        .cancel-btn {
            background: rgba(255, 255, 255, 0.06);
            color: #cbd5e1;
            border-radius: 14px;
            padding: 10px 18px;
        }

        .save-btn {
            background: linear-gradient(135deg, #06b6d4, #22d3ee);
            color: black;
            border-radius: 14px;
            padding: 10px 18px;
            font-weight: 700;
            border: none;
        }

        .save-btn:hover {
            color: black;
        }
    </style>
@endsection
