@extends('layouts.app')
@section('title', 'Manage Procedures')
@section('page-title', 'Manage Procedures')

@section('sidebar-links')
    <div class="sidebar-section">Main</div>
    <a href="{{ route('super_admin.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-1x2"></i> Dashboard</a>
    <div class="sidebar-section">Management</div>
    <a href="{{ route('super_admin.supervisors') }}" class="sidebar-link"><i class="bi bi-person-badge"></i> Supervisors</a>
    <a href="{{ route('super_admin.students') }}" class="sidebar-link"><i class="bi bi-people"></i> Intern Doctors</a>
    <a href="{{ route('super_admin.procedures') }}" class="sidebar-link active"><i class="bi bi-list-check"></i> Procedures</a>
@endsection

@section('content')
<div class="row g-4">
    {{-- Add New Procedure --}}
    <div class="col-lg-4">
        <div class="card sticky-top" style="top:80px;">
            <div class="card-header"><i class="bi bi-plus-circle me-2"></i>Add New Procedure</div>
            <div class="card-body p-4">
                <form action="{{ route('super_admin.procedures.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Procedure Name *</label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="e.g. Laparoscopic cholecystectomy" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-plus-lg me-2"></i>Add Procedure
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Procedures List --}}
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="mb-0 fw-bold" style="color:#1a5276">Surgical Procedures</h5>
                <small class="text-muted">{{ $procedures->count() }} procedures &mdash; {{ $procedures->where('is_active', true)->count() }} active</small>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Procedure Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($procedures as $proc)
                            <tr>
                                <td class="text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    {{-- Inline edit form --}}
                                    <form action="{{ route('super_admin.procedures.update', $proc) }}"
                                          method="POST" class="d-flex align-items-center gap-2" id="edit-form-{{ $proc->id }}">
                                        @csrf @method('PUT')
                                        <input type="text" name="name"
                                               class="form-control form-control-sm"
                                               value="{{ $proc->name }}"
                                               style="max-width:320px;">
                                        <button type="submit" class="btn btn-sm btn-outline-primary" title="Save">
                                            <i class="bi bi-check2"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <span class="status-badge {{ $proc->is_active ? 'badge-approved' : 'badge-declined' }}">
                                        {{ $proc->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        {{-- Toggle --}}
                                        <form action="{{ route('super_admin.procedures.toggle', $proc) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                    class="btn btn-sm {{ $proc->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                                    title="{{ $proc->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="bi bi-{{ $proc->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                        {{-- Delete --}}
                                        <form action="{{ route('super_admin.procedures.destroy', $proc) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete"
                                                    onclick="return confirm('Delete \'{{ addslashes($proc->name) }}\'? This cannot be undone.')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="bi bi-list-check fs-2 d-block mb-2 opacity-25"></i>
                                    No procedures yet. Add the first one.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
