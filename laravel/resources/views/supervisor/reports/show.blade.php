@extends('layouts.app')
@section('title', 'Review Report')
@section('page-title', 'Review Report')

@section('sidebar-links')
    <div class="sidebar-section">Main</div>
    <a href="{{ route('supervisor.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-1x2"></i> Dashboard</a>
    <div class="sidebar-section">Management</div>
    <a href="{{ route('supervisor.students') }}" class="sidebar-link"><i class="bi bi-people"></i> All Intern Doctors</a>
    <a href="{{ route('supervisor.reports') }}" class="sidebar-link active"><i class="bi bi-file-earmark-text"></i> Reports</a>
@endsection

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('supervisor.reports') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div class="flex-grow-1">
        <h5 class="mb-0 fw-bold" style="color:#1a5276">{{ $report->student->user->name }} — {{ $report->date_submitted->format('d F Y') }}</h5>
        <small class="text-muted">Submitted {{ $report->created_at->diffForHumans() }}</small>
    </div>
    <span class="status-badge badge-{{ $report->status }} fs-6">{{ ucfirst($report->status) }}</span>
</div>

<div class="row g-4">
    <div class="col-lg-8">

        {{-- Patient Info --}}
        @if($report->patient_id || $report->diagnosis)
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-person-vcard me-2"></i>Patient Information</div>
            <div class="card-body">
                <div class="row g-3">
                    @if($report->patient_id)
                    <div class="col-md-6">
                        <div class="text-muted" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Patient ID</div>
                        <div class="fw-semibold">{{ $report->patient_id }}</div>
                    </div>
                    @endif
                    @if($report->diagnosis)
                    <div class="col-md-6">
                        <div class="text-muted" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Diagnosis</div>
                        <div class="fw-semibold">{{ $report->diagnosis }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Procedures --}}
        @if($report->reportProcedures->count())
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-clipboard2-pulse me-2"></i>Procedures Logged ({{ $report->reportProcedures->count() }})</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Procedure</th><th>Role</th></tr></thead>
                    <tbody>
                        @foreach($report->reportProcedures as $rp)
                        <tr>
                            <td class="fw-semibold">{{ $rp->procedure->name }}</td>
                            <td><span class="badge {{ $rp->getRoleBadgeClass() }}">{{ ucfirst($rp->role) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Report Content --}}
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-file-text me-2"></i>Report Narrative</div>
            <div class="card-body"><div class="report-body">{{ $report->report }}</div></div>
        </div>

        {{-- Attachments --}}
        @if($report->attachments->count())
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-paperclip me-2"></i>Attachments ({{ $report->attachments->count() }})</div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach($report->attachments as $att)
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3 p-3 border rounded-3">
                            <i class="bi {{ $att->getIconClass() }} fs-3"></i>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="fw-semibold text-truncate" style="font-size:.85rem;">{{ $att->document_name }}</div>
                                <small class="text-muted">{{ $att->file_name }} &middot; {{ $att->getFileSizeFormatted() }}</small>
                            </div>
                            <a href="{{ asset('storage/' . $att->file_path) }}" target="_blank"
                               class="btn btn-sm btn-outline-primary" download><i class="bi bi-download"></i></a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Notes --}}
        <div class="card">
            <div class="card-header"><i class="bi bi-chat-left-text me-2"></i>Notes & Feedback</div>
            <div class="card-body">
                @forelse($report->notes as $note)
                <div class="note-bubble {{ $note->user->isSupervisor() ? 'supervisor' : 'student' }}">
                    <div class="d-flex justify-content-between mb-1">
                        <strong style="font-size:.82rem;">{{ $note->user->name }}
                            <span class="badge ms-1 {{ $note->user->isSupervisor() ? 'bg-primary' : 'bg-success' }}" style="font-size:.65rem;">
                                {{ $note->user->isSupervisor() ? 'Supervisor' : 'Intern Doctor' }}
                            </span>
                        </strong>
                        <small class="text-muted">{{ $note->created_at->format('d M Y, h:i A') }}</small>
                    </div>
                    <p class="mb-0" style="font-size:.875rem;">{{ $note->note }}</p>
                </div>
                @empty
                <p class="text-muted text-center py-3 mb-0">No notes yet.</p>
                @endforelse
                <hr class="my-3">
                <form action="{{ route('supervisor.reports.notes.store', $report) }}" method="POST">
                    @csrf
                    <label class="form-label">Add Feedback</label>
                    <textarea name="note" class="form-control @error('note') is-invalid @enderror" rows="3"
                              placeholder="Leave feedback for the intern...">{{ old('note') }}</textarea>
                    @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <button type="submit" class="btn btn-primary btn-sm mt-2"><i class="bi bi-send me-1"></i>Post Note</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Intern Info --}}
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-person me-2"></i>Intern</div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:44px;height:44px;border-radius:50%;background:#d5f5e3;display:flex;align-items:center;justify-content:center;font-weight:700;color:#1e8449;">
                        {{ strtoupper(substr($report->student->user->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="fw-bold">{{ $report->student->user->name }}</div>
                        <small class="text-muted">{{ $report->student->user->email }}</small>
                    </div>
                </div>
                <a href="{{ route('supervisor.students.summary', $report->student) }}"
                   class="btn btn-sm btn-outline-primary w-100">
                    <i class="bi bi-bar-chart me-1"></i>View Procedure Summary
                </a>
            </div>
        </div>

        {{-- Update Status --}}
        <div class="card">
            <div class="card-header"><i class="bi bi-shield-check me-2"></i>Update Status</div>
            <div class="card-body">
                <p class="text-muted small mb-3">Current: <strong>{{ ucfirst($report->status) }}</strong></p>
                <form action="{{ route('supervisor.reports.status', $report) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="d-grid gap-2">
                        <button type="submit" name="status" value="approved"
                                class="btn btn-success {{ $report->status === 'approved' ? 'disabled' : '' }}"
                                onclick="return confirm('Approve this report?')">
                            <i class="bi bi-check-circle me-2"></i>Approve
                        </button>
                        <button type="submit" name="status" value="declined"
                                class="btn btn-danger {{ $report->status === 'declined' ? 'disabled' : '' }}"
                                onclick="return confirm('Decline this report?')">
                            <i class="bi bi-x-circle me-2"></i>Decline
                        </button>
                        @if($report->status !== 'pending')
                        <button type="submit" name="status" value="pending"
                                class="btn btn-outline-warning btn-sm"
                                onclick="return confirm('Reset to pending?')">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset to Pending
                        </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
