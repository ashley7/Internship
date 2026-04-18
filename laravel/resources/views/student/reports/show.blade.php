@extends('layouts.app')
@section('title', 'View Report')
@section('page-title', 'Report Details')

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
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('student.reports') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div class="flex-grow-1">
        <h5 class="mb-0 fw-bold" style="color:#1a5276">
            Report — {{ $report->date_submitted->format('l, d F Y') }}
        </h5>
        <small class="text-muted">Submitted {{ $report->created_at->diffForHumans() }}</small>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="status-badge badge-{{ $report->status }} fs-6">{{ ucfirst($report->status) }}</span>
        @if($report->status === 'pending')
        <a href="{{ route('student.reports.edit', $report) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        @endif
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Report Content -->
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-file-text me-2"></i>Report Content</div>
            <div class="card-body">
                <div class="report-body">{{ $report->report }}</div>
            </div>
        </div>

        <!-- Attachments -->
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
                            <div class="d-flex flex-column gap-1">
                                <a href="{{ asset('storage/' . $att->file_path) }}" target="_blank"
                                   class="btn btn-sm btn-outline-primary" download>
                                    <i class="bi bi-download"></i>
                                </a>
                                @if($report->status === 'pending')
                                <form action="{{ route('student.attachments.destroy', $att) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Remove this attachment?')" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Notes -->
        <div class="card">
            <div class="card-header"><i class="bi bi-chat-left-text me-2"></i>Notes & Feedback
                @if($report->notes->count())
                <span class="badge bg-secondary ms-1">{{ $report->notes->count() }}</span>
                @endif
            </div>
            <div class="card-body">
                @forelse($report->notes as $note)
                <div class="note-bubble {{ $note->user->isSupervisor() ? 'supervisor' : 'Intern Doctor' }}">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong style="font-size:.82rem;">
                            {{ $note->user->name }}
                            <span class="badge ms-1 {{ $note->user->isSupervisor() ? 'bg-primary' : 'bg-success' }}" style="font-size:.65rem;">
                                {{ $note->user->isSupervisor() ? 'Supervisor' : 'You' }}
                            </span>
                        </strong>
                        <small class="text-muted">{{ $note->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="mb-0" style="font-size:.875rem;">{{ $note->note }}</p>
                </div>
                @empty
                <p class="text-muted text-center py-3 mb-0">No notes yet. Your supervisor will leave feedback here.</p>
                @endforelse

                <hr class="my-3">
                <form action="{{ route('student.reports.notes.store', $report) }}" method="POST">
                    @csrf
                    <label class="form-label">Add a Note</label>
                    <textarea name="note" class="form-control @error('note') is-invalid @enderror" rows="3"
                              placeholder="Add a note or clarification to this report...">{{ old('note') }}</textarea>
                    @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <button type="submit" class="btn btn-primary btn-sm mt-2">
                        <i class="bi bi-send me-1"></i>Add Note
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar info -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-info-circle me-2"></i>Report Info</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0" style="font-size:.85rem;">
                    <tr>
                        <td class="text-muted ps-3">Date</td>
                        <td class="fw-semibold">{{ $report->date_submitted->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-3">Status</td>
                        <td><span class="status-badge badge-{{ $report->status }}">{{ ucfirst($report->status) }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-3">Submitted</td>
                        <td>{{ $report->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-3">Attachments</td>
                        <td>{{ $report->attachments->count() }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-3 pb-3">Notes</td>
                        <td class="pb-3">{{ $report->notes->count() }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="bi bi-person-badge me-2"></i>Supervisor</div>
            <div class="card-body text-center py-4">
                <div style="width:48px;height:48px;border-radius:50%;background:#d6eaf8;display:flex;align-items:center;justify-content:center;font-weight:700;color:#1a5276;margin:0 auto 10px;">
                    {{ strtoupper(substr($report->student->supervisor->name, 0, 2)) }}
                </div>
                <div class="fw-bold" style="font-size:.9rem;">{{ $report->student->supervisor->name }}</div>
                <small class="text-muted">{{ $report->student->supervisor->email }}</small>
            </div>
        </div>
    </div>
</div>
@endsection
