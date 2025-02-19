<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
