@extends('layouts.app')
@section('title', 'Full Internship Report')
@section('page-title', 'Full Internship Report')

@section('sidebar-links')
    <div class="sidebar-section">Main</div>
    <a href="{{ route('student.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-1x2"></i> Dashboard</a>
    <div class="sidebar-section">Reports</div>
    <a href="{{ route('student.reports') }}" class="sidebar-link"><i class="bi bi-file-earmark-text"></i> My Reports</a>
    <a href="{{ route('student.reports.create') }}" class="sidebar-link"><i class="bi bi-plus-circle"></i> Submit Report</a>
    <div class="sidebar-section">Export</div>
    <a href="{{ route('student.report.generate') }}" class="sidebar-link active"><i class="bi bi-file-earmark-arrow-down"></i> Full Report</a>
@endsection

@push('styles')
<style>
    @media print {
        .no-print { display: none !important; }
        body { background: #fff !important; }
        .report-entry { page-break-inside: avoid; }
        #main-wrapper { margin-left: 0 !important; }
    }
    .report-cover {
        background: linear-gradient(135deg, #0d2137 0%, #1a5276 60%, #2e86c1 100%);
        border-radius: 16px;
        padding: 48px 40px;
        color: #fff;
        margin-bottom: 32px;
    }
    .report-entry {
        border-left: 4px solid #e8edf3;
        padding-left: 20px;
        margin-bottom: 32px;
        transition: border-color .2s;
    }
    .report-entry:hover { border-left-color: #2e86c1; }
    .report-entry.approved { border-left-color: #27ae60; }
    .report-entry.declined { border-left-color: #c0392b; }
    .report-entry.pending  { border-left-color: #f39c12; }
</style>
@endpush

@section('content')
<!-- Actions Bar -->
<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <div>
        <h5 class="mb-0 fw-bold" style="color:#1a5276">Full Internship Report</h5>
        <small class="text-muted">Generated on {{ $generated_at->format('d F Y, H:i') }}</small>
    </div>
    <button onclick="window.print()" class="btn btn-primary">
        <i class="bi bi-printer me-2"></i>Print / Save as PDF
    </button>
</div>

<!-- Cover Page -->
<div class="report-cover">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div style="background:rgba(255,255,255,.15);border-radius:12px;width:52px;height:52px;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-hospital fs-4"></i>
        </div>
        <div>
            <div style="font-size:.75rem;letter-spacing:1.5px;text-transform:uppercase;opacity:.7;">Medical Internship Portal</div>
            <div style="font-family:'Lora',serif;font-size:1.4rem;font-weight:700;">Internship Report</div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div style="opacity:.7;font-size:.75rem;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Intern Doctor</div>
            <div style="font-size:1.3rem;font-weight:700;">{{ $student->user->name }}</div>
            <div style="opacity:.8;font-size:.875rem;">{{ $student->user->email }}</div>
            <div style="opacity:.8;font-size:.875rem;">{{ $student->user->phone }}</div>
        </div>
        <div class="col-md-6">
            <div style="opacity:.7;font-size:.75rem;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Institution</div>
            <div style="font-size:1.1rem;font-weight:600;">{{ $student->school }}</div>
            <div style="opacity:.8;font-size:.875rem;">Intern Doctor No: {{ $student->student_number }}</div>
        </div>
        <div class="col-md-6">
            <div style="opacity:.7;font-size:.75rem;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Supervisor</div>
            <div style="font-weight:600;">{{ $student->supervisor->name }}</div>
            <div style="opacity:.8;font-size:.875rem;">{{ $student->supervisor->email }}</div>
        </div>
        <div class="col-md-6">
            <div style="opacity:.7;font-size:.75rem;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Internship Period</div>
            <div style="font-weight:600;">
                @if($student->internship_start_date)
                    {{ $student->internship_start_date->format('d M Y') }} — {{ $student->internship_end_date?->format('d M Y') ?? 'Present' }}
                @else
                    Not specified
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Summary Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card text-center p-3">
            <div style="font-size:2rem;font-weight:800;color:#1a5276;">{{ $total }}</div>
            <div class="text-muted" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">Total Reports</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center p-3" style="border-left:4px solid #27ae60;">
            <div style="font-size:2rem;font-weight:800;color:#1e8449;">{{ $approved }}</div>
            <div class="text-muted" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">Approved</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center p-3" style="border-left:4px solid #c0392b;">
            <div style="font-size:2rem;font-weight:800;color:#c0392b;">{{ $declined }}</div>
            <div class="text-muted" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">Declined</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center p-3" style="border-left:4px solid #f39c12;">
            <div style="font-size:2rem;font-weight:800;color:#d68910;">{{ $pending }}</div>
            <div class="text-muted" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">Pending</div>
        </div>
    </div>
</div>

<!-- Reports -->
<div class="card">
    <div class="card-header">
        <i class="bi bi-journal-text me-2"></i>Daily Reports ({{ $total }})
    </div>
    <div class="card-body p-4">
        @forelse($reports as $report)
        <div class="report-entry {{ $report->status }}">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h6 class="mb-0 fw-bold" style="color:#1a5276;">
                        {{ $report->date_submitted->format('l, d F Y') }}
                    </h6>
                    <small class="text-muted">Report #{{ $loop->iteration }}</small>
                </div>
                <span class="status-badge badge-{{ $report->status }}">{{ ucfirst($report->status) }}</span>
            </div>

            <div class="report-body mb-3" style="font-size:.875rem;">{{ $report->report }}</div>

            @if($report->attachments->count())
            <div class="mb-3">
                <div class="fw-semibold mb-1" style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;color:#6b7c93;">
                    <i class="bi bi-paperclip me-1"></i>Attachments
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($report->attachments as $att)
                    <span class="badge bg-light text-dark border">
                        <i class="bi {{ $att->getIconClass() }} me-1"></i>{{ $att->document_name }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            @if($report->notes->count())
            <div>
                <div class="fw-semibold mb-2" style="font-size:.78rem;text-transform:uppercase;letter-spacing:.5px;color:#6b7c93;">
                    <i class="bi bi-chat me-1"></i>Notes
                </div>
                @foreach($report->notes as $note)
                <div class="note-bubble {{ $note->user->isSupervisor() ? 'supervisor' : 'student' }} mb-2">
                    <div class="d-flex justify-content-between mb-1">
                        <strong style="font-size:.78rem;">{{ $note->user->name }}
                            <span class="badge ms-1 {{ $note->user->isSupervisor() ? 'bg-primary' : 'bg-success' }}" style="font-size:.6rem;">
                                {{ $note->user->isSupervisor() ? 'Supervisor' : 'Student' }}
                            </span>
                        </strong>
                        <small class="text-muted">{{ $note->created_at->format('d M Y') }}</small>
                    </div>
                    <p class="mb-0" style="font-size:.82rem;">{{ $note->note }}</p>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @empty
        <div class="text-center text-muted py-5">
            <i class="bi bi-journal-x fs-2 d-block mb-2 opacity-25"></i>
            No reports have been submitted yet.
        </div>
        @endforelse
    </div>

    <!-- Footer -->
    <div class="card-footer bg-transparent text-center py-4" style="border-top:2px solid #e8edf3;">
        <div class="text-muted" style="font-size:.78rem;">
            This report was generated on <strong>{{ $generated_at->format('d F Y \a\t H:i') }}</strong>
            from the Medical Internship Portal.
        </div>
        @if($total > 0)
        <div class="mt-1 text-muted" style="font-size:.78rem;">
            Approval rate:
            <strong style="color:#1e8449;">
                {{ $total > 0 ? round(($approved / $total) * 100) : 0 }}%
            </strong>
            ({{ $approved }} of {{ $total }} reports approved)
        </div>
        @endif
    </div>
</div>
@endsection
