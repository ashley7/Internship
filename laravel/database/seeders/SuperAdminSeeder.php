<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@internship.com'],
            [
                'name'     => 'Super Administrator',
                'email'    => 'admin@internship.com',
                'phone'    => '+256700000000',
                'password' => Hash::make('Admin@1234'),
                'role'     => 'super_admin',
                'is_active'=> true,
            ]
        );
    }
}
