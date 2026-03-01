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
        Schema::create('home_carousels', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('organization_id');
            $table->foreign('organization_id')->references('id')->on('organizations');

            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->unsignedBigInteger('media_id');
            $table->foreign('media_id')->references('id')->on('medias');

            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);

            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');

            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_carousels');
    }
};
