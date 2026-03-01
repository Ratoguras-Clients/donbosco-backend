<?php

namespace Database\Seeders;

use App\Models\Notice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;


class NoticeSeeder extends Seeder
{
    public function run(): void
    {
        // Use first user as created_by
        $user = DB::table('users')->first();
        if (!$user) {
            $this->command->info('No users found. Please create at least one user first.');
            return;
        }
        $userId = $user->id;

        $noticesData = [
            // Organization 1
            [
                'organization_id' => 1,
                'title'           => 'Notice 1 for Organization 1',
                'description'     => 'Sample description for notice 1 of organization 1.',
                'attachment'      => null,
                'notice_date'     => Carbon::now()->toDateString(),
                'priority'        => 'high',
                'is_published'    => true,
                'created_by'      => $userId,
            ],
            [
                'organization_id' => 1,
                'title'           => 'Notice 2 for Organization 1',
                'description'     => 'Sample description for notice 2 of organization 1.',
                'attachment'      => null,
                'notice_date'     => Carbon::now()->addDay()->toDateString(),
                'priority'        => 'medium',
                'is_published'    => false,
                'created_by'      => $userId,
            ],
            [
                'organization_id' => 1,
                'title'           => 'Notice 3 for Organization 1',
                'description'     => 'Sample description for notice 3 of organization 1.',
                'attachment'      => null,
                'notice_date'     => Carbon::now()->addDays(2)->toDateString(),
                'priority'        => 'low',
                'is_published'    => true,
                'created_by'      => $userId,
            ],
            [
                'organization_id' => 1,
                'title'           => 'Notice 4 for Organization 1',
                'description'     => 'Sample description for notice 4 of organization 1.',
                'attachment'      => null,
                'notice_date'     => Carbon::now()->addDays(3)->toDateString(),
                'priority'        => 'medium',
                'is_published'    => false,
                'created_by'      => $userId,
            ],
            [
                'organization_id' => 1,
                'title'           => 'Notice 5 for Organization 1',
                'description'     => 'Sample description for notice 5 of organization 1.',
                'attachment'      => null,
                'notice_date'     => Carbon::now()->addDays(4)->toDateString(),
                'priority'        => 'high',
                'is_published'    => true,
                'created_by'      => $userId,
            ],

            // Organization 2
            [
                'organization_id' => 2,
                'title'           => 'Notice 1 for Organization 2',
                'description'     => 'Sample description for notice 1 of organization 2.',
                'attachment'      => null,
                'notice_date'     => Carbon::now()->toDateString(),
                'priority'        => 'medium',
                'is_published'    => true,
                'created_by'      => $userId,
            ],
            [
                'organization_id' => 2,
                'title'           => 'Notice 2 for Organization 2',
                'description'     => 'Sample description for notice 2 of organization 2.',
                'attachment'      => null,
                'notice_date'     => Carbon::now()->addDay()->toDateString(),
                'priority'        => 'high',
                'is_published'    => false,
                'created_by'      => $userId,
            ],
            [
                'organization_id' => 2,
                'title'           => 'Notice 3 for Organization 2',
                'description'     => 'Sample description for notice 3 of organization 2.',
                'attachment'      => null,
                'notice_date'     => Carbon::now()->addDays(2)->toDateString(),
                'priority'        => 'low',
                'is_published'    => true,
                'created_by'      => $userId,
            ],
            [
                'organization_id' => 2,
                'title'           => 'Notice 4 for Organization 2',
                'description'     => 'Sample description for notice 4 of organization 2.',
                'attachment'      => null,
                'notice_date'     => Carbon::now()->addDays(3)->toDateString(),
                'priority'        => 'medium',
                'is_published'    => false,
                'created_by'      => $userId,
            ],
            [
                'organization_id' => 2,
                'title'           => 'Notice 5 for Organization 2',
                'description'     => 'Sample description for notice 5 of organization 2.',
                'attachment'      => null,
                'notice_date'     => Carbon::now()->addDays(4)->toDateString(),
                'priority'        => 'high',
                'is_published'    => true,
                'created_by'      => $userId,
            ],
        ];

        foreach ($noticesData as $data) {
            Notice::create($data);
        }
    }
}