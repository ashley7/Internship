<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'patient_id', 'diagnosis', 'report', 'date_submitted', 'status',
    ];

    protected $casts = [
        'date_submitted' => 'date',
    ];

    public function student(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function attachments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function notes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ReportNote::class)->with('user')->latest();
    }

    public function reportProcedures(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ReportProcedure::class)->with('procedure');
    }

    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'approved' => 'badge-success',
            'declined' => 'badge-danger',
            default    => 'badge-warning',
        };
    }
}
