<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('faqs')->insert([
            [
                'id' => 1,
                'organization_id' => 1,
                'question' => 'What does CNI Koshi do?',
                'answer' => 'CNI Koshi works to promote industrial growth, represent business interests, and support policy advocacy in the Koshi Province.',
                'order_index' => 0,
                'is_active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'organization_id' => 1,
                'question' => 'Who can become a member of CNI Koshi?',
                'answer' => 'Any registered industry or business operating in Koshi Province can apply for membership.',
                'order_index' => 0,
                'is_active' => 1,
                'created_by' => 1,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'organization_id' => 1,
                'question' => 'What benefits do members receive?',
                'answer' => 'Members gain access to advocacy support, networking events, industry insights, and policy representation.',
                'order_index' => 0,
                'is_active' => 1,
                'created_by' => 1,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'organization_id' => 2,
                'question' => 'asd',
                'answer' => 'asd',
                'order_index' => 0,
                'is_active' => 1,
                'created_by' => 1,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
