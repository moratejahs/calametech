<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $now = now();

        User::create([
            'name' => 'Admin',
            'address' => 'Tandag City',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'contact_number' => '09706122212',
            'password' => bcrypt('123'),
            'plain_password' => '123',
            'email_verified_at' =>now(),
        ])->roles()->attach($adminRole, ['created_at' => $now, 'updated_at' => $now]);
    }
}
