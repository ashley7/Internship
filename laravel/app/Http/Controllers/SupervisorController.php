<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Report;
use App\Services\UserService;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupervisorController extends Controller
{
    public function __construct(
        private UserService   $userService,
        private ReportService $reportService
    ) {}

    public function dashboard()
    {
        // All students, not just mine
        $students = Student::with('user', 'supervisor')
            ->withCount([
                'reports',
                'reports as pending_count' => fn($q) => $q->where('status', 'pending'),
            ])
            ->get();

        $stats = [
            'students'         => Student::count(),
            'total_reports'    => Report::count(),
            'pending_reports'  => Report::where('status', 'pending')->count(),
            'approved_reports' => Report::where('status', 'approved')->count(),
        ];

        $recentReports = Report::with('student.user')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('supervisor.dashboard', compact('students', 'stats', 'recentReports'));
    }

    // ── Students ──────────────────────────────────────────────
    public function students()
    {
        $students = Student::with('user', 'supervisor')
            ->withCount('reports')
            ->paginate(20);

        return view('supervisor.students.index', compact('students'));
    }

    public function createStudent()
    {
        return view('supervisor.students.create');
    }

    public function storeStudent(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'unique:users'],
            'phone'                 => ['nullable', 'string', 'max:20'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'internship_start_date' => ['nullable', 'date'],
            'internship_end_date'   => ['nullable', 'date', 'after_or_equal:internship_start_date'],
        ]);

        $this->userService->createStudent($data, Auth::id());

        return redirect()->route('supervisor.students')
            ->with('success', 'Student account created successfully.');
    }

    public function editStudent(Student $student)
    {
        return view('supervisor.students.edit', compact('student'));
    }

    public function updateStudent(Request $request, Student $student)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'unique:users,email,' . $student->user_id],
            'phone'                 => ['nullable', 'string', 'max:20'],
            'password'              => ['nullable', 'string', 'min:8', 'confirmed'],
            'internship_start_date' => ['nullable', 'date'],
            'internship_end_date'   => ['nullable', 'date'],
        ]);

        $this->userService->updateUser($student->user, $data);
        $student->update([
            'internship_start_date' => $data['internship_start_date'] ?? null,
            'internship_end_date'   => $data['internship_end_date'] ?? null,
        ]);

        return redirect()->route('supervisor.students')
            ->with('success', 'Student updated successfully.');
    }

    // Student procedure summary
    public function studentSummary(Student $student)
    {
        $procedureSummary = $student->procedureSummary();
        $student->load('user', 'supervisor');
        $reportCount = $student->reports()->count();
        return view('supervisor.students.summary', compact('student', 'procedureSummary', 'reportCount'));
    }

    // ── Reports ───────────────────────────────────────────────
    public function reports(Request $request)
    {
        $allStudents = Student::with('user')->get();
        $filters     = $request->only(['status', 'student_id', 'date_from', 'date_to']);
        $reports     = $this->reportService->getSupervisorReports($filters);

        return view('supervisor.reports.index', compact('reports', 'allStudents', 'filters'));
    }

    public function showReport(Report $report)
    {
        $report->load('student.user', 'student.supervisor', 'attachments', 'notes.user', 'reportProcedures.procedure');
        return view('supervisor.reports.show', compact('report'));
    }

    public function updateStatus(Request $request, Report $report)
    {
        $request->validate(['status' => ['required', 'in:approved,declined,pending']]);
        $this->reportService->updateStatus($report, $request->status);
        return back()->with('success', 'Report status updated to ' . ucfirst($request->status) . '.');
    }

    public function addNote(Request $request, Report $report)
    {
        $request->validate(['note' => ['required', 'string', 'max:2000']]);
        $this->reportService->addNote($report, Auth::id(), $request->note);
        return back()->with('success', 'Note added successfully.');
    }
}
