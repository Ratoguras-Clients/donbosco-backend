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
        Schema::create('organization_staff_roles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('organization_staff_id');
            $table->foreign('organization_staff_id')
                ->references('id')
                ->on('organization_staff');

            $table->unsignedBigInteger('staff_role_id');
            $table->foreign('staff_role_id')
                ->references('id')
                ->on('staff_roles');

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
        Schema::dropIfExists('organization_staff_roles');
    }
};
