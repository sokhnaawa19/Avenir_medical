<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Relie une marque à un domaine d'intervention.
 *
 * Une marque équipe souvent plusieurs domaines (Comen fournit le bloc et le
 * monitorage) et un domaine réunit plusieurs marques (Canon et Edan en
 * imagerie). La colonne « ranges » retient, pour ce couple précis, les gammes
 * fournies — une ligne par gamme, au format « Nom | précision ».
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('domain_partner', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domain_id')->constrained()->cascadeOnDelete();
            $table->foreignId('partner_id')->constrained()->cascadeOnDelete();
            $table->text('ranges')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->unique(['domain_id', 'partner_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domain_partner');
    }
};
