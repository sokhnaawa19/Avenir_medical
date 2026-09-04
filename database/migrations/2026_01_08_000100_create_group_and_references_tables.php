<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // --- Partenariats exclusifs ---
        Schema::table('partners', function (Blueprint $table) {
            $table->boolean('is_exclusive')->default(false)->after('is_featured');
            $table->string('exclusivity_scope')->nullable()->after('is_exclusive');
        });

        // --- Établissements équipés (nos références) ---
        // Le nom « references » est réservé en SQL : on utilise « establishments ».
        Schema::create('establishments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type', 80)->nullable();          // Hôpital, Clinique, Centre de santé…
            $table->string('city', 120)->nullable();
            $table->string('country', 80)->default('Sénégal');
            $table->string('logo')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->text('equipments')->nullable();          // Ce qui a été installé
            $table->string('year', 20)->nullable();
            $table->boolean('is_flagship')->default(false);  // Équipement complet, réalisation phare
            $table->boolean('is_featured')->default(true);   // Visible sur l'accueil
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // --- Formations suivies par les techniciens ---
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('organism')->nullable();          // Fabricant ou institut formateur
            $table->string('country', 80)->nullable();
            $table->string('city', 120)->nullable();
            $table->string('year', 20)->nullable();
            $table->unsignedInteger('participants')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // --- Galerie photos (événements, chantiers, salons…) ---
        Schema::create('gallery_photos', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('album', 120)->nullable()->index(); // Événements, Installations, Formations…
            $table->string('image');
            $table->text('caption')->nullable();
            $table->string('taken_at', 40)->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // --- Agences : implantation actuelle et à venir ---
        Schema::create('agencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('region', 120)->nullable();
            $table->string('country', 80)->default('Sénégal');
            $table->string('address')->nullable();
            $table->string('phone', 60)->nullable();
            $table->string('status', 40)->default('projet');  // ouverte | en cours | projet
            $table->string('opening_year', 20)->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // --- Entreprises du groupe (filiales) ---
        Schema::create('subsidiaries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->string('activity')->nullable();
            $table->string('logo')->nullable();
            $table->string('image')->nullable();
            $table->string('website')->nullable();
            $table->string('color', 20)->nullable();
            $table->string('founded_year', 20)->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subsidiaries');
        Schema::dropIfExists('agencies');
        Schema::dropIfExists('gallery_photos');
        Schema::dropIfExists('trainings');
        Schema::dropIfExists('establishments');

        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn(['is_exclusive', 'exclusivity_scope']);
        });
    }
};
