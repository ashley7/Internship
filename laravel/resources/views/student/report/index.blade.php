@extends('layouts.app')
@section('title', 'My Reports')
@section('page-title', 'My Reports')

@section('sidebar-links')
    <div class="sidebar-section">Main</div>
    <a href="{{ route('student.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-1x2"></i> Dashboard</a>
    <div class="sidebar-section">Reports</div>
    <a href="{{ route('student.reports') }}" class="sidebar-link active"><i class="bi bi-file-earmark-text"></i> My Reports</a>
    <a href="{{ route('student.reports.create') }}" class="sidebar-link"><i class="bi bi-plus-circle"></i> Submit Report</a>
    <div class="sidebar-section">Export</div>
    <a href="{{ route('student.report.generate') }}" class="sidebar-link"><i class="bi bi-file-earmark-arrow-down"></i> Full Report</a>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold" style="color:#1a5276">My Daily Reports</h5>
        <small class="text-muted">{{ $reports->total() }} report(s) submitted</small>
    </div>
    <a href="{{ route('student.reports.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Submit Report
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr><th>Date Submitted</th><th>Preview</th><th>Status</th><th>Attachments</th><th>Notes</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $report->date_submitted->format('d M Y') }}</div>
                            <small class="text-muted">{{ $report->date_submitted->format('l') }}</small>
                        </td>
                        <td style="max-width:280px;">
                            <p class="mb-0 text-truncate text-muted" style="font-size:.82rem;">
                                {{ Str::limit($report->report, 80) }}
                            </p>
                        </td>
                        <td><span class="status-badge badge-{{ $report->status }}">{{ ucfirst($report->status) }}</span></td>
                        <td>
                            @if($report->attachments->count())
                                <span class="badge bg-light text-dark border"><i class="bi bi-paperclip me-1"></i>{{ $report->attachments->count() }}</span>
                            @else <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($report->notes->count())
                                <span class="badge bg-light text-dark border"><i class="bi bi-chat me-1"></i>{{ $report->notes->count() }}</span>
                            @else <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('student.reports.show', $report) }}" class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($report->status === 'pending')
                                <a href="{{ route('student.reports.edit', $report) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-5">
                        <i class="bi bi-file-earmark fs-2 d-block mb-2 opacity-25"></i>
                        No reports yet. <a href="{{ route('student.reports.create') }}">Submit your first report.</a>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($reports->hasPages())
    <div class="card-footer bg-transparent">{{ $reports->links() }}</div>
    @endif
</div>
@endsection
