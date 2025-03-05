<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminPassword = env('ADMIN_PASSWORD');

        $user = User::create([
            'identity_document' => '1002',
            'name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@gmail.com',
            'is_superuser' => true,
            'password' => Hash::make($adminPassword),
        ]);

        $this->command->info('Usuario administrador creado correctamente.');
    }
}
