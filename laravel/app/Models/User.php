<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'role', 'is_active',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];

    // Relationships
    public function student(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function supervisedStudents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Student::class, 'supervisor_id');
    }

    public function reportNotes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ReportNote::class);
    }

    // Helpers
    public function isSuperAdmin(): bool { return $this->role === 'super_admin'; }
    public function isSupervisor(): bool { return $this->role === 'supervisor'; }
    public function isStudent(): bool    { return $this->role === 'student'; }
}
