@extends('layouts.app')
@section('title', 'Supervisor Dashboard')
@section('page-title', 'Dashboard')

@section('sidebar-links')
    <div class="sidebar-section">Main</div>
    <a href="{{ route('supervisor.dashboard') }}" class="sidebar-link {{ request()->routeIs('supervisor.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2"></i> Dashboard
    </a>
    <div class="sidebar-section">Management</div>
    <a href="{{ route('supervisor.students') }}" class="sidebar-link {{ request()->routeIs('supervisor.students*') ? 'active' : '' }}">
        <i class="bi bi-people"></i> My Students
    </a>
    <a href="{{ route('supervisor.reports') }}" class="sidebar-link {{ request()->routeIs('supervisor.reports*') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-text"></i> Reports
    </a>
@endsection

@section('content')
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card" style="background:linear-gradient(135deg,#1a5276,#2e86c1);color:#fff;">
            <div class="stat-icon" style="background:rgba(255,255,255,.15)"><i class="bi bi-people" style="color:#fff"></i></div>
            <div class="stat-value">{{ $stats['students'] }}</div>
            <div class="stat-label">My Students</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card" style="background:linear-gradient(135deg,#7d3c98,#9b59b6);color:#fff;">
            <div class="stat-icon" style="background:rgba(255,255,255,.15)"><i class="bi bi-file-earmark-text" style="color:#fff"></i></div>
            <div class="stat-value">{{ $stats['total_reports'] }}</div>
            <div class="stat-label">Total Reports</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card" style="background:linear-gradient(135deg,#b7770d,#f39c12);color:#fff;">
            <div class="stat-icon" style="background:rgba(255,255,255,.15)"><i class="bi bi-clock-history" style="color:#fff"></i></div>
            <div class="stat-value">{{ $stats['pending_reports'] }}</div>
            <div class="stat-label">Pending Review</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card" style="background:linear-gradient(135deg,#1a7a4a,#27ae60);color:#fff;">
            <div class="stat-icon" style="background:rgba(255,255,255,.15)"><i class="bi bi-check-circle" style="color:#fff"></i></div>
            <div class="stat-value">{{ $stats['approved_reports'] }}</div>
            <div class="stat-label">Approved</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2"></i>Recent Reports</span>
                <a href="{{ route('supervisor.reports') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr><th>Student</th><th>Date</th><th>Status</th><th></th></tr>
                        </thead>
                        <tbody>
                            @forelse($recentReports as $report)
                            <tr>
                                <td><strong>{{ $report->student->user->name }}</strong></td>
                                <td class="text-muted">{{ $report->date_submitted->format('d M Y') }}</td>
                                <td>
                                    <span class="status-badge badge-{{ $report->status }}">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('supervisor.reports.show', $report) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Review
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No reports yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people me-2"></i>My Students</span>
                <a href="{{ route('supervisor.students.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg"></i>
                </a>
            </div>
            <div class="card-body p-0">
                @forelse($students as $student)
                <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
                    <div style="width:38px;height:38px;border-radius:50%;background:#d5f5e3;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.8rem;color:#1e8449;flex-shrink:0;">
                        {{ strtoupper(substr($student->user->name, 0, 2)) }}
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:.875rem;">{{ $student->user->name }}</div>
                        <small class="text-muted">{{ $student->school }} &middot; {{ $student->student_number }}</small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-light text-dark border d-block mb-1">{{ $student->reports_count }} reports</span>
                        @if($student->pending_count > 0)
                        <span class="status-badge badge-pending" style="font-size:.68rem;">{{ $student->pending_count }} pending</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-5">
                    <i class="bi bi-people fs-2 d-block mb-2 opacity-25"></i>
                    No students yet. <a href="{{ route('supervisor.students.create') }}">Add one.</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
