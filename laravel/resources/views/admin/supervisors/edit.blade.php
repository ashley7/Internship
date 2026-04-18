@extends('layouts.app')
@section('title', 'Edit Supervisor')
@section('page-title', 'Edit Supervisor')

@section('sidebar-links')
    <div class="sidebar-section">Main</div>
    <a href="{{ route('super_admin.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-1x2"></i> Dashboard</a>
    <div class="sidebar-section">Management</div>
    <a href="{{ route('super_admin.supervisors') }}" class="sidebar-link active"><i class="bi bi-person-badge"></i> Supervisors</a>
    <a href="{{ route('super_admin.students') }}" class="sidebar-link"><i class="bi bi-people"></i> Intern Doctors</a>
@endsection

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('super_admin.supervisors') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h5 class="mb-0 fw-bold" style="color:#1a5276">Edit: {{ $user->name }}</h5>
        <small class="text-muted">Leave password blank to keep existing password</small>
    </div>
</div>

<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header"><i class="bi bi-pencil me-2"></i>Supervisor Details</div>
    <div class="card-body p-4">
        <form action="{{ route('super_admin.supervisors.update', $user) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Leave blank to keep current">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
                </div>
                <div class="col-12 pt-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2 me-2"></i>Save Changes</button>
                        <a href="{{ route('super_admin.supervisors') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
