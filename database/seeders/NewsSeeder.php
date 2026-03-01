<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 1;     // Must exist in users table
        $mediaId = null; // Optional

        $newsData = [
            // =====================
            // Organization ID = 1
            // =====================
            [
                'organization_id' => 1,
                'title' => 'Org 1 – Company Expansion Announced',
                'summary' => 'The organization announces a major expansion plan.',
                'content' => 'Detailed content about expansion plans and future roadmap.',
                'media_id' => $mediaId,
                'published_date' => Carbon::now()->subDays(10)->toDateString(),
                'is_published' => true,
                'is_home' => true,
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'organization_id' => 1,
                'title' => 'Org 1 – New Partnership Signed',
                'summary' => 'Strategic partnership with a regional company.',
                'content' => 'Full article describing the partnership and its impact.',
                'media_id' => $mediaId,
                'published_date' => Carbon::now()->subDays(8)->toDateString(),
                'is_published' => true,
                'is_home' => false,
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'organization_id' => 1,
                'title' => 'Org 1 – Internal Training Program',
                'summary' => 'New training initiative for staff.',
                'content' => 'Details about skills development and training sessions.',
                'media_id' => $mediaId,
                'published_date' => Carbon::now()->subDays(6)->toDateString(),
                'is_published' => false,
                'is_home' => false,
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'organization_id' => 1,
                'title' => 'Org 1 – CSR Activity Conducted',
                'summary' => 'Corporate social responsibility initiative.',
                'content' => 'Coverage of CSR activity and community impact.',
                'media_id' => $mediaId,
                'published_date' => Carbon::now()->subDays(4)->toDateString(),
                'is_published' => true,
                'is_home' => true,
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'organization_id' => 1,
                'title' => 'Org 1 – Yearly Performance Review',
                'summary' => 'Annual performance review highlights.',
                'content' => 'In-depth analysis of yearly performance.',
                'media_id' => $mediaId,
                'published_date' => Carbon::now()->subDays(2)->toDateString(),
                'is_published' => false,
                'is_home' => false,
                'created_by' => $userId,
                'updated_by' => $userId,
            ],

            // =====================
            // Organization ID = 2
            // =====================
            [
                'organization_id' => 2,
                'title' => 'Org 2 – Product Launch Event',
                'summary' => 'Launch of a new product line.',
                'content' => 'Full coverage of the product launch event.',
                'media_id' => $mediaId,
                'published_date' => Carbon::now()->subDays(9)->toDateString(),
                'is_published' => true,
                'is_home' => true,
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'organization_id' => 2,
                'title' => 'Org 2 – Industry Award Won',
                'summary' => 'Recognition received at industry level.',
                'content' => 'Details about the award and judging criteria.',
                'media_id' => $mediaId,
                'published_date' => Carbon::now()->subDays(7)->toDateString(),
                'is_published' => true,
                'is_home' => false,
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'organization_id' => 2,
                'title' => 'Org 2 – Staff Recruitment Drive',
                'summary' => 'New recruitment drive announced.',
                'content' => 'Positions available and application process.',
                'media_id' => $mediaId,
                'published_date' => Carbon::now()->subDays(5)->toDateString(),
                'is_published' => false,
                'is_home' => false,
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'organization_id' => 2,
                'title' => 'Org 2 – Sustainability Initiative',
                'summary' => 'Steps taken toward environmental sustainability.',
                'content' => 'Long-term sustainability strategy details.',
                'media_id' => $mediaId,
                'published_date' => Carbon::now()->subDays(3)->toDateString(),
                'is_published' => true,
                'is_home' => true,
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
            [
                'organization_id' => 2,
                'title' => 'Org 2 – Quarterly Financial Update',
                'summary' => 'Financial performance update.',
                'content' => 'Revenue, expenses, and growth analysis.',
                'media_id' => $mediaId,
                'published_date' => Carbon::now()->subDays(1)->toDateString(),
                'is_published' => false,
                'is_home' => false,
                'created_by' => $userId,
                'updated_by' => $userId,
            ],
        ];

        foreach ($newsData as $data) {
            News::create($data);
        }
    }
}