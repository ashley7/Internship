@extends('layouts.app')
@section('title', 'Add Intern Doctor')
@section('page-title', 'Add Intern Doctor')

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
        <h5 class="mb-0 fw-bold" style="color:#1a5276">Add Intern</h5>
        <small class="text-muted">Intern will use these credentials to submit daily reports</small>
    </div>
</div>

<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header"><i class="bi bi-person-plus me-2"></i>Intern Details</div>
    <div class="card-body p-4">
        <form action="{{ route('supervisor.students.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="Intern's full name" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="intern@example.com" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+256 700 000 000">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           placeholder="Min. 8 characters" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirm Password *</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Internship Start Date</label>
                    <input type="date" name="internship_start_date" class="form-control" value="{{ old('internship_start_date') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Internship End Date</label>
                    <input type="date" name="internship_end_date" class="form-control" value="{{ old('internship_end_date') }}">
                </div>
                <div class="col-12 pt-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2 me-2"></i>Create Account</button>
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
