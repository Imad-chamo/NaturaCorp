<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // Création des rôles
        $roles = ['admin', 'commercial', 'logistique'];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@naturacorp.com'],
            ['name' => 'admin', 'password' => bcrypt('password')]
        );
        $admin->assignRole('admin');

        // Commerciaux
        for ($i = 1; $i <= 5; $i++) {
            $user = User::firstOrCreate(
                ['email' => "commercial{$i}@naturacorp.com"],
                ['name' => "commercial{$i}", 'password' => bcrypt('password')]
            );
            $user->assignRole('commercial');
        }

        // Logistiques
        for ($i = 1; $i <= 5; $i++) {
            $user = User::firstOrCreate(
                ['email' => "logistique{$i}@naturacorp.com"],
                ['name' => "logistique{$i}", 'password' => bcrypt('password')]
            );
            $user->assignRole('logistique');
        }
    }
}
