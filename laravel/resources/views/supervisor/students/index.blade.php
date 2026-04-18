@extends('layouts.app')
@section('title', 'My Students')
@section('page-title', 'My Students')

@section('sidebar-links')
    <div class="sidebar-section">Main</div>
    <a href="{{ route('supervisor.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-1x2"></i> Dashboard</a>
    <div class="sidebar-section">Management</div>
    <a href="{{ route('supervisor.students') }}" class="sidebar-link active"><i class="bi bi-people"></i> My Students</a>
    <a href="{{ route('supervisor.reports') }}" class="sidebar-link"><i class="bi bi-file-earmark-text"></i> Reports</a>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold" style="color:#1a5276">My Students</h5>
        <small class="text-muted">{{ $students->total() }} student(s) under your supervision</small>
    </div>
    <a href="{{ route('supervisor.students.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Add Student
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th><th>Student</th><th>Student No.</th>
                        <th>School</th><th>Internship Period</th><th>Reports</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr>
                        <td class="text-muted">{{ $students->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:34px;height:34px;border-radius:50%;background:#d5f5e3;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.75rem;color:#1e8449;flex-shrink:0;">
                                    {{ strtoupper(substr($student->user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $student->user->name }}</div>
                                    <small class="text-muted">{{ $student->user->email }}</small>
                                    <small class="text-muted">{{ $student->user->phone }}</small>
                                </div>
                            </div>
                        </td>
                        <td><code>{{ $student->student_number }}</code></td>
                        <td>{{ $student->school }}</td>
                        <td class="small text-muted">
                            @if($student->internship_start_date)
                                {{ $student->internship_start_date->format('d M Y') }} →
                                {{ $student->internship_end_date?->format('d M Y') ?? 'Present' }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $student->reports_count }}</span></td>
                        <td>
                            <a href="{{ route('supervisor.students.edit', $student) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-people fs-2 d-block mb-2 opacity-25"></i>
                        No students yet. <a href="{{ route('supervisor.students.create') }}">Add your first student.</a>
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
