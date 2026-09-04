<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ajoute au domaine ce qui manquait pour que sa carte soit parlante :
 * une icône, une accroche, et la liste des équipements affichée en étiquettes.
 *
 * Les équipements sont stockés en « text » (et non en « json ») pour rester
 * compatibles avec les anciennes versions de MySQL / MariaDB.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->string('icon', 16)->nullable()->after('subtitle');
            $table->text('intro')->nullable()->after('icon');
            $table->text('equipments')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->dropColumn(['icon', 'intro', 'equipments']);
        });
    }
};
