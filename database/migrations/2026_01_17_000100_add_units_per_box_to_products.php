<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Nombre d'unités contenues dans un carton.
 *
 * Le prix enregistré reste le PRIX UNITAIRE. Quand ce champ est
 * renseigné, le site vend le produit au carton et calcule le prix
 * tout seul : si le prix unitaire change, le prix du carton suit.
 *
 * Laissé vide, le produit est vendu à l'unité, comme avant.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('units_per_box')->nullable()->after('price');
            $table->string('box_label', 40)->nullable()->after('units_per_box');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['units_per_box', 'box_label']);
        });
    }
};
