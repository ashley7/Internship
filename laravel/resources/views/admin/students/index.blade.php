@extends('layouts.app')
@section('title', 'All Intern Doctors')
@section('page-title', 'All Intern Doctors')

@section('sidebar-links')
    <div class="sidebar-section">Main</div>
    <a href="{{ route('super_admin.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-1x2"></i> Dashboard</a>
    <div class="sidebar-section">Management</div>
    <a href="{{ route('super_admin.supervisors') }}" class="sidebar-link"><i class="bi bi-person-badge"></i> Supervisors</a>
    <a href="{{ route('super_admin.students') }}" class="sidebar-link active"><i class="bi bi-people"></i> Intern Doctors</a>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold" style="color:#1a5276">All Intern Doctors</h5>
        <small class="text-muted">{{ $students->total() }} intern doctor(s) across all supervisors</small>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Intern Doctor</th>
                        <th>Intern Doctor No.</th>
                        <th>School</th>
                        <th>Supervisor</th>
                        <th>Reports</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr>
                        <td class="text-muted">{{ $students->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:32px;height:32px;border-radius:50%;background:#d5f5e3;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.75rem;color:#1e8449;">
                                    {{ strtoupper(substr($student->user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $student->user->name }}</div>
                                    <small class="text-muted">{{ $student->user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td><code>{{ $student->student_number }}</code></td>
                        <td>{{ $student->school }}</td>
                        <td>{{ $student->supervisor->name }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $student->reports_count }}</span></td>
                        <td>
                            <span class="status-badge {{ $student->user->is_active ? 'badge-approved' : 'badge-declined' }}">
                                {{ $student->user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-people fs-2 d-block mb-2 opacity-25"></i>No intern doctors yet.
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($students->hasPages())
    <div class="card-footer bg-transparent">{{ $students->links() }}</div>
    @endif
</div>
@endsection
