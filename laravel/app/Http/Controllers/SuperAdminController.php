<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Report;
use App\Models\Procedure;
use App\Services\UserService;
use App\Services\ProcedureService;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function __construct(
        private UserService      $userService,
        private ProcedureService $procedureService,
    ) {}

    public function dashboard()
    {
        $stats = [
            'supervisors' => User::where('role', 'supervisor')->count(),
            'students'    => User::where('role', 'student')->count(),
            'reports'     => Report::count(),
            'pending'     => Report::where('status', 'pending')->count(),
        ];

        $recentSupervisors = User::where('role', 'supervisor')
            ->withCount('supervisedStudents')
            ->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentSupervisors'));
    }

    // ── Supervisors ──────────────────────────────────────────
    public function supervisors()
    {
        $supervisors = $this->userService->getAllSupervisors();
        return view('admin.supervisors.index', compact('supervisors'));
    }

    public function createSupervisor()
    {
        return view('admin.supervisors.create');
    }

    public function storeSupervisor(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $this->userService->createSupervisor($data);

        return redirect()->route('super_admin.supervisors')
            ->with('success', 'Supervisor account created successfully.');
    }

    public function editSupervisor(User $user)
    {
        abort_unless($user->role === 'supervisor', 403);
        return view('admin.supervisors.edit', compact('user'));
    }

    public function updateSupervisor(Request $request, User $user)
    {
        abort_unless($user->role === 'supervisor', 403);

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email,' . $user->id],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $this->userService->updateUser($user, $data);

        return redirect()->route('super_admin.supervisors')
            ->with('success', 'Supervisor updated successfully.');
    }

    public function toggleSupervisor(User $user)
    {
        abort_unless($user->role === 'supervisor', 403);
        $this->userService->toggleStatus($user);
        return back()->with('success', 'Supervisor status updated.');
    }

    // ── Students ─────────────────────────────────────────────
    public function students()
    {
        $students = Student::with('user', 'supervisor')
            ->withCount('reports')
            ->paginate(20);

        return view('admin.students.index', compact('students'));
    }

    // ── Procedures ────────────────────────────────────────────
    public function procedures()
    {
        $procedures = $this->procedureService->getAll();
        return view('admin.procedures.index', compact('procedures'));
    }

    public function storeProcedure(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:procedures,name'],
        ]);

        $this->procedureService->create($request->all());

        return back()->with('success', 'Procedure added successfully.');
    }

    public function updateProcedure(Request $request, Procedure $procedure)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:procedures,name,' . $procedure->id],
        ]);

        $this->procedureService->update($procedure, $request->all());

        return back()->with('success', 'Procedure updated.');
    }

    public function toggleProcedure(Procedure $procedure)
    {
        $this->procedureService->toggle($procedure);
        return back()->with('success', 'Procedure status updated.');
    }

    public function deleteProcedure(Procedure $procedure)
    {
        $this->procedureService->delete($procedure);
        return back()->with('success', 'Procedure deleted.');
    }
}
