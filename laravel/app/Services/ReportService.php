<?php

namespace App\Services;

use App\Models\Report;
use App\Models\ReportNote;
use App\Models\Attachment;
use App\Models\Student;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReportService
{
    /**
     * Submit a daily report by a student.
     */
    public function submitReport(Student $student, array $data, array $files = []): Report
    {
        return DB::transaction(function () use ($student, $data, $files) {
            $report = Report::create([
                'student_id'    => $student->id,
                'report'        => $data['report'],
                'date_submitted'=> $data['date_submitted'] ?? now()->toDateString(),
                'status'        => 'pending',
            ]);

            foreach ($files as $file) {
                $this->storeAttachment($report, $file, $data['document_names'][$file->getClientOriginalName()] ?? $file->getClientOriginalName());
            }

            return $report;
        });
    }

    /**
     * Update an existing report (student only, when status is pending).
     */
    public function updateReport(Report $report, array $data, array $files = []): Report
    {
        $report->update([
            'report'         => $data['report'],
            'date_submitted' => $data['date_submitted'] ?? $report->date_submitted,
        ]);

        foreach ($files as $file) {
            $docName = $data['document_names'][$file->getClientOriginalName()] ?? $file->getClientOriginalName();
            $this->storeAttachment($report, $file, $docName);
        }

        return $report->fresh();
    }

    /**
     * Store a file attachment on a report.
     */
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

    /**
     * Delete an attachment.
     */
    public function deleteAttachment(Attachment $attachment): void
    {
        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();
    }

    /**
     * Update report status (supervisor action).
     */
    public function updateStatus(Report $report, string $status): Report
    {
        $report->update(['status' => $status]);
        return $report->fresh();
    }

    /**
     * Add a note to a report (student or supervisor).
     */
    public function addNote(Report $report, int $userId, string $note): ReportNote
    {
        return ReportNote::create([
            'report_id' => $report->id,
            'user_id'   => $userId,
            'note'      => $note,
        ]);
    }

    /**
     * Delete a note.
     */
    public function deleteNote(ReportNote $note): void
    {
        $note->delete();
    }

    /**
     * Get all reports for a student, ordered by date desc.
     */
    public function getStudentReports(Student $student)
    {
        return $student->reports()
            ->with(['attachments', 'notes.user'])
            ->orderByDesc('date_submitted')
            ->paginate(10);
    }

    /**
     * Get all reports for students under a supervisor.
     */
    public function getSupervisorReports(int $supervisorId, array $filters = [])
    {
        $query = Report::whereHas('student', fn($q) => $q->where('supervisor_id', $supervisorId))
            ->with(['student.user', 'attachments', 'notes.user']);

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

    /**
     * Generate full internship report data for a student.
     */
    public function generateFullReport(Student $student): array
    {
        $reports = $student->reports()
            ->with(['attachments', 'notes.user'])
            ->orderBy('date_submitted')
            ->get();

        return [
            'student'     => $student->load('user', 'supervisor'),
            'reports'     => $reports,
            'total'       => $reports->count(),
            'approved'    => $reports->where('status', 'approved')->count(),
            'declined'    => $reports->where('status', 'declined')->count(),
            'pending'     => $reports->where('status', 'pending')->count(),
            'generated_at'=> now(),
        ];
    }

    /**
     * Check if student already submitted a report for a given date.
     */
    public function hasReportForDate(Student $student, string $date): bool
    {
        return $student->reports()->whereDate('date_submitted', $date)->exists();
    }
}
