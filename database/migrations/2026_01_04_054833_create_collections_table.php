<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organization_id')->constrained('organizations');

            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('cover_image')->nullable();
            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');

            $table->timestamps();
            $table->foreign('cover_image')->references('id')->on('medias')->onDelete('set null');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};