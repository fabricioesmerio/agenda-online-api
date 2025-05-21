<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserPassword;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserPasswordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::all()->each(function ($user) {
            for ($i = 0; $i < 3; $i++) {
                UserPassword::create([
                    'user_id' => $user->id,
                    'password' => Hash::make('SenhaAntiga' . $i),
                ]);
            }
        });
    }
}
