<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('homemissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->string('title');
            $table->longText('content')->nullable();
            
            // Three image columns instead of one
            $table->unsignedBigInteger('media_id_1')->nullable();
            $table->foreign('media_id_1')->references('id')->on('medias')->onDelete('set null');
            
            $table->unsignedBigInteger('media_id_2')->nullable();
            $table->foreign('media_id_2')->references('id')->on('medias')->onDelete('set null');
            
            $table->unsignedBigInteger('media_id_3')->nullable();
            $table->foreign('media_id_3')->references('id')->on('medias')->onDelete('set null');
            
            $table->boolean('is_home')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('homemissions');
    }
};