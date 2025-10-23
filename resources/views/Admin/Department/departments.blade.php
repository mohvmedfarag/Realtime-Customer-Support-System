@extends('Admin.layout')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0 rounded-4 p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>📋 الإدارات</h4>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                ➕ إضافة إدارة جديدة
            </button>
        </div>

        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>اسم الإدارة</th>
                    <th>عدد الوكلاء</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($departments as $index => $department)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ ucfirst($department->name) }}</td>
                        <td>{{ $department->agents_count ?? 0 }}</td>
                        <td>
                            <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editDepartmentModal{{ $department->id }}">
                                ✏️ تعديل
                            </button>

                            <form action="" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm" onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                    🗑️ حذف
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal تعديل الإدارة -->
                    <div class="modal fade" id="editDepartmentModal{{ $department->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">تعديل الإدارة</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" name="name" class="form-control" value="{{ $department->name }}" required>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                        <button type="submit" class="btn btn-success">💾 حفظ التعديلات</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                @empty
                    <tr>
                        <td colspan="4" class="text-muted">لا توجد إدارات بعد</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal إضافة إدارة جديدة -->
<div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('dashboard.departments.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">إضافة إدارة جديدة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="name" class="form-control" placeholder="أدخل اسم الإدارة" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">➕ إضافة</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
