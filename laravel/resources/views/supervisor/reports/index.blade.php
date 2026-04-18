@extends('layouts.app')
@section('title', 'Reports')
@section('page-title', 'Student Reports')

@section('sidebar-links')
    <div class="sidebar-section">Main</div>
    <a href="{{ route('supervisor.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-1x2"></i> Dashboard</a>
    <div class="sidebar-section">Management</div>
    <a href="{{ route('supervisor.students') }}" class="sidebar-link"><i class="bi bi-people"></i> My Students</a>
    <a href="{{ route('supervisor.reports') }}" class="sidebar-link active"><i class="bi bi-file-earmark-text"></i> Reports</a>
@endsection

@section('content')
<div class="card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('supervisor.reports') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label mb-1">Student</label>
                <select name="student_id" class="form-select form-select-sm">
                    <option value="">All Students</option>
                    @foreach($myStudents as $s)
                        <option value="{{ $s->id }}" {{ ($filters['student_id'] ?? '') == $s->id ? 'selected' : '' }}>
                            {{ $s->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="pending"  {{ ($filters['status'] ?? '') === 'pending'   ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ ($filters['status'] ?? '') === 'approved'  ? 'selected' : '' }}>Approved</option>
                    <option value="declined" {{ ($filters['status'] ?? '') === 'declined'  ? 'selected' : '' }}>Declined</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1">From</label>
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ $filters['date_from'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1">To</label>
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ $filters['date_to'] ?? '' }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm px-3"><i class="bi bi-search me-1"></i>Filter</button>
                <a href="{{ route('supervisor.reports') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="bi bi-file-earmark-text me-2"></i>Reports ({{ $reports->total() }})</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr><th>Student</th><th>Date</th><th>Status</th><th>Attachments</th><th>Notes</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr>
                        <td>
                            <strong>{{ $report->student->user->name }}</strong><br>
                            <small class="text-muted">{{ $report->student->student_number }}</small>
                        </td>
                        <td>{{ $report->date_submitted->format('d M Y') }}</td>
                        <td>
                            <span class="status-badge badge-{{ $report->status }}">{{ ucfirst($report->status) }}</span>
                        </td>
                        <td>
                            @if($report->attachments->count())
                                <span class="badge bg-light text-dark border"><i class="bi bi-paperclip me-1"></i>{{ $report->attachments->count() }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($report->notes->count())
                                <span class="badge bg-light text-dark border"><i class="bi bi-chat me-1"></i>{{ $report->notes->count() }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('supervisor.reports.show', $report) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye me-1"></i>Review
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-5">
                        <i class="bi bi-file-earmark fs-2 d-block mb-2 opacity-25"></i>No reports found.
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($reports->hasPages())
    <div class="card-footer bg-transparent">{{ $reports->appends($filters)->links() }}</div>
    @endif
</div>
@endsection
