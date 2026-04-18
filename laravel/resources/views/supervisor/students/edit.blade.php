@extends('layouts.app')
@section('title', 'Edit Intern Doctor')
@section('page-title', 'Edit Intern Doctor')

@section('sidebar-links')
    <div class="sidebar-section">Main</div>
    <a href="{{ route('supervisor.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-1x2"></i> Dashboard</a>
    <div class="sidebar-section">Management</div>
    <a href="{{ route('supervisor.students') }}" class="sidebar-link active"><i class="bi bi-people"></i> All Intern Doctors</a>
    <a href="{{ route('supervisor.reports') }}" class="sidebar-link"><i class="bi bi-file-earmark-text"></i> Reports</a>
@endsection

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('supervisor.students') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h5 class="mb-0 fw-bold" style="color:#1a5276">Edit Intern Doctor: {{ $student->user->name }}</h5>
        <small class="text-muted">Leave password blank to keep existing</small>
    </div>
</div>

<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header"><i class="bi bi-pencil me-2"></i>Intern Details</div>
    <div class="card-body p-4">
        <form action="{{ route('supervisor.students.update', $student) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $student->user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $student->user->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $student->user->phone) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="internship_start_date" class="form-control"
                           value="{{ old('internship_start_date', $student->internship_start_date?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">End Date</label>
                    <input type="date" name="internship_end_date" class="form-control"
                           value="{{ old('internship_end_date', $student->internship_end_date?->format('Y-m-d')) }}">
                </div>
                <div class="col-12 pt-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2 me-2"></i>Save Changes</button>
                        <a href="{{ route('supervisor.students') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
