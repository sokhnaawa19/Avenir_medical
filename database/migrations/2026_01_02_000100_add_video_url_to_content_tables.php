<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('video_url')->nullable()->after('image');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('video_url')->nullable()->after('image');
        });

        Schema::table('domains', function (Blueprint $table) {
            $table->string('video_url')->nullable()->after('image');
        });
    }

    public function down(): void
    {
        Schema::table('domains', fn (Blueprint $table) => $table->dropColumn('video_url'));
        Schema::table('products', fn (Blueprint $table) => $table->dropColumn('video_url'));
        Schema::table('posts', fn (Blueprint $table) => $table->dropColumn('video_url'));
    }
};
