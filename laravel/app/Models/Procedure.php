<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    protected $fillable = ['name', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function reportProcedures(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ReportProcedure::class);
    }
}
