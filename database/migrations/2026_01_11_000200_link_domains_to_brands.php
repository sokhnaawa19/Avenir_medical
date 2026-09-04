<?php

use App\Models\Domain;
use App\Models\Partner;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Associe chaque domaine phare à sa marque exclusive :
 *
 *   Bloc & néonatalogie -> COMEN
 *   Stérilisation       -> SHINVA
 *   Imagerie            -> CANON
 *   Laboratoire         -> RANDOX
 *
 * Met aussi à jour le texte de la section, qui ne parlait que
 * de deux domaines sur douze.
 */
return new class extends Migration
{
    /** @var array<string, array<int, string>> marque => mots-clés du domaine */
    private array $associations = [
        'COMEN' => ['bloc', 'néonat', 'neonat', 'réanimation', 'reanimation'],
        'SHINVA' => ['stérilis', 'steril'],
        'CANON' => ['imagerie'],
        'RANDOX' => ['laboratoire', 'analyses'],
    ];

    public function up(): void
    {
        foreach ($this->associations as $marque => $motsCles) {
            $partenaire = Partner::query()
                ->whereRaw('UPPER(name) LIKE ?', ['%'.$marque.'%'])
                ->first();

            if (! $partenaire) {
                continue;
            }

            $domaines = Domain::query()
                ->where(function ($query) use ($motsCles) {
                    foreach ($motsCles as $mot) {
                        $query->orWhereRaw('LOWER(title) LIKE ?', ['%'.$mot.'%']);
                    }
                })
                ->get();

            foreach ($domaines as $domaine) {
                // syncWithoutDetaching : on n'efface pas les marques déjà associées.
                $domaine->partners()->syncWithoutDetaching([$partenaire->id => ['position' => 0]]);
            }
        }

        // Le titre de la section ne citait que deux domaines sur douze.
        $ancien = 'Du bloc opératoire au laboratoire';

        DB::table('settings')->where('key', 'domains_title')->where('value', $ancien)
            ->update([
                'value' => 'Une expertise complète, du bloc au laboratoire',
                'updated_at' => now(),
            ]);

        settings()->flush();
    }

    public function down(): void
    {
        // Rien à défaire : les associations restent valables.
    }
};
