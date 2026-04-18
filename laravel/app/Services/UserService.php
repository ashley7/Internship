<?php

namespace App\Services;

use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * Create a new supervisor account (called by super_admin).
     */
    public function createSupervisor(array $data): User
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role'     => 'supervisor',
            'is_active'=> true,
        ]);
    }

    /**
     * Create a new student account (called by supervisor).
     */
    public function createStudent(array $data, int $supervisorId): User
    {
        return DB::transaction(function () use ($data, $supervisorId) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'phone'    => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
                'role'     => 'student',
                'is_active'=> true,
            ]);

            Student::create([
                'user_id'                => $user->id,
                'school'                 => $data['school'],
                'student_number'         => $data['student_number'],
                'supervisor_id'          => $supervisorId,
                'internship_start_date'  => $data['internship_start_date'] ?? null,
                'internship_end_date'    => $data['internship_end_date'] ?? null,
            ]);

            return $user;
        });
    }

    /**
     * Update a user's profile.
     */
    public function updateUser(User $user, array $data): User
    {
        $updateData = [
            'name'  => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);
        return $user->fresh();
    }

    /**
     * Toggle a user's active status.
     */
    public function toggleStatus(User $user): User
    {
        $user->update(['is_active' => !$user->is_active]);
        return $user->fresh();
    }

    /**
     * Get all supervisors for dropdown.
     */
    public function getSupervisors()
    {
        return User::where('role', 'supervisor')->where('is_active', true)->orderBy('name')->get();
    }

    /**
     * Get all supervisors with their student counts.
     */
    public function getAllSupervisors()
    {
        return User::where('role', 'supervisor')
            ->withCount('supervisedStudents')
            ->orderBy('name')
            ->get();
    }
}
