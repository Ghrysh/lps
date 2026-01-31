<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Role
        $adminRole = Role::create([
            'name' => 'Administrator',
            'slug' => 'admin'
        ]);

        $userRole = Role::create([
            'name' => 'Regular User',
            'slug' => 'user'
        ]);

        // Buat Akun Admin
        $admin = User::create([
            'name' => 'Admin Orange',
            'email' => 'admin@lps.go.id',
            'password' => Hash::make('!admin123'),
        ]);
        $admin->roles()->attach($adminRole);

        // Buat Akun User Biasa
        $user = User::create([
            'name' => 'Budi User',
            'email' => 'user@lps.go.id',
            'password' => Hash::make('!user123'),
        ]);
        $user->roles()->attach($userRole);
    }
}