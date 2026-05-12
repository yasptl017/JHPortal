<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        User::firstOrCreate(
            ['email' => 'admin@jewishhouse.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        echo "Admin user created/verified successfully!\n";
        echo "Email: admin@jewishhouse.com\n";
        echo "Password: admin\n";
    }
}
