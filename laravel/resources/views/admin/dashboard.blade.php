@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('sidebar-links')
    <div class="sidebar-section">Main</div>
    <a href="{{ route('super_admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('super_admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2"></i> Dashboard
    </a>
    <div class="sidebar-section">Management</div>
    <a href="{{ route('super_admin.supervisors') }}" class="sidebar-link {{ request()->routeIs('super_admin.supervisors*') ? 'active' : '' }}">
        <i class="bi bi-person-badge"></i> Supervisors
    </a>
    <a href="{{ route('super_admin.students') }}" class="sidebar-link {{ request()->routeIs('super_admin.students*') ? 'active' : '' }}">
        <i class="bi bi-people"></i> Intern Doctors
    </a>
@endsection

@section('content')
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card" style="background: linear-gradient(135deg,#1a5276,#2e86c1); color:#fff;">
            <div class="stat-icon" style="background:rgba(255,255,255,.15)"><i class="bi bi-person-badge" style="color:#fff"></i></div>
            <div class="stat-value">{{ $stats['supervisors'] }}</div>
            <div class="stat-label">Supervisors</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card" style="background: linear-gradient(135deg,#1a7a4a,#27ae60); color:#fff;">
            <div class="stat-icon" style="background:rgba(255,255,255,.15)"><i class="bi bi-people" style="color:#fff"></i></div>
            <div class="stat-value">{{ $stats['students'] }}</div>
            <div class="stat-label">Intern Doctors</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card" style="background: linear-gradient(135deg,#7d3c98,#9b59b6); color:#fff;">
            <div class="stat-icon" style="background:rgba(255,255,255,.15)"><i class="bi bi-file-earmark-text" style="color:#fff"></i></div>
            <div class="stat-value">{{ $stats['reports'] }}</div>
            <div class="stat-label">Total Reports</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card" style="background: linear-gradient(135deg,#b7770d,#f39c12); color:#fff;">
            <div class="stat-icon" style="background:rgba(255,255,255,.15)"><i class="bi bi-clock-history" style="color:#fff"></i></div>
            <div class="stat-value">{{ $stats['pending'] }}</div>
            <div class="stat-label">Pending Review</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-person-badge me-2"></i>Supervisors Overview</span>
                <a href="{{ route('super_admin.supervisors.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg me-1"></i>Add Supervisor
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Intern Doctors</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSupervisors as $sv)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width:32px;height:32px;border-radius:50%;background:#d6eaf8;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.75rem;color:#1a5276;">
                                            {{ strtoupper(substr($sv->name, 0, 2)) }}
                                        </div>
                                        <strong>{{ $sv->name }}</strong>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $sv->email }}</td>
                                <td> <small class="text-muted">{{ $sv->phone }}</small></td>
                                <td><span class="badge bg-light text-dark border">{{ $sv->supervised_students_count }} Intern Doctors</span></td>
                                <td>
                                    <span class="status-badge {{ $sv->is_active ? 'badge-approved' : 'badge-declined' }}">
                                        {{ $sv->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('super_admin.supervisors.edit', $sv) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No supervisors yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($recentSupervisors->count())
            <div class="card-footer bg-transparent border-top-0 text-end">
                <a href="{{ route('super_admin.supervisors') }}" class="btn btn-sm btn-outline-primary">View All Supervisors</a>
            </div>
            @endif
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-lightning-charge me-2"></i>Quick Actions</div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('super_admin.supervisors.create') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus me-2"></i>Create Supervisor Account
                </a>
                <a href="{{ route('super_admin.supervisors') }}" class="btn btn-outline-primary">
                    <i class="bi bi-person-badge me-2"></i>Manage Supervisors
                </a>
                <a href="{{ route('super_admin.students') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-people me-2"></i>View All Intern Doctors
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
