<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id', 'file_name', 'file_path', 'file_type', 'document_name', 'file_size',
    ];

    public function report(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function getIconClass(): string
    {
        return match (true) {
            str_contains($this->file_type, 'pdf')   => 'bi-file-earmark-pdf text-danger',
            str_contains($this->file_type, 'image') => 'bi-file-earmark-image text-primary',
            str_contains($this->file_type, 'word')  => 'bi-file-earmark-word text-primary',
            default                                  => 'bi-file-earmark text-secondary',
        };
    }

    public function getFileSizeFormatted(): string
    {
        if (!$this->file_size) return '';
        $kb = $this->file_size / 1024;
        if ($kb < 1024) return round($kb, 1) . ' KB';
        return round($kb / 1024, 1) . ' MB';
    }
}
