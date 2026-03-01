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
        Schema::create('other_organizations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('organization_id');
            $table->foreign('organization_id')->references('id')->on('organizations');

            $table->string('name');
            $table->string('short_name')->nullable();
            $table->string('slug')->unique();
            $table->text('mission')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('logo')->nullable();
            $table->foreign('logo')->references('id')->on('medias')->onDelete('set null');
            $table->string('url')->nullable();
            $table->date('established_date')->nullable();
            $table->string('status')->default('active');

            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')
                ->references('id')
                ->on('users');

            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')
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
        Schema::dropIfExists('other_organizations');
    }
};