<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('notices', function (Blueprint $table) {
            // Convert existing 'attachment' column to unsignedBigInteger
            $table->unsignedBigInteger('attachment')->nullable()->change();

            // Add foreign key
            $table->foreign('attachment')->references('id')->on('medias');
        });
    }

    public function down(): void
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->dropForeign(['attachment']);
            $table->string('attachment')->nullable()->change();
        });
    }
};