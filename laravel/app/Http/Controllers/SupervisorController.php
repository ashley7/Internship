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
        $supervisor = Auth::user();

        $students = Student::where('supervisor_id', $supervisor->id)
            ->with('user')
            ->withCount(['reports', 'reports as pending_count' => fn($q) => $q->where('status', 'pending')])
            ->get();

        $stats = [
            'students'        => $students->count(),
            'total_reports'   => Report::whereHas('student', fn($q) => $q->where('supervisor_id', $supervisor->id))->count(),
            'pending_reports' => Report::whereHas('student', fn($q) => $q->where('supervisor_id', $supervisor->id))->where('status', 'pending')->count(),
            'approved_reports'=> Report::whereHas('student', fn($q) => $q->where('supervisor_id', $supervisor->id))->where('status', 'approved')->count(),
        ];

        $recentReports = Report::whereHas('student', fn($q) => $q->where('supervisor_id', $supervisor->id))
            ->with('student.user')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('supervisor.dashboard', compact('students', 'stats', 'recentReports'));
    }

    // ── Students Management ───────────────────────────────────
    public function students()
    {
        $students = Student::where('supervisor_id', Auth::id())
            ->with('user')
            ->withCount('reports')
            ->paginate(15);

        return view('supervisor.students.index', compact('students'));
    }

    public function createStudent()
    {
        return view('supervisor.students.create');
    }

    public function storeStudent(Request $request)
    {
        $data = $request->validate([
            'name'                   => ['required', 'string', 'max:255'],
            'email'                  => ['required', 'email', 'unique:users'],
            'phone'                  => ['nullable', 'string', 'max:20'],
            'password'               => ['required', 'string', 'min:8', 'confirmed'],
            'school'                 => ['required', 'string', 'max:255'],
            'student_number'         => ['required', 'string', 'unique:students,student_number'],
            'internship_start_date'  => ['nullable', 'date'],
            'internship_end_date'    => ['nullable', 'date', 'after_or_equal:internship_start_date'],
        ]);

        $this->userService->createStudent($data, Auth::id());

        return redirect()->route('supervisor.students')
            ->with('success', 'Student account created successfully.');
    }

    public function editStudent(Student $student)
    {
        abort_unless($student->supervisor_id === Auth::id(), 403);
        return view('supervisor.students.edit', compact('student'));
    }

    public function updateStudent(Request $request, Student $student)
    {
        abort_unless($student->supervisor_id === Auth::id(), 403);

        $data = $request->validate([
            'name'                   => ['required', 'string', 'max:255'],
            'email'                  => ['required', 'email', 'unique:users,email,' . $student->user_id],
            'phone'                  => ['nullable', 'string', 'max:20'],
            'password'               => ['nullable', 'string', 'min:8', 'confirmed'],
            'school'                 => ['required', 'string', 'max:255'],
            'student_number'         => ['required', 'string', 'unique:students,student_number,' . $student->id],
            'internship_start_date'  => ['nullable', 'date'],
            'internship_end_date'    => ['nullable', 'date'],
        ]);

        $this->userService->updateUser($student->user, $data);
        $student->update([
            'school'                => $data['school'],
            'student_number'        => $data['student_number'],
            'internship_start_date' => $data['internship_start_date'] ?? null,
            'internship_end_date'   => $data['internship_end_date'] ?? null,
        ]);

        return redirect()->route('supervisor.students')
            ->with('success', 'Student updated successfully.');
    }

    // ── Reports Management ────────────────────────────────────
    public function reports(Request $request)
    {
        $supervisor = Auth::user();
        $myStudents = Student::where('supervisor_id', $supervisor->id)->with('user')->get();

        $filters = $request->only(['status', 'student_id', 'date_from', 'date_to']);
        $reports  = $this->reportService->getSupervisorReports($supervisor->id, $filters);

        return view('supervisor.reports.index', compact('reports', 'myStudents', 'filters'));
    }

    public function showReport(Report $report)
    {
        abort_unless($report->student->supervisor_id === Auth::id(), 403);
        $report->load('student.user', 'attachments', 'notes.user');
        return view('supervisor.reports.show', compact('report'));
    }

    public function updateStatus(Request $request, Report $report)
    {
        abort_unless($report->student->supervisor_id === Auth::id(), 403);

        $request->validate(['status' => ['required', 'in:approved,declined,pending']]);

        $this->reportService->updateStatus($report, $request->status);

        return back()->with('success', 'Report status updated to ' . ucfirst($request->status) . '.');
    }

    public function addNote(Request $request, Report $report)
    {
        abort_unless($report->student->supervisor_id === Auth::id(), 403);

        $request->validate(['note' => ['required', 'string', 'max:2000']]);

        $this->reportService->addNote($report, Auth::id(), $request->note);

        return back()->with('success', 'Note added successfully.');
    }
}
