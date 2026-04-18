<?php

namespace App\Services;

use App\Models\Report;
use App\Models\ReportNote;
use App\Models\ReportProcedure;
use App\Models\Attachment;
use App\Models\Student;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReportService
{
    public function submitReport(Student $student, array $data, array $files = []): Report
    {
        return DB::transaction(function () use ($student, $data, $files) {
            $report = Report::create([
                'student_id'     => $student->id,
                'patient_id'     => $data['patient_id'] ?? null,
                'diagnosis'      => $data['diagnosis'] ?? null,
                'report'         => $data['report'],
                'date_submitted' => $data['date_submitted'] ?? now()->toDateString(),
                'status'         => 'pending',
            ]);

            $this->syncProcedures($report, $data['procedures'] ?? []);

            foreach ($files as $file) {
                $docName = $data['document_names'][$file->getClientOriginalName()] ?? $file->getClientOriginalName();
                $this->storeAttachment($report, $file, $docName);
            }

            return $report;
        });
    }

    public function updateReport(Report $report, array $data, array $files = []): Report
    {
        $report->update([
            'patient_id'     => $data['patient_id'] ?? null,
            'diagnosis'      => $data['diagnosis'] ?? null,
            'report'         => $data['report'],
            'date_submitted' => $data['date_submitted'] ?? $report->date_submitted,
        ]);

        $this->syncProcedures($report, $data['procedures'] ?? []);

        foreach ($files as $file) {
            $docName = $data['document_names'][$file->getClientOriginalName()] ?? $file->getClientOriginalName();
            $this->storeAttachment($report, $file, $docName);
        }

        return $report->fresh();
    }

    /**
     * Sync procedures: delete existing ones and re-insert.
     * $procedures = [ ['procedure_id' => 1, 'role' => 'assisted'], ... ]
     */
    private function syncProcedures(Report $report, array $procedures): void
    {
        ReportProcedure::where('report_id', $report->id)->delete();

        foreach ($procedures as $entry) {
            if (!empty($entry['procedure_id']) && !empty($entry['role'])) {
                ReportProcedure::create([
                    'report_id'    => $report->id,
                    'procedure_id' => $entry['procedure_id'],
                    'role'         => $entry['role'],
                ]);
            }
        }
    }

    public function storeAttachment(Report $report, UploadedFile $file, string $documentName): Attachment
    {
        $path = $file->store("attachments/report_{$report->id}", 'public');

        return Attachment::create([
            'report_id'     => $report->id,
            'file_name'     => $file->getClientOriginalName(),
            'file_path'     => $path,
            'file_type'     => $file->getMimeType(),
            'document_name' => $documentName,
            'file_size'     => $file->getSize(),
        ]);
    }

    public function deleteAttachment(Attachment $attachment): void
    {
        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();
    }

    public function updateStatus(Report $report, string $status): Report
    {
        $report->update(['status' => $status]);
        return $report->fresh();
    }

    public function addNote(Report $report, int $userId, string $note): ReportNote
    {
        return ReportNote::create([
            'report_id' => $report->id,
            'user_id'   => $userId,
            'note'      => $note,
        ]);
    }

    public function deleteNote(ReportNote $note): void
    {
        $note->delete();
    }

    public function getStudentReports(Student $student)
    {
        return $student->reports()
            ->with(['attachments', 'notes.user', 'reportProcedures.procedure'])
            ->orderByDesc('date_submitted')
            ->paginate(10);
    }

    public function getSupervisorReports(array $filters = [])
    {
        $query = Report::with(['student.user', 'attachments', 'notes.user', 'reportProcedures.procedure']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['student_id'])) {
            $query->where('student_id', $filters['student_id']);
        }
        if (!empty($filters['date_from'])) {
            $query->whereDate('date_submitted', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('date_submitted', '<=', $filters['date_to']);
        }

        return $query->orderByDesc('date_submitted')->paginate(15);
    }

    public function generateFullReport(Student $student): array
    {
        $reports = $student->reports()
            ->with(['attachments', 'notes.user', 'reportProcedures.procedure'])
            ->orderBy('date_submitted')
            ->get();

        return [
            'student'          => $student->load('user', 'supervisor'),
            'reports'          => $reports,
            'total'            => $reports->count(),
            'approved'         => $reports->where('status', 'approved')->count(),
            'declined'         => $reports->where('status', 'declined')->count(),
            'pending'          => $reports->where('status', 'pending')->count(),
            'procedureSummary' => $student->procedureSummary(),
            'generated_at'     => now(),
        ];
    }

    public function hasReportForDate(Student $student, string $date): bool
    {
        return $student->reports()->whereDate('date_submitted', $date)->exists();
    }
}
