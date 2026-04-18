<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'supervisor_id',
        'internship_start_date', 'internship_end_date',
    ];

    protected $casts = [
        'internship_start_date' => 'date',
        'internship_end_date'   => 'date',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supervisor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function reports(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Aggregate procedure counts by role for this student.
     * Returns: [ 'Hernia repair' => ['observed'=>2,'assisted'=>3,'performed'=>10], ... ]
     */
    public function procedureSummary(): array
    {
        $rows = DB::table('report_procedures')
            ->join('reports',    'reports.id',    '=', 'report_procedures.report_id')
            ->join('procedures', 'procedures.id', '=', 'report_procedures.procedure_id')
            ->where('reports.student_id', $this->id)
            ->select('procedures.name', 'report_procedures.role', DB::raw('COUNT(*) as total'))
            ->groupBy('procedures.name', 'report_procedures.role')
            ->get();

        $summary = [];
        foreach ($rows as $row) {
            if (!isset($summary[$row->name])) {
                $summary[$row->name] = ['observed' => 0, 'assisted' => 0, 'performed' => 0];
            }
            $summary[$row->name][$row->role] = $row->total;
        }

        ksort($summary);
        return $summary;
    }
}
