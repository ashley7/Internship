@extends('layouts.app')
@section('title', 'Intern Summary')
@section('page-title', 'Intern Doctor Procedure Summary')

@section('sidebar-links')
    <div class="sidebar-section">Main</div>
    <a href="{{ route('supervisor.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-1x2"></i> Dashboard</a>
    <div class="sidebar-section">Management</div>
    <a href="{{ route('supervisor.students') }}" class="sidebar-link active"><i class="bi bi-people"></i> All Intern Doctors</a>
    <a href="{{ route('supervisor.reports') }}" class="sidebar-link"><i class="bi bi-file-earmark-text"></i> Reports</a>
@endsection

@push('styles')
<style>
    @media print {
        #sidebar, #topbar, .no-print { display: none !important; }
        #main-wrapper { margin-left: 0 !important; }
        .card { box-shadow: none !important; border: 1px solid #ddd !important; }
    }
    .role-pill {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 10px; border-radius: 20px; font-size: .75rem; font-weight: 700;
    }
    .role-observed  { background:#e9ecef; color:#495057; }
    .role-assisted  { background:#cfe2ff; color:#084298; }
    .role-performed { background:#d1e7dd; color:#0a3622; }
    .summary-row:hover { background:#f8fafc; }
    .count-cell { font-size: 1.05rem; font-weight: 700; text-align: center; }
    .count-zero { color: #ced4da; font-weight: 400; font-size: .9rem; }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center gap-3 mb-4 no-print">
    <a href="{{ route('supervisor.students') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div class="flex-grow-1">
        <h5 class="mb-0 fw-bold" style="color:#1a5276">{{ $student->user->name }} — Procedure Summary</h5>
        <small class="text-muted">Supervisor: {{ $student->supervisor->name }}</small>
    </div>
    <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-printer me-1"></i>Print
    </button>
</div>

{{-- Quick Stats --}}
<div class="row g-3 mb-4">
    @php
        $totalObserved  = collect($procedureSummary)->sum('observed');
        $totalAssisted  = collect($procedureSummary)->sum('assisted');
        $totalPerformed = collect($procedureSummary)->sum('performed');
        $totalAll       = $totalObserved + $totalAssisted + $totalPerformed;
    @endphp
    <div class="col-6 col-md-3">
        <div class="card text-center p-3">
            <div style="font-size:1.9rem;font-weight:800;color:#1a5276;">{{ $reportCount }}</div>
            <div class="text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.5px;">Total Reports</div>
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

{{-- Procedure Breakdown Table --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-table me-2"></i>Procedure Breakdown</span>
        <small class="text-muted">{{ count($procedureSummary) }} procedure(s) logged</small>
    </div>
    <div class="card-body p-0">
        @if(count($procedureSummary) > 0)
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="min-width:280px;">Procedure</th>
                        <th class="text-center" style="min-width:110px;">
                            <span class="role-pill role-observed">Observed</span>
                        </th>
                        <th class="text-center" style="min-width:110px;">
                            <span class="role-pill role-assisted">Assisted</span>
                        </th>
                        <th class="text-center" style="min-width:110px;">
                            <span class="role-pill role-performed">Performed</span>
                        </th>
                        <th class="text-center" style="min-width:80px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($procedureSummary as $name => $counts)
                    @php
                        $obs  = $counts['observed']  ?? 0;
                        $ast  = $counts['assisted']  ?? 0;
                        $prf  = $counts['performed'] ?? 0;
                        $tot  = $obs + $ast + $prf;
                    @endphp
                    <tr class="summary-row">
                        <td class="fw-semibold" style="font-size:.9rem;">{{ $name }}</td>
                        <td class="count-cell">
                            @if($obs > 0)
                                <span class="role-pill role-observed">{{ $obs }}</span>
                            @else
                                <span class="count-zero">—</span>
                            @endif
                        </td>
                        <td class="count-cell">
                            @if($ast > 0)
                                <span class="role-pill role-assisted">{{ $ast }}</span>
                            @else
                                <span class="count-zero">—</span>
                            @endif
                        </td>
                        <td class="count-cell">
                            @if($prf > 0)
                                <span class="role-pill role-performed">{{ $prf }}</span>
                            @else
                                <span class="count-zero">—</span>
                            @endif
                        </td>
                        <td class="count-cell">
                            <strong>{{ $tot }}</strong>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot style="background:#f8fafc;">
                    <tr>
                        <td class="fw-bold text-uppercase" style="font-size:.75rem;letter-spacing:.5px;color:#6b7c93;">TOTAL</td>
                        <td class="count-cell fw-bold">{{ $totalObserved }}</td>
                        <td class="count-cell fw-bold">{{ $totalAssisted }}</td>
                        <td class="count-cell fw-bold">{{ $totalPerformed }}</td>
                        <td class="count-cell fw-bold">{{ $totalAll }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="text-center text-muted py-5">
            <i class="bi bi-clipboard-x fs-2 d-block mb-2 opacity-25"></i>
            <p class="mb-0">No procedures logged yet for this intern.</p>
            <small>Procedures are recorded when the intern submits daily reports.</small>
        </div>
        @endif
    </div>
</div>
@endsection
