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
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')
                ->constrained('organizations');
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('attachment')->nullable();
            $table->date('notice_date')->nullable();
            $table->string('priority', 20)
                ->default('medium')
                ->nullable(false);
            $table->boolean('is_published')->default(false);



            $table->foreignId('created_by')
                ->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
