<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JourneySeeder extends Seeder
{
    public function run(): void
    {
        $userId = 1; // must exist in users table

        DB::table('journeys')->insert([

            // =====================
            // Organization ID = 1
            // =====================
            [
                'organization_id' => 1,
                'title' => 'Company Founded',
                'description' => 'The organization was officially founded.',
                'start_date' => Carbon::now()->subYears(5),
                'end_date' => null,
                'order_index' => 1,
                'is_active' => true,
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => 1,
                'title' => 'First Major Project',
                'description' => 'Successfully completed the first major project.',
                'start_date' => Carbon::now()->subYears(4),
                'end_date' => Carbon::now()->subYears(4)->addMonths(6),
                'order_index' => 2,
                'is_active' => true,
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => 1,
                'title' => 'Team Expansion',
                'description' => 'Expanded the core team and departments.',
                'start_date' => Carbon::now()->subYears(3),
                'end_date' => null,
                'order_index' => 3,
                'is_active' => true,
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => 1,
                'title' => 'Regional Office Opened',
                'description' => 'Opened a new regional office.',
                'start_date' => Carbon::now()->subYears(2),
                'end_date' => null,
                'order_index' => 4,
                'is_active' => true,
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => 1,
                'title' => 'International Expansion',
                'description' => 'Expanded operations internationally.',
                'start_date' => Carbon::now()->subYear(),
                'end_date' => null,
                'order_index' => 5,
                'is_active' => true,
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // =====================
            // Organization ID = 2
            // =====================
            [
                'organization_id' => 2,
                'title' => 'Organization Established',
                'description' => 'The organization began its journey.',
                'start_date' => Carbon::now()->subYears(6),
                'end_date' => null,
                'order_index' => 1,
                'is_active' => true,
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => 2,
                'title' => 'Product Launch',
                'description' => 'Launched the first flagship product.',
                'start_date' => Carbon::now()->subYears(4)->addMonths(3),
                'end_date' => null,
                'order_index' => 2,
                'is_active' => true,
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => 2,
                'title' => 'Market Growth Phase',
                'description' => 'Entered a rapid growth phase.',
                'start_date' => Carbon::now()->subYears(3),
                'end_date' => Carbon::now()->subYears(2),
                'order_index' => 3,
                'is_active' => true,
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => 2,
                'title' => 'Technology Upgrade',
                'description' => 'Major technology and infrastructure upgrade.',
                'start_date' => Carbon::now()->subYears(2),
                'end_date' => null,
                'order_index' => 4,
                'is_active' => true,
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => 2,
                'title' => 'Strategic Partnership',
                'description' => 'Formed strategic partnerships.',
                'start_date' => Carbon::now()->subYear(),
                'end_date' => null,
                'order_index' => 5,
                'is_active' => true,
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
