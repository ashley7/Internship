@extends('layouts.app')
@section('title', 'My Dashboard')
@section('page-title', 'Dashboard')

@section('sidebar-links')
    <div class="sidebar-section">Main</div>
    <a href="{{ route('student.dashboard') }}" class="sidebar-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2"></i> Dashboard
    </a>
    <div class="sidebar-section">Reports</div>
    <a href="{{ route('student.reports') }}" class="sidebar-link {{ request()->routeIs('student.reports*') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-text"></i> My Reports
    </a>
    <a href="{{ route('student.reports.create') }}" class="sidebar-link {{ request()->routeIs('student.reports.create') ? 'active' : '' }}">
        <i class="bi bi-plus-circle"></i> Submit Report
    </a>
    <div class="sidebar-section">Export</div>
    <a href="{{ route('student.report.generate') }}" class="sidebar-link">
        <i class="bi bi-file-earmark-arrow-down"></i> Full Report
    </a>
@endsection

@section('content')
@if(!$hasToday)
<div class="alert alert-warning d-flex align-items-center gap-3 mb-4">
    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
    <div>
        <strong>Reminder:</strong> You haven't submitted a report for today yet.
        <a href="{{ route('student.reports.create') }}" class="alert-link ms-2">Submit now →</a>
    </div>
</div>
@endif

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card" style="background:linear-gradient(135deg,#1a5276,#2e86c1);color:#fff;">
            <div class="stat-icon" style="background:rgba(255,255,255,.15)"><i class="bi bi-file-earmark-text" style="color:#fff"></i></div>
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Reports</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card" style="background:linear-gradient(135deg,#b7770d,#f39c12);color:#fff;">
            <div class="stat-icon" style="background:rgba(255,255,255,.15)"><i class="bi bi-clock-history" style="color:#fff"></i></div>
            <div class="stat-value">{{ $stats['pending'] }}</div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card" style="background:linear-gradient(135deg,#1a7a4a,#27ae60);color:#fff;">
            <div class="stat-icon" style="background:rgba(255,255,255,.15)"><i class="bi bi-check-circle" style="color:#fff"></i></div>
            <div class="stat-value">{{ $stats['approved'] }}</div>
            <div class="stat-label">Approved</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card" style="background:linear-gradient(135deg,#922b21,#c0392b);color:#fff;">
            <div class="stat-icon" style="background:rgba(255,255,255,.15)"><i class="bi bi-x-circle" style="color:#fff"></i></div>
            <div class="stat-value">{{ $stats['declined'] }}</div>
            <div class="stat-label">Declined</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2"></i>Recent Reports</span>
                <a href="{{ route('student.reports') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr><th>Date</th><th>Status</th><th>Attachments</th><th></th></tr>
                        </thead>
                        <tbody>
                            @forelse($recentReports as $report)
                            <tr>
                                <td>{{ $report->date_submitted->format('d M Y') }}</td>
                                <td><span class="status-badge badge-{{ $report->status }}">{{ ucfirst($report->status) }}</span></td>
                                <td>
                                    @if($report->attachments->count())
                                        <span class="badge bg-light text-dark border"><i class="bi bi-paperclip me-1"></i>{{ $report->attachments->count() }}</span>
                                    @else <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('student.reports.show', $report) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No reports submitted yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-person-badge me-2"></i>My Supervisor</div>
            <div class="card-body text-center py-4">
                <div style="width:56px;height:56px;border-radius:50%;background:#d6eaf8;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.2rem;color:#1a5276;margin:0 auto 12px;">
                    {{ strtoupper(substr($student->supervisor->name, 0, 2)) }}
                </div>
                <div class="fw-bold">{{ $student->supervisor->name }}</div>
                <div class="text-muted small">{{ $student->supervisor->email }}</div>
                <div class="text-muted small mt-1">{{ $student->supervisor->phone ?? '' }}</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="bi bi-lightning-charge me-2"></i>Quick Actions</div>
            <div class="card-body d-flex flex-column gap-2">
                @if(!$hasToday)
                <a href="{{ route('student.reports.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Submit Today's Report
                </a>
                @else
                <div class="alert alert-success mb-0 py-2 text-center" style="font-size:.82rem;">
                    <i class="bi bi-check-circle me-1"></i> Today's report submitted!
                </div>
                @endif
                <a href="{{ route('student.report.generate') }}" class="btn btn-outline-primary">
                    <i class="bi bi-file-earmark-arrow-down me-2"></i>Generate Full Report
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
