<?php

namespace Database\Seeders;

use App\Models\Domain;
use App\Models\Partner;
use Illuminate\Database\Seeder;

/**
 * Les marques représentées par Avenir Médical, et le domaine qu'elles équipent.
 *
 * Ce seeder ne remplace jamais un contenu déjà saisi dans l'administration :
 * il ne remplit que les champs restés vides et n'ajoute que les liens
 * marque ↔ domaine manquants. Il peut donc être relancé sans risque.
 *
 * Le contenu est rangé dans database/seeders/data/brands.php.
 */
class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        /** @var array<int, array<string, mixed>> $brands */
        $brands = require database_path('seeders/data/brands.php');

        // Les domaines, retrouvés une seule fois par leur titre.
        $domains = Domain::query()->get()->keyBy('title');

        foreach ($brands as $position => $brand) {
            $partner = Partner::query()->firstOrCreate(
                ['name' => $brand['name']],
                [
                    'position' => $position,
                    'is_featured' => (bool) ($brand['is_featured'] ?? false),
                    'is_active' => true,
                ]
            );

            // On ne complète que ce qui est vide, pour ne rien écraser.
            foreach (['country', 'website', 'description'] as $field) {
                if (blank($partner->getAttribute($field)) && filled($brand[$field] ?? null)) {
                    $partner->setAttribute($field, $brand[$field]);
                }
            }

            $partner->save();

            $this->attachDomains($partner, $domains, $brand['domains'] ?? []);
        }
    }

    /**
     * Rattache la marque à ses domaines, sans toucher aux liens déjà en place.
     *
     * @param  \Illuminate\Support\Collection<string, Domain>  $domains
     * @param  array<string, string>  $wanted
     */
    private function attachDomains(Partner $partner, $domains, array $wanted): void
    {
        $existing = $partner->domains()->pluck('domains.id')->all();
        $position = count($existing);

        foreach ($wanted as $title => $ranges) {
            $domain = $domains->get($title);

            if ($domain === null) {
                $this->command?->warn('Domaine introuvable : '.$title.' (marque '.$partner->name.')');

                continue;
            }

            if (in_array($domain->id, $existing, true)) {
                continue;
            }

            $partner->domains()->attach($domain->id, [
                'ranges' => trim((string) $ranges),
                'position' => $position++,
            ]);
        }
    }
}
