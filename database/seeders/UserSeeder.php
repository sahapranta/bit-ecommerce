<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'is_admin' => true,
            ],
            [
                'name' => 'Customer 1',
                'email' => 'customer1@example.com',
            ],
            [
                'name' => 'Customer 2',
                'email' => 'customer2@example.com',
            ]
        ];

        \App\Models\User::truncate();

        foreach ($users as $user) {
            \App\Models\User::factory()->create($user + [
                'email_verified_at' => now(),
            ]);
        }
    }
}
