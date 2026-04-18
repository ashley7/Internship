<?php

namespace App\Services;

use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function createSupervisor(array $data): User
    {
        return User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'phone'     => $data['phone'] ?? null,
            'password'  => Hash::make($data['password']),
            'role'      => 'supervisor',
            'is_active' => true,
        ]);
    }

    public function createStudent(array $data, int $supervisorId): User
    {
        return DB::transaction(function () use ($data, $supervisorId) {
            $user = User::create([
                'name'      => $data['name'],
                'email'     => $data['email'],
                'phone'     => $data['phone'] ?? null,
                'password'  => Hash::make($data['password']),
                'role'      => 'student',
                'is_active' => true,
            ]);

            Student::create([
                'user_id'               => $user->id,
                'supervisor_id'         => $supervisorId,
                'internship_start_date' => $data['internship_start_date'] ?? null,
                'internship_end_date'   => $data['internship_end_date'] ?? null,
            ]);

            return $user;
        });
    }

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

    public function toggleStatus(User $user): User
    {
        $user->update(['is_active' => !$user->is_active]);
        return $user->fresh();
    }

    public function getSupervisors()
    {
        return User::where('role', 'supervisor')->where('is_active', true)->orderBy('name')->get();
    }

    public function getAllSupervisors()
    {
        return User::where('role', 'supervisor')
            ->withCount('supervisedStudents')
            ->orderBy('name')
            ->get();
    }
}
