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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->foreign('organization_id')
                ->references('id')
                ->on('organizations');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('name')->nullable();
            $table->date('start_date')->nullable();
            $table->unsignedBigInteger('media_id')->nullable();
            $table->foreign('media_id')
                ->references('id')
                ->on('medias');
            $table->boolean('is_published')->default(false);
            $table->boolean('is_home')->default(false);
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')
                ->references('id')
                ->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
