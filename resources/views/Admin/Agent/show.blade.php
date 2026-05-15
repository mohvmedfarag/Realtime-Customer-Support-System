@extends('Admin.layout')
@section('content')
    <div class="content container mt-4">

        <div class="card shadow-sm border-0 rounded-4 p-3 position-relative">

            <a href="#" data-bs-toggle="modal" data-bs-target="#editAgentModal"
                class="btn btn-outline-warning btn-sm position-absolute top-0 end-0 m-3 d-flex align-items-center gap-1">
                <i class="fa-solid fa-pen-to-square"></i> تعديل
            </a>

            <div class="d-flex align-items-center">
                <div>
                    <h4 class="mb-1">{{ $agent->name }}</h4>
                    <p class="text-muted mb-2">{{ $agent->email }}</p>
                    <span class="badge {{ $agent->status == 'online' ? 'bg-success' : 'bg-secondary' }}">
                        {{ ucfirst($agent->status) }}
                    </span>
                </div>
            </div>

            <hr>

            <div class="row text-center">
                <div class="col-md-3 mb-3">
                    <h6 class="text-muted mb-1">📞 عدد الجلسات</h6>
                    <h5>0</h5>
                </div>
                <div class="col-md-3 mb-3">
                    <h6 class="text-muted mb-1">📊 التقييم</h6>
                    <h5>4⭐</h5>
                </div>
                <div class="col-md-3 mb-3">
                    <h6 class="text-muted mb-1">💬 الرسائل المرسلة</h6>
                    <h5>0</h5>
                </div>
                <div class="col-md-3 mb-3">
                    <h6 class="text-muted mb-1"><i class="fa-solid fa-building-user"></i> الادارة</h6>
                    <h5>{{ $agent->department->name ?? 'لا يوجد' }}</h5>
                </div>
            </div>

            <hr>

            <div class="text-end">
                <a href="{{ route('dashboard.agents') }}" class="btn btn-outline-primary btn-sm">
                    ← الرجوع لقائمة الوكلاء
                </a>
            </div>
        </div>

    </div>

    <div class="modal fade" id="editAgentModal" tabindex="-1" aria-labelledby="editAgentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('dashboard.agents.update', $agent->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">القسم</label>
                            <select name="department" class="form-select">
                                <option value="{{ $agent->department->id ?? 'null' }}" selected hidden>
                                    {{ $agent->department->name ?? 'بدون قسم' }}
                                </option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach

                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-success">💾 حفظ التغييرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
