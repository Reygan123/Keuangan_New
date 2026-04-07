<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userData = [
            [
                'name' => 'David',
                'email' => 'David@gmail.com',
                'role' => 'super_admin',
                'password' => bcrypt('123456'),
                'email_verified_at' => now(),
            ]
        ];

            foreach ($userData as $key => $val) {
            User::create($val);
        }
    }
}

