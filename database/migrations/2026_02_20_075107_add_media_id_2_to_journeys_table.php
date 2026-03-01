<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('journeys', function (Blueprint $table) {
            $table->unsignedBigInteger('media_id_2')->nullable()->after('media_id');
            $table->foreign('media_id_2')->references('id')->on('medias');
        });
    }

    public function down(): void
    {
        Schema::table('journeys', function (Blueprint $table) {
            $table->dropForeign(['media_id_2']);
            $table->dropColumn('media_id_2');
        });
    }
};