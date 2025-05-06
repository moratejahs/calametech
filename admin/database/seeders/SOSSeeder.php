<?php

namespace Database\Seeders;

use App\Models\SOS;
use Illuminate\Database\Seeder;

class SOSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sosData = [
            [
            'lat' => '9.071651312307543',
            'long' => '126.162487818678',
            'address' => 'Brgy. Dawis, Tandag City',
            'status' => 'resolved',
            'type' => 'fire',
            'user_id' => 1, // Ensure this user ID exists in the users table
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'lat' => '9.07840811322997',
            'long' => '126.1992890639329',
            'address' => 'Brgy. Draga, Tandag City',
            'status' => 'resolved',
            'type' => 'fire',
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'lat' => '9.066121085386317',
            'long' => '126.1789407724455',
            'status' => 'pending',
            'type' => 'fire',
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            ],
        ];

        foreach ($sosData as $data) {
            SOS::create($data);
        }

    }
}
