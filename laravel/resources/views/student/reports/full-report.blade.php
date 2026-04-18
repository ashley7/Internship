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
        #sidebar, #topbar { display: none !important; }
        #main-wrapper { margin-left: 0 !important; }
        .card { box-shadow: none !important; border: 1px solid #ddd !important; }
        .report-entry { page-break-inside: avoid; }
    }
    .report-cover {
        background: linear-gradient(135deg, #0d2137 0%, #1a5276 60%, #2e86c1 100%);
        border-radius: 16px; padding: 48px 40px; color: #fff; margin-bottom: 32px;
    }
    .report-entry {
        border-left: 4px solid #e8edf3; padding-left: 20px; margin-bottom: 32px;
    }
    .report-entry.approved { border-left-color: #27ae60; }
    .report-entry.declined { border-left-color: #c0392b; }
    .report-entry.pending  { border-left-color: #f39c12; }
    .role-pill {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 2px 9px; border-radius: 20px; font-size: .72rem; font-weight: 700;
    }
    .role-observed  { background:#e9ecef; color:#495057; }
    .role-assisted  { background:#cfe2ff; color:#084298; }
    .role-performed { background:#d1e7dd; color:#0a3622; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <div>
        <h5 class="mb-0 fw-bold" style="color:#1a5276">Full Internship Report</h5>
        <small class="text-muted">Generated on {{ $generated_at->format('d F Y, H:i') }}</small>
    </div>
    <button onclick="window.print()" class="btn btn-primary">
        <i class="bi bi-printer me-2"></i>Print / Save PDF
    </button>
</div>

{{-- Cover --}}
<div class="report-cover">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div style="background:rgba(255,255,255,.15);border-radius:12px;width:52px;height:52px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;">🏥</div>
        <div>
            <div style="font-size:.72rem;letter-spacing:1.5px;text-transform:uppercase;opacity:.7;">Medical Internship Portal</div>
            <div style="font-family:'Lora',serif;font-size:1.4rem;font-weight:700;">Internship Report</div>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-md-6">
            <div style="opacity:.7;font-size:.72rem;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Intern</div>
            <div style="font-size:1.3rem;font-weight:700;">{{ $student->user->name }}</div>
            <div style="opacity:.8;font-size:.875rem;">{{ $student->user->email }}</div>
        </div>
        <div class="col-md-6">
            <div style="opacity:.7;font-size:.72rem;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Supervisor</div>
            <div style="font-weight:600;">{{ $student->supervisor->name }}</div>
            <div style="opacity:.8;font-size:.875rem;">{{ $student->supervisor->email }}</div>
        </div>
        <div class="col-md-6">
            <div style="opacity:.7;font-size:.72rem;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Internship Period</div>
            <div style="font-weight:600;">
                @if($student->internship_start_date)
                    {{ $student->internship_start_date->format('d M Y') }} — {{ $student->internship_end_date?->format('d M Y') ?? 'Present' }}
                @else Not specified @endif
            </div>
        </div>
        <div class="col-md-6">
            <div style="opacity:.7;font-size:.72rem;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Generated</div>
            <div style="font-weight:600;">{{ $generated_at->format('d M Y, H:i') }}</div>
        </div>
    </div>
</div>

{{-- Summary Stats --}}
<div class="row g-3 mb-4">
    @php
        $totalObserved  = collect($procedureSummary)->sum('observed');
        $totalAssisted  = collect($procedureSummary)->sum('assisted');
        $totalPerformed = collect($procedureSummary)->sum('performed');
    @endphp
    <div class="col-6 col-md-3">
        <div class="card text-center p-3">
            <div style="font-size:1.9rem;font-weight:800;color:#1a5276;">{{ $total }}</div>
            <div class="text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.5px;">Reports</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center p-3" style="border-top:3px solid #6c757d;">
            <div style="font-size:1.9rem;font-weight:800;color:#495057;">{{ $totalObserved }}</div>
            <div class="text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.5px;">Observed</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center p-3" style="border-top:3px solid #2e86c1;">
            <div style="font-size:1.9rem;font-weight:800;color:#084298;">{{ $totalAssisted }}</div>
            <div class="text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.5px;">Assisted</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center p-3" style="border-top:3px solid #27ae60;">
            <div style="font-size:1.9rem;font-weight:800;color:#0a3622;">{{ $totalPerformed }}</div>
            <div class="text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.5px;">Performed</div>
        </div>
    </div>
</div>

{{-- Procedure Summary Table --}}
@if(count($procedureSummary))
<div class="card mb-4">
    <div class="card-header"><i class="bi bi-table me-2"></i>Procedure Summary</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Procedure</th>
                        <th class="text-center"><span class="role-pill role-observed">Observed</span></th>
                        <th class="text-center"><span class="role-pill role-assisted">Assisted</span></th>
                        <th class="text-center"><span class="role-pill role-performed">Performed</span></th>
                        <th class="text-center">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($procedureSummary as $name => $counts)
                    @php $obs=$counts['observed']??0; $ast=$counts['assisted']??0; $prf=$counts['performed']??0; @endphp
                    <tr>
                        <td class="fw-semibold" style="font-size:.875rem;">{{ $name }}</td>
                        <td class="text-center">{{ $obs ?: '—' }}</td>
                        <td class="text-center">{{ $ast ?: '—' }}</td>
                        <td class="text-center">{{ $prf ?: '—' }}</td>
                        <td class="text-center fw-bold">{{ $obs+$ast+$prf }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot style="background:#f8fafc;">
                    <tr>
                        <td class="fw-bold text-uppercase" style="font-size:.72rem;color:#6b7c93;letter-spacing:.5px;">TOTAL</td>
                        <td class="text-center fw-bold">{{ $totalObserved }}</td>
                        <td class="text-center fw-bold">{{ $totalAssisted }}</td>
                        <td class="text-center fw-bold">{{ $totalPerformed }}</td>
                        <td class="text-center fw-bold">{{ $totalObserved+$totalAssisted+$totalPerformed }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endif

{{-- Daily Reports --}}
<div class="card">
    <div class="card-header"><i class="bi bi-journal-text me-2"></i>Daily Reports ({{ $total }})</div>
    <div class="card-body p-4">
        @forelse($reports as $report)
        <div class="report-entry {{ $report->status }}">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h6 class="mb-0 fw-bold" style="color:#1a5276;">{{ $report->date_submitted->format('l, d F Y') }}</h6>
                    <small class="text-muted">Report #{{ $loop->iteration }}</small>
                </div>
                <span class="status-badge badge-{{ $report->status }}">{{ ucfirst($report->status) }}</span>
            </div>

            {{-- Patient info --}}
            @if($report->patient_id || $report->diagnosis)
            <div class="d-flex gap-4 mb-2" style="font-size:.82rem;">
                @if($report->patient_id)
                <div><span class="text-muted">Patient ID:</span> <strong>{{ $report->patient_id }}</strong></div>
                @endif
                @if($report->diagnosis)
                <div><span class="text-muted">Diagnosis:</span> <strong>{{ $report->diagnosis }}</strong></div>
                @endif
            </div>
            @endif

            {{-- Procedures --}}
            @if($report->reportProcedures->count())
            <div class="mb-2">
                @foreach($report->reportProcedures as $rp)
                <span class="role-pill role-{{ $rp->role }} me-1 mb-1">
                    {{ $rp->procedure->name }} &mdash; {{ ucfirst($rp->role) }}
                </span>
                @endforeach
            </div>
            @endif

            <div class="report-body mb-2" style="font-size:.875rem;">{{ $report->report }}</div>

            @if($report->notes->count())
            <div class="mt-2">
                @foreach($report->notes as $note)
                <div class="note-bubble {{ $note->user->isSupervisor() ? 'supervisor' : 'student' }} mb-1">
                    <div class="d-flex justify-content-between mb-1">
                        <strong style="font-size:.75rem;">{{ $note->user->name }}</strong>
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
            <i class="bi bi-journal-x fs-2 d-block mb-2 opacity-25"></i>No reports submitted yet.
        </div>
        @endforelse
    </div>
    <div class="card-footer bg-transparent text-center py-4">
        <div class="text-muted" style="font-size:.78rem;">
            Generated on <strong>{{ $generated_at->format('d F Y \a\t H:i') }}</strong> — MedIntern Portal
        </div>
    </div>
</div>
@endsection
