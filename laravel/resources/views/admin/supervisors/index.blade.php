@extends('layouts.app')
@section('title', 'Supervisors')
@section('page-title', 'Supervisors')

@section('sidebar-links')
    <div class="sidebar-section">Main</div>
    <a href="{{ route('super_admin.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-1x2"></i> Dashboard</a>
    <div class="sidebar-section">Management</div>
    <a href="{{ route('super_admin.supervisors') }}" class="sidebar-link active"><i class="bi bi-person-badge"></i> Supervisors</a>
    <a href="{{ route('super_admin.students') }}" class="sidebar-link"><i class="bi bi-people"></i> Intern Doctors</a>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold" style="color:#1a5276">Supervisors</h5>
        <small class="text-muted">{{ $supervisors->count() }} supervisor(s) in the system</small>
    </div>
    <a href="{{ route('super_admin.supervisors.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Add Supervisor
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Supervisor</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Intern Doctors</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($supervisors as $sv)
                    <tr>
                        <td class="text-muted">{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:36px;height:36px;border-radius:50%;background:#d6eaf8;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.8rem;color:#1a5276;flex-shrink:0;">
                                    {{ strtoupper(substr($sv->name, 0, 2)) }}
                                </div>
                                <span class="fw-semibold">{{ $sv->name }}</span>
                            </div>
                        </td>
                        <td>{{ $sv->email }}</td>
                        <td>{{ $sv->phone ?? '—' }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-people me-1"></i>{{ $sv->supervised_students_count }}
                            </span>
                        </td>
                        <td>
                            <span class="status-badge {{ $sv->is_active ? 'badge-approved' : 'badge-declined' }}">
                                {{ $sv->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-muted small">{{ $sv->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('super_admin.supervisors.edit', $sv) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('super_admin.supervisors.toggle', $sv) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $sv->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                            title="{{ $sv->is_active ? 'Deactivate' : 'Activate' }}"
                                            onclick="return confirm('{{ $sv->is_active ? 'Deactivate' : 'Activate' }} this supervisor?')">
                                        <i class="bi bi-{{ $sv->is_active ? 'pause' : 'play' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-5">
                        <i class="bi bi-person-badge fs-2 d-block mb-2 opacity-25"></i>
                        No supervisors yet. <a href="{{ route('super_admin.supervisors.create') }}">Create the first one.</a>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
