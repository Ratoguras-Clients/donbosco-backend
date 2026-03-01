<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Example dummy data
        Role::firstOrCreate(['name' => 'super-admin'], ['level' => 1]);
        Role::firstOrCreate(['name' => 'admin'], ['level' => 2]);
    }
}
