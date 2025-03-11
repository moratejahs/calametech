<?php

namespace Database\Seeders;

use App\Models\SOS;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SOSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SOS::created([
            [
                'lat' => 123456,
                'long' => 654321,
                'status' => 'pending',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'lat' => 234567,
                'long' => 765432,
                'status' => 'resolved',
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'lat' => 345678,
                'long' => 876543,
                'status' => 'dismissed',
                'user_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
