<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 1; // must exist in users table

        DB::table('services')->insert([

            // =====================
            // Organization ID = 1
            // =====================
            [
                'organization_id' => 1,
                'title' => 'Web Development',
                'description' => 'Custom website and web application development.',
                'icon' => 'fa-solid fa-code',
                'order_index' => 1,
                'is_active' => true,
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => 1,
                'title' => 'Mobile App Development',
                'description' => 'Android and iOS mobile application development.',
                'icon' => 'fa-solid fa-mobile',
                'order_index' => 2,
                'is_active' => true,
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => 1,
                'title' => 'UI / UX Design',
                'description' => 'User-centered UI/UX design services.',
                'icon' => 'fa-solid fa-paintbrush',
                'order_index' => 3,
                'is_active' => true,
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => 1,
                'title' => 'SEO Optimization',
                'description' => 'Search engine optimization and analytics.',
                'icon' => 'fa-solid fa-chart-line',
                'order_index' => 4,
                'is_active' => true,
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => 1,
                'title' => 'IT Consulting',
                'description' => 'Professional IT consulting and support.',
                'icon' => 'fa-solid fa-handshake',
                'order_index' => 5,
                'is_active' => true,
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // =====================
            // Organization ID = 2
            // =====================
            [
                'organization_id' => 2,
                'title' => 'Digital Marketing',
                'description' => 'Online marketing and branding solutions.',
                'icon' => 'fa-solid fa-bullhorn',
                'order_index' => 1,
                'is_active' => true,
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => 2,
                'title' => 'Content Creation',
                'description' => 'Creative content writing and media production.',
                'icon' => 'fa-solid fa-pen-nib',
                'order_index' => 2,
                'is_active' => true,
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => 2,
                'title' => 'Brand Strategy',
                'description' => 'Brand identity and strategic planning.',
                'icon' => 'fa-solid fa-lightbulb',
                'order_index' => 3,
                'is_active' => true,
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => 2,
                'title' => 'Cloud Services',
                'description' => 'Cloud hosting and infrastructure services.',
                'icon' => 'fa-solid fa-cloud',
                'order_index' => 4,
                'is_active' => true,
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => 2,
                'title' => 'Cyber Security',
                'description' => 'Security audits and protection solutions.',
                'icon' => 'fa-solid fa-shield-halved',
                'order_index' => 5,
                'is_active' => true,
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
