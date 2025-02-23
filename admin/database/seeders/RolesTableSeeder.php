<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Admin',
            ],
            [
                'name' => 'user',
                'description' => 'User',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
