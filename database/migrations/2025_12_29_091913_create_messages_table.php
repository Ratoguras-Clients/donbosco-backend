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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('organization_id')->nullable();
            $table->foreign('organization_id')
                ->references('id')
                ->on('organizations');

            $table->unsignedBigInteger('organization_staff_id')->nullable();
            $table->foreign('organization_staff_id')
                ->references('id')
                ->on('organization_staff');

            $table->string('title');
            $table->text('content')->nullable();
            $table->date('date')->nullable();
            $table->string('tenure')->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_home')->default(true);

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
        Schema::dropIfExists('messages');
    }
};
