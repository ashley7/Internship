<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportProcedure extends Model
{
    protected $fillable = ['report_id', 'procedure_id', 'role'];

    public function report(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function procedure(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Procedure::class);
    }

    public function getRoleBadgeClass(): string
    {
        return match ($this->role) {
            'performed' => 'bg-success',
            'assisted'  => 'bg-primary',
            'observed'  => 'bg-secondary',
            default     => 'bg-light text-dark',
        };
    }
}
