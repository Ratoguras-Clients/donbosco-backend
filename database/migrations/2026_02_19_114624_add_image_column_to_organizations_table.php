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
        Schema::table('organizations', function (Blueprint $table) {
            // Add the new image column after the 'logo' column
            $table->unsignedBigInteger('image')->nullable()->after('logo');
            $table->foreign('image')->references('id')->on('medias')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropForeign(['image']); // drop foreign first
            $table->dropColumn('image');    // then drop column
        });
    }
};
