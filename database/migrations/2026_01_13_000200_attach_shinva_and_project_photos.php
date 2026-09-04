<?php

use App\Models\Domain;
use App\Models\Partner;
use App\Models\GalleryPhoto;
use App\Models\Service;
use Illuminate\Database\Migrations\Migration;

/**
 * - SHINVA est la marque mise en avant pour la stérilisation,
 *   qui fait partie du domaine « Hygiène et entretien ».
 * - Les photos du projet clé en main rejoignent le service
 *   correspondant.
 */
return new class extends Migration
{
    /** Les photos du chantier, dans un ordre qui raconte le projet. */
    private array $photos = [
        ['projets/cck-respirateurs.webp', 'Respirateurs et défibrillateurs installés'],
        ['projets/cck-equipe-sav.webp', 'Notre équipe technique lors de la mise en service'],
        ['projets/cck-formation-fabricant.webp', 'Formation assurée avec les ingénieurs du fabricant'],
        ['projets/cck-demonstration.webp', 'Démonstration des équipements aux équipes soignantes'],
        ['projets/cck-equipe-soignante.webp', 'Prise en main par le personnel de l’établissement'],
        ['projets/cck-pompe-perfusion.webp', 'Pompe à perfusion COMEN ME600'],
        ['projets/cck-reglage-pousse-seringue.webp', 'Réglage des paramètres de perfusion'],
        ['projets/cck-mise-en-service.webp', 'Vérification avant mise en service'],
        ['projets/cck-defibrillateur.webp', 'Défibrillateur COMEN prêt à l’emploi'],
    ];

    public function up(): void
    {
        // --- SHINVA sur le domaine Hygiène et entretien (stérilisation) ---
        $shinva = Partner::query()->whereRaw('UPPER(name) LIKE ?', ['%SHINVA%'])->first();

        if ($shinva) {
            Domain::query()
                ->where(function ($query) {
                    $query->whereRaw('LOWER(title) LIKE ?', ['%hygiène%'])
                        ->orWhereRaw('LOWER(title) LIKE ?', ['%hygiene%'])
                        ->orWhereRaw('LOWER(title) LIKE ?', ['%stérilis%']);
                })
                ->get()
                ->each(fn (Domain $domaine) => $domaine->partners()->syncWithoutDetaching([
                    $shinva->id => ['position' => 0],
                ]));
        }

        // --- Photos du projet clé en main ---
        $service = Service::query()
            ->where(function ($query) {
                $query->whereRaw('LOWER(title) LIKE ?', ['%clé en main%'])
                    ->orWhereRaw('LOWER(title) LIKE ?', ['%cle en main%']);
            })
            ->first();

        if ($service && $service->photos()->count() === 0) {
            foreach ($this->photos as $position => [$chemin, $legende]) {
                $service->photos()->create([
                    'image' => $chemin,
                    'caption' => $legende,
                    'position' => $position,
                ]);
            }

            return;
        }

        if ($service) {
            return;
        }

        // Aucun service « clé en main » : les photos rejoignent la galerie,
        // dans leur propre album, pour ne pas être perdues.
        foreach ($this->photos as $position => [$chemin, $legende]) {
            GalleryPhoto::query()->firstOrCreate(
                ['image' => $chemin],
                [
                    'title' => $legende,
                    'album' => 'Projets clé en main',
                    'position' => $position,
                    'is_active' => true,
                ]
            );
        }
    }

    public function down(): void
    {
        Service::query()
            ->whereRaw('LOWER(title) LIKE ?', ['%clé en main%'])
            ->first()?->photos()->delete();
    }
};
