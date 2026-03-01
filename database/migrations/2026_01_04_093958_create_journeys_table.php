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
        Schema::create('journeys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')
                ->constrained('organizations');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedBigInteger('media_id')->nullable();
            $table->foreign('media_id')
                ->references('id')
                ->on('medias');
            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')
                ->constrained('users');
            $table->foreignId('updated_by')
                ->nullable()                
                ->nullOnDelete()
                ->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journeys');
    }
};
