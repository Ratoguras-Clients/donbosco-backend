<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Seeders\Permission\RestaurantSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
                // RoleSeeder::class,
                // PermissionSeeder::class,
            UserSeeder::class,
            OrganizationSeeder::class,
            // StaffRoleSeeder::class,
            // FaqSeeder::class,
            // StaffSeeder::class,
            // JourneySeeder::class,
            // EventSeeder::class,
            // ServiceSeeder::class,
            // NoticeSeeder::class,
            // NewsSeeder::class,
            // OrganizationSeeder::class,
            // MediaSeeder::class,
        ]);
    }
}
