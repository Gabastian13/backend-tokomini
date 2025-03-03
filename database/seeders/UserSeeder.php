<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       User::create([
           'name' => 'Administrator',
           'email' => 'admin@mail.com',
           'password' => Hash::make('@dm1nRahasia'),
           'role' => 'admin',
       ]);

        User::create([
            'name' => 'Yufan Amri',
            'email' => 'yufan.amri@mail.com',
            'password' => Hash::make('U53R@Rahasia'),
        ]);
    }
}
