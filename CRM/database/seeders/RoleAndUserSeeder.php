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
        $admin = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@naturacorp.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        // Commerciaux
        for ($i = 1; $i <= 5; $i++) {
            User::factory()->create([
                'name' => "commercial{$i}",
                'email' => "commercial{$i}@naturacorp.com",
                'password' => bcrypt('password'),
            ])->assignRole('commercial');
        }

        // Logistiques
        for ($i = 1; $i <= 5; $i++) {
            User::factory()->create([
                'name' => "logistique{$i}",
                'email' => "logistique{$i}@naturacorp.com",
                'password' => bcrypt('password'),
            ])->assignRole('logistique');
        }
    }
}
