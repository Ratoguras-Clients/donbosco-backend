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
        Schema::create('organization_staff', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('organization_id')->nullable();
            $table->foreign('organization_id')
                ->references('id')
                ->on('organizations');

            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('media_id')->nullable();
            $table->foreign('media_id')
                ->references('id')
                ->on('medias');

            $table->text('bio')->nullable();

            $table->integer('order_index')->default(0);

            $table->boolean('is_active')->default(true);

            $table->unsignedBigInteger('created_by')->nullable();
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
        Schema::dropIfExists('organization_staff');
    }
};
