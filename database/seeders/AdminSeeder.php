<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'nama' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@camping.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'alamat' => 'Jl. Admin No. 1, Jakarta',
            'telp' => '081234567890',
        ]);

        echo "✓ Admin user created: {$admin->email}\n";
    }
}
