<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tenant::all()->each(function ($tenant) {
            dd($tenant);
            User::factory()->count(5)->create([
                'tenant_id' => $tenant->id,
            ]);
        });
    }
}
