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
            ['email' => 'ashley7520charles@gmail.com'],
            [
                'name'     => 'Charles Thembo',
                'email'    => 'ashley7520charles@gmail.com',
                'phone'    => '+256787444081',
                'password' => Hash::make('admin123@'),
                'role'     => 'super_admin',
                'is_active'=> true,
            ]
        );
    }
}
