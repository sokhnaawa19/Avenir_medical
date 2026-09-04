<?php

use App\Models\Partner;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Mise à jour de la liste des marques partenaires.
 *
 * - Retrait d'ENDOMED et SUMER
 * - Ajout de SUNBIO
 * - COMEN, SHINVA, CANON et RANDOX passent en partenariat exclusif,
 *   dans cet ordre, et sont mis en avant sur la page d'accueil.
 */
return new class extends Migration
{
    private array $exclusifs = ['COMEN', 'SHINVA', 'CANON', 'RANDOX'];

    private array $retires = ['ENDOMED', 'SUMER'];

    public function up(): void
    {
        // --- Marques retirées ---
        foreach ($this->retires as $nom) {
            Partner::query()
                ->whereRaw('UPPER(name) LIKE ?', ['%'.$nom.'%'])
                ->get()
                ->each(fn (Partner $p) => $p->delete());
        }

        // --- Nouvelle marque ---
        Partner::query()->firstOrCreate(
            ['slug' => 'sunbio'],
            ['name' => 'SUNBIO', 'is_active' => true, 'is_featured' => true, 'position' => 50]
        );

        // --- Les quatre partenariats exclusifs, dans l'ordre demandé ---
        foreach ($this->exclusifs as $position => $nom) {
            $partenaire = Partner::query()
                ->whereRaw('UPPER(name) LIKE ?', ['%'.$nom.'%'])
                ->first();

            $partenaire?->update([
                'is_exclusive' => true,
                'exclusivity_scope' => $partenaire->exclusivity_scope ?: "Afrique de l'Ouest",
                'is_featured' => true,
                'position' => $position,
            ]);
        }

        // Les autres marques passent après les exclusives.
        Partner::query()
            ->where('is_exclusive', false)
            ->where('position', '<', 10)
            ->update(['position' => 10]);

        // --- Le chiffre « 8 pôles » ne correspond à aucune réalité ---
        foreach ([1, 2, 3, 4] as $i) {
            $libelle = DB::table('settings')->where('key', 'stat_'.$i.'_label')->value('value');

            if (is_string($libelle) && preg_match('/p[oô]le/i', $libelle)) {
                DB::table('settings')->whereIn('key', ['stat_'.$i.'_value', 'stat_'.$i.'_label'])
                    ->update(['value' => '', 'updated_at' => now()]);
            }
        }

        // --- Le nombre de marques annoncé suit la réalité ---
        $nombre = Partner::query()->where('is_active', true)->count();

        foreach ([1, 2, 3, 4] as $i) {
            $libelle = DB::table('settings')->where('key', 'stat_'.$i.'_label')->value('value');

            if (is_string($libelle) && preg_match('/marques? partenaires?/i', $libelle)) {
                DB::table('settings')->where('key', 'stat_'.$i.'_value')
                    ->update(['value' => '+ de '.$nombre, 'updated_at' => now()]);
            }
        }

        settings()->flush();
    }

    public function down(): void
    {
        Partner::query()->whereIn('name', $this->exclusifs)->update(['is_exclusive' => false]);
    }
};
