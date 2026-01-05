<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::Create([
        //     'guid' => (string) Str::uuid(),
        //     'first_name' => 'Admin',
        //     'last_name' => 'System',
        //     'email' => 'shajahanbasha.syed@gmail.com',
        //     'phone' => '9666668397',
        //     'password' => 'password123',
        //     'role' => 'admin',
        //     'status' => 'active'
        // ]);

        $now = now();

        User::insert([
            [
                    'guid' => (string) Str::uuid(),
                    'first_name' => 'Admin',
                    'last_name' => 'System',
                    'email' => 'shajahanbasha.syed@gmail.com',
                    'phone' => '9666668397',
                    'password' => Hash::make('password123'),
                    'role' => 'admin',
                    'status' => 'active',
                    'is_deleted' => 0,
                    'created_at' => $now,
                    'updated_at' => $now
                ],
            [
                'guid' => (string) Str::uuid(),
                'first_name' => 'Shajahan Basha',
                'last_name' => 'Syed',
                'email' => 'shajahanbasha.in@gmail.com',
                'phone' => '9949427002',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'status' => 'active',
                'is_deleted' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'guid' => (string) Str::uuid(),
                'first_name' => 'Sajida',
                'last_name' => 'Shaikh',
                'email' => 'shaiksajidashaiksajida917@gmail.com',
                'phone' => '8885377785',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'status' => 'active',
                'is_deleted' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);
    }
}
