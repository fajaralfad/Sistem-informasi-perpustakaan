<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $admins = [
            [
                'name' => 'Admin',
                'email' => 'admin@perpustakaan.com',
                'password' => bcrypt('kejari23'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Admin 2',
                'email' => 'admin2@perpustakaan.com',
                'password' => bcrypt('admin234'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($admins as $admin) {
            User::firstOrCreate(
                ['email' => $admin['email']], // Kondisi unik
                $admin
            );
        }
    }
}
