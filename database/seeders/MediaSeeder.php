<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;

class MediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::pluck('id')->toArray();

        $mediaSamples = [
            [
                'filename' => 'sample-image-1.jpg',
                'mime_type' => 'image/jpeg',
                'filesize' => 245678,
                'alt_text' => 'Sample Image 1',
                'description' => 'This is a demo image file.',
            ],
            [
                'filename' => 'sample-image-2.png',
                'mime_type' => 'image/png',
                'filesize' => 389456,
                'alt_text' => 'Sample Image 2',
                'description' => 'Another demo image.',
            ],
            [
                'filename' => 'sample-document.pdf',
                'mime_type' => 'application/pdf',
                'filesize' => 1048576,
                'alt_text' => null,
                'description' => 'Demo PDF document.',
            ],
        ];

        foreach ($mediaSamples as $media) {
            DB::table('medias')->insert([
                'filename'      => $media['filename'],
                'disk'          => 'public',
                'filepath'      => 'uploads/' . $media['filename'],
                'mime_type'     => $media['mime_type'],
                'filesize'      => $media['filesize'],
                'alt_text'      => $media['alt_text'],
                'description'   => $media['description'],
                'mediable_type' => null, // Example: App\Models\Post::class
                'mediable_id'   => null, // Example: 1
                'uploader_id'   => !empty($users) ? $users[array_rand($users)] : null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}