<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 1;    
        $mediaId = null;

        DB::table('events')->insert([
            // =====================
            // Organization ID = 1
            // =====================
            [
                'organization_id' => 1,
                'title' => 'Org 1 – Tech Conference',
                'description' => 'Annual technology conference.',
                'start_date' => Carbon::now()->addDays(2)->toDateString(),
                'end_date' => Carbon::now()->addDays(3)->toDateString(),
                'location' => 'Kathmandu',
                'media_id' => $mediaId,
                'is_published' => true,
                'is_home' => true,
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => 1,
                'title' => 'Org 1 – Laravel Meetup',
                'description' => 'Laravel developers community meetup.',
                'start_date' => Carbon::now()->addDays(5)->toDateString(),
                'end_date' => Carbon::now()->addDays(5)->toDateString(),
                'location' => 'Pokhara',
                'media_id' => $mediaId,
                'is_published' => true, 
                'is_home' => false,
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => 1,
                'title' => 'Org 1 – Startup Pitch',
                'description' => 'Startup pitching event.',
                'start_date' => Carbon::now()->addDays(8)->toDateString(),
                'end_date' => Carbon::now()->addDays(8)->toDateString(),
                'location' => 'Biratnagar',
                'media_id' => $mediaId,
                'is_published' => false,
                'is_home' => false,
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => 1,
                'title' => 'Org 1 – AI Workshop',
                'description' => 'Practical AI & ML workshop.',
                'start_date' => Carbon::now()->addDays(11)->toDateString(),
                'end_date' => Carbon::now()->addDays(12)->toDateString(),
                'location' => 'Lalitpur',
                'media_id' => $mediaId,
                'is_published' => true,
                'is_home' => true,
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => 1,
                'title' => 'Org 1 – Hackathon',
                'description' => '24-hour coding hackathon.',
                'start_date' => Carbon::now()->addDays(15)->toDateString(),
                'end_date' => Carbon::now()->addDays(16)->toDateString(),
                'location' => 'Butwal',
                'media_id' => $mediaId,
                'is_published' => false,
                'is_home' => false,
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // =====================
            // Organization ID = 2
            // =====================
            [
                'organization_id' => 2,
                'title' => 'Org 2 – Business Summit',
                'description' => 'National business leadership summit.',
                'start_date' => Carbon::now()->addDays(3)->toDateString(),
                'end_date' => Carbon::now()->addDays(4)->toDateString(),
                'location' => 'Kathmandu',
                'media_id' => $mediaId,
                'is_published' => true,
                'is_home' => true,
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => 2,
                'title' => 'Org 2 – Marketing Workshop',
                'description' => 'Digital marketing strategies workshop.',
                'start_date' => Carbon::now()->addDays(6)->toDateString(),
                'end_date' => Carbon::now()->addDays(6)->toDateString(),
                'location' => 'Hetauda',
                'media_id' => $mediaId,
                'is_published' => true,
                'is_home' => false,
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => 2,
                'title' => 'Org 2 – Entrepreneurship Talk',
                'description' => 'Talk session with successful entrepreneurs.',
                'start_date' => Carbon::now()->addDays(9)->toDateString(),
                'end_date' => Carbon::now()->addDays(9)->toDateString(),
                'location' => 'Dharan',
                'media_id' => $mediaId,
                'is_published' => false,
                'is_home' => false,
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => 2,
                'title' => 'Org 2 – Design Bootcamp',
                'description' => 'UI/UX design bootcamp.',
                'start_date' => Carbon::now()->addDays(13)->toDateString(),
                'end_date' => Carbon::now()->addDays(14)->toDateString(),
                'location' => 'Bhaktapur',
                'media_id' => $mediaId,
                'is_published' => true,
                'is_home' => true,
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => 2,
                'title' => 'Org 2 – Innovation Fair',
                'description' => 'Showcase of innovative products.',
                'start_date' => Carbon::now()->addDays(18)->toDateString(),
                'end_date' => Carbon::now()->addDays(19)->toDateString(),
                'location' => 'Nepalgunj',
                'media_id' => $mediaId,
                'is_published' => false,
                'is_home' => false,
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
