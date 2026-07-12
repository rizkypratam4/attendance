<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'Rizky',
            'last_name' => 'Pratama',
            'email' => 'rizkypratama@gmail.com',
            'role' => 'IT',
            'password' => Hash::make('logic301'),
            'status' => true,
            "last_login" => null,
            'remember_token' => Str::random(10),
        ]);
    }
}
