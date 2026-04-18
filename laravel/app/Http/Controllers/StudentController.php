<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Attachment;
use App\Services\ReportService;
use App\Services\ProcedureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function __construct(
        private ReportService    $reportService,
        private ProcedureService $procedureService,
    ) {}

    public function dashboard()
    {
        $student = Auth::user()->student;
        $stats   = [
            'total'    => $student->reports()->count(),
            'approved' => $student->reports()->where('status', 'approved')->count(),
            'declined' => $student->reports()->where('status', 'declined')->count(),
            'pending'  => $student->reports()->where('status', 'pending')->count(),
        ];
        $recentReports = $student->reports()
            ->with(['attachments', 'reportProcedures.procedure'])
            ->orderByDesc('date_submitted')
            ->take(5)->get();
        $hasToday = $this->reportService->hasReportForDate($student, today()->toDateString());

        return view('student.dashboard', compact('stats', 'recentReports', 'hasToday', 'student'));
    }

    public function reports()
    {
        $student = Auth::user()->student;
        $reports = $this->reportService->getStudentReports($student);
        return view('student.reports.index', compact('reports'));
    }

    public function createReport()
    {
        $student    = Auth::user()->student;
        $procedures = $this->procedureService->getActive();

        return view('student.reports.create', compact('procedures'));
    }

    public function storeReport(Request $request)
    {
        $student = Auth::user()->student;

        $data = $request->validate([
            'report'                    => ['required', 'string', 'min:10'],
            'patient_id'                => ['nullable', 'string', 'max:100'],
            'diagnosis'                 => ['nullable', 'string', 'max:500'],
            'date_submitted'            => ['required', 'date', 'before_or_equal:today'],
            'attachments.*'             => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,jpg,jpeg,png'],
            'procedures'                => ['nullable', 'array'],
            'procedures.*.procedure_id' => ['required_with:procedures.*.role', 'exists:procedures,id'],
            'procedures.*.role'         => ['required_with:procedures.*.procedure_id', 'in:observed,assisted,performed'],
        ]);

        if ($this->reportService->hasReportForDate($student, $data['date_submitted'])) {
            return back()->withErrors(['date_submitted' => 'A report already exists for this date.'])->withInput();
        }

        $files = $request->hasFile('attachments') ? $request->file('attachments') : [];
        $this->reportService->submitReport($student, $data, $files);

        return redirect()->route('student.reports')
            ->with('success', 'Report submitted successfully.');
    }

    public function showReport(Report $report)
    {
        abort_unless($report->student->user_id === Auth::id(), 403);
        $report->load('attachments', 'notes.user', 'student.supervisor', 'reportProcedures.procedure');
        return view('student.reports.show', compact('report'));
    }

    public function editReport(Report $report)
    {
        abort_unless($report->student->user_id === Auth::id(), 403);
        abort_unless($report->status === 'pending', 403);
        $procedures = $this->procedureService->getActive();
        $report->load('reportProcedures.procedure');
        return view('student.reports.edit', compact('report', 'procedures'));
    }

    public function updateReport(Request $request, Report $report)
    {
        abort_unless($report->student->user_id === Auth::id(), 403);
        abort_unless($report->status === 'pending', 403);

        $data = $request->validate([
            'report'                    => ['required', 'string', 'min:10'],
            'patient_id'                => ['nullable', 'string', 'max:100'],
            'diagnosis'                 => ['nullable', 'string', 'max:500'],
            'attachments.*'             => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,jpg,jpeg,png'],
            'procedures'                => ['nullable', 'array'],
            'procedures.*.procedure_id' => ['required_with:procedures.*.role', 'exists:procedures,id'],
            'procedures.*.role'         => ['required_with:procedures.*.procedure_id', 'in:observed,assisted,performed'],
        ]);

        $files = $request->hasFile('attachments') ? $request->file('attachments') : [];
        $this->reportService->updateReport($report, $data, $files);

        return redirect()->route('student.reports.show', $report)
            ->with('success', 'Report updated successfully.');
    }

    public function addNote(Request $request, Report $report)
    {
        abort_unless($report->student->user_id === Auth::id(), 403);
        $request->validate(['note' => ['required', 'string', 'max:2000']]);
        $this->reportService->addNote($report, Auth::id(), $request->note);
        return back()->with('success', 'Note added.');
    }

    public function deleteAttachment(Attachment $attachment)
    {
        abort_unless($attachment->report->student->user_id === Auth::id(), 403);
        abort_unless($attachment->report->status === 'pending', 403);
        $this->reportService->deleteAttachment($attachment);
        return back()->with('success', 'Attachment removed.');
    }

    public function generateReport()
    {
        $student = Auth::user()->student;
        $data    = $this->reportService->generateFullReport($student);
        return view('student.reports.full-report', $data);
    }
}
