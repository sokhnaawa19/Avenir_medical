<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Les logos et les couleurs ne sont plus modifiables depuis l'administration :
 * on retire les anciennes valeurs enregistrées pour ne rien laisser traîner.
 */
return new class extends Migration
{
    /** @var array<int, string> */
    private array $keys = [
        'logo',
        'logo_light',
        'favicon',
        'color_primary',
        'color_dark',
        'color_light',
    ];

    public function up(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        DB::table('settings')->whereIn('key', $this->keys)->delete();
    }

    public function down(): void
    {
        // Rien à faire : ces réglages seront recréés par le seeder si besoin.
    }
};
