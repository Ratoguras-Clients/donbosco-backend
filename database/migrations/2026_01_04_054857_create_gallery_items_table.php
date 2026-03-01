<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organization_id')->constrained('organizations');
            $table->foreignId('collection_id')->constrained('collections');

            $table->string('title')->nullable();
            $table->unsignedBigInteger('media_id')->nullable();
            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_cover')->default(false);

            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');

            $table->timestamps();

            $table->foreign('media_id')->references('id')->on('medias')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_items');
    }
};