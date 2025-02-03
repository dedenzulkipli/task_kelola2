<?php

namespace Database\Seeders;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        User::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'no_hp' => '08123456789',
            'address' => 'Jl. Admin Utama',
            'jurusan' => 'Teknologi Informasi',
            'status' => 1,
            'password' => bcrypt('adminpassword'),
            'role_name' => 'Admin',
        ]);
    }
}
