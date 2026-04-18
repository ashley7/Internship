@extends('layouts.app')
@section('title', 'Submit Report')
@section('page-title', 'Submit Daily Report')

@section('sidebar-links')
    <div class="sidebar-section">Main</div>
    <a href="{{ route('student.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-1x2"></i> Dashboard</a>
    <div class="sidebar-section">Reports</div>
    <a href="{{ route('student.reports') }}" class="sidebar-link"><i class="bi bi-file-earmark-text"></i> My Reports</a>
    <a href="{{ route('student.reports.create') }}" class="sidebar-link active"><i class="bi bi-plus-circle"></i> Submit Report</a>
    <div class="sidebar-section">Export</div>
    <a href="{{ route('student.report.generate') }}" class="sidebar-link"><i class="bi bi-file-earmark-arrow-down"></i> Full Report</a>
@endsection

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('student.reports') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h5 class="mb-0 fw-bold" style="color:#1a5276">Submit Daily Report</h5>
        <small class="text-muted">Today: {{ now()->format('l, d F Y') }}</small>
    </div>
</div>

<form action="{{ route('student.reports.store') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="row g-4">
    {{-- Left column --}}
    <div class="col-lg-8">

        {{-- Patient & Diagnosis --}}
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-person-vcard me-2"></i>Patient Information</div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Report Date *</label>
                        <input type="date" name="date_submitted"
                               class="form-control @error('date_submitted') is-invalid @enderror"
                               value="{{ old('date_submitted', now()->toDateString()) }}"
                               max="{{ now()->toDateString() }}" required>
                        @error('date_submitted')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Patient ID</label>
                        <input type="text" name="patient_id"
                               class="form-control @error('patient_id') is-invalid @enderror"
                               value="{{ old('patient_id') }}" placeholder="e.g. MUH-2024-001">
                        @error('patient_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Diagnosis</label>
                        <input type="text" name="diagnosis"
                               class="form-control @error('diagnosis') is-invalid @enderror"
                               value="{{ old('diagnosis') }}" placeholder="e.g. Inguinal hernia">
                        @error('diagnosis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Procedures --}}
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-clipboard2-pulse me-2"></i>Procedures Logged</div>
            <div class="card-body p-4">
                <div class="row g-3">
                    @include('partials.procedure-entries')
                </div>
                @error('procedures')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Report narrative --}}
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-file-text me-2"></i>Report Narrative</div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Report Content *</label>
                        <textarea name="report"
                                  class="form-control @error('report') is-invalid @enderror"
                                  rows="10"
                                  placeholder="Describe the activities you carried out today: ward rounds, cases seen, operations attended, clinical discussions, skills practised..."
                                  required>{{ old('report') }}</textarea>
                        @error('report')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Be detailed — minimum 10 characters.</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Attachments --}}
        <div class="card">
            <div class="card-header"><i class="bi bi-paperclip me-2"></i>Attachments <small class="text-muted fw-normal">(PDF, Word, Images — max 10MB each)</small></div>
            <div class="card-body p-4">
                <div id="file-drop-zone" class="border rounded-3 p-4 text-center"
                     style="border-style:dashed!important;cursor:pointer;background:#f8fafc;">
                    <i class="bi bi-cloud-upload fs-2 text-muted d-block mb-2"></i>
                    <div class="text-muted" style="font-size:.85rem;">Drop files here or <span class="text-primary">browse</span></div>
                    <input type="file" name="attachments[]" id="file-input" multiple
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="d-none">
                </div>
                <div id="file-list" class="mt-2"></div>
            </div>
        </div>
    </div>

    {{-- Right column --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="bi bi-send me-2"></i>Submit</div>
            <div class="card-body">
                <p class="text-muted small mb-3">Review all entries before submitting. You can only submit one report per day.</p>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-2"></i>Submit Report
                    </button>
                    <a href="{{ route('student.reports') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

@push('scripts')
<script>
const dropZone  = document.getElementById('file-drop-zone');
const fileInput = document.getElementById('file-input');
const fileList  = document.getElementById('file-list');

dropZone.addEventListener('click', () => fileInput.click());
dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.style.background = '#eaf4fb'; });
dropZone.addEventListener('dragleave',  () => dropZone.style.background = '#f8fafc');
dropZone.addEventListener('drop', e => {
    e.preventDefault(); dropZone.style.background = '#f8fafc';
    fileInput.files = e.dataTransfer.files; renderFiles(fileInput.files);
});
fileInput.addEventListener('change', () => renderFiles(fileInput.files));

function renderFiles(files) {
    fileList.innerHTML = '';
    [...files].forEach(f => {
        fileList.innerHTML += `
        <div class="d-flex align-items-center gap-2 p-2 border rounded-3 mb-2 bg-white">
            <i class="bi bi-file-earmark text-primary"></i>
            <div class="flex-grow-1">
                <div style="font-size:.82rem;font-weight:600;">${f.name}</div>
                <small class="text-muted">${(f.size/1024).toFixed(1)} KB</small>
            </div>
            <input type="text" name="document_names[${f.name}]" class="form-control form-control-sm"
                   style="max-width:180px;" placeholder="Label" value="${f.name.replace(/\.[^.]+$/, '')}">
        </div>`;
    });
}
</script>
@endpush
