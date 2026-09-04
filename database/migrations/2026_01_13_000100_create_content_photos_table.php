<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint as B;
use Illuminate\Support\Facades\Schema;

/**
 * Une galerie de photos rattachable à n'importe quel contenu.
 *
 * Elle sert aux formations, aux services (projets clé en main)
 * et aux références. Les photos déjà enregistrées pour les
 * formations sont reprises automatiquement.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_photos', function (Blueprint $table) {
            $table->id();
            $table->morphs('photoable');
            $table->string('image');
            $table->string('caption')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });

        // Reprise des photos de formation déjà enregistrées.
        if (Schema::hasTable('training_photos')) {
            foreach (DB::table('training_photos')->get() as $photo) {
                DB::table('content_photos')->insert([
                    'photoable_type' => 'App\\Models\\Training',
                    'photoable_id' => $photo->training_id,
                    'image' => $photo->image,
                    'caption' => $photo->caption,
                    'position' => $photo->position,
                    'created_at' => $photo->created_at,
                    'updated_at' => $photo->updated_at,
                ]);
            }

            Schema::dropIfExists('training_photos');
        }
    }

    public function down(): void
    {
        Schema::create('training_photos', function (B $table) {
            $table->id();
            $table->foreignId('training_id')->constrained()->cascadeOnDelete();
            $table->string('image');
            $table->string('caption')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });

        foreach (DB::table('content_photos')->where('photoable_type', 'App\\Models\\Training')->get() as $photo) {
            DB::table('training_photos')->insert([
                'training_id' => $photo->photoable_id,
                'image' => $photo->image,
                'caption' => $photo->caption,
                'position' => $photo->position,
                'created_at' => $photo->created_at,
                'updated_at' => $photo->updated_at,
            ]);
        }

        Schema::dropIfExists('content_photos');
    }
};
