<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $superAdminRole = Role::where('name', 'super-admin')->first();
        $now = now();

        User::create([
            'name' => 'Reygenix Admin',
            'address' => 'Sotil Tandag City',
            'email' => 'admin@mail.com',
            'contact_number' => '09706122212',
            'address' => 'Sotil Tandag City',
            'password' => 'password',
            'plain_password' => Crypt::encrypt('password'),
        ])->roles()->attach($adminRole, ['created_at' => $now, 'updated_at' => $now]);

        User::create([
            'name' => 'Reygenix Super Admin',
            'address' => 'Sotil Tandag City',
            'email' => 'superadmin@mail.com',
            'contact_number' => '09706122212',
            'address' => 'Sotil Tandag City',
            'password' => 'password',
            'plain_password' => Crypt::encrypt('password'),
        ])->roles()->attach($superAdminRole, ['created_at' => $now, 'updated_at' => $now]);
    }
}
