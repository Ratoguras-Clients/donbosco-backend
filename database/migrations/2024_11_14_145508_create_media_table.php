<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->id();
            $table->string('filename'); // Original filename
            $table->string('disk')->default('public'); // Storage disk (e.g., 'public', 's3')
            $table->string('filepath'); // Relative path to the file on the disk (e.g., 'uploads/images/my-image.jpg')
            $table->string('mime_type', 100); // MIME type of the file (e.g., 'image/jpeg', 'application/pdf')
            $table->unsignedBigInteger('filesize'); // File size in bytes
            $table->string('alt_text')->nullable(); // Optional alt text for images
            $table->text('description')->nullable(); // Optional description for the media

            // Polymorphic relation fields
            $table->nullableMorphs('mediable'); // Adds mediable_type (string) and mediable_id (unsignedBigInteger)

            // Uploader user ID
            $table->foreignId('uploader_id')->nullable()->constrained('users')->onDelete('set null');

            // Indexes for performance
            $table->index(['mime_type']);
            $table->index(['uploader_id']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
