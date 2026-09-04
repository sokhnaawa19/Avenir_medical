<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Une seule table pour toutes les traductions du contenu.
 *
 * Plutôt que d'ajouter une colonne « _en » à chaque table
 * (produits, domaines, articles…), on stocke ici la traduction
 * de n'importe quel champ de n'importe quel contenu.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->morphs('translatable');            // le contenu concerné
            $table->string('locale', 5)->index();      // fr, en…
            $table->string('field', 60);               // name, description…
            $table->text('value')->nullable();
            $table->boolean('is_automatic')->default(true); // traduit par machine ?
            $table->timestamps();

            $table->unique(['translatable_type', 'translatable_id', 'locale', 'field'], 'traduction_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
