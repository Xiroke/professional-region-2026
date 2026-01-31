<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'admin',
            'is_admin' => true,
            'email' => 'admin@edu.com',
            'password' => Hash::make('course2025')
        ]);

        User::factory()->create([
            'name' => 'Иван',
            'is_admin' => false,
            'email' => 'ivan@example.com',
            'password' => Hash::make('P@ssw0rd')
        ]);

        User::factory()->create([
            'name' => 'Александр',
            'is_admin' => false,
            'email' => 'ivan14@example.com',
            'password' => Hash::make('P@ssw0rd')
        ]);
    }
}
