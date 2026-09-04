<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Establishment;
use App\Models\Partner;
use App\Models\Subsidiary;
use App\Models\Training;
use Illuminate\Database\Seeder;

/**
 * Exemples de contenu pour les nouvelles rubriques :
 * références, formations, agences et entreprises du groupe.
 * À remplacer par les vraies informations depuis l'administration.
 */
class GroupSeeder extends Seeder
{
    public function run(): void
    {
        // --- Deux partenariats rendus exclusifs, à titre d'exemple ---
        Partner::query()->orderBy('position')->take(2)->get()
            ->each(fn (Partner $partner) => $partner->update([
                'is_exclusive' => true,
                'exclusivity_scope' => "Afrique de l'Ouest",
            ]));

        // --- Établissements équipés ---
        $etablissements = [
            ['Clinique de la Madeleine', 'Clinique privée', 'Dakar', '2024', true,
             "Équipement complet du plateau technique : bloc opératoire, réanimation, imagerie et laboratoire.",
             "Bloc opératoire complet\nUnité de réanimation\nImagerie numérique\nLaboratoire d'analyses\nCentrale à oxygène"],
            ['Hôpital régional de Thiès', 'Hôpital public', 'Thiès', '2023', true,
             "Modernisation du service d'imagerie et installation d'une centrale à oxygène.",
             "Imagerie médicale\nCentrale à oxygène\nMobilier hospitalier"],
            ['Centre de santé de Mbour', 'Centre de santé', 'Mbour', '2025', false, null, null],
            ['Clinique du Cap', 'Clinique privée', 'Dakar', '2022', false, null, null],
            ['Polyclinique de Saint-Louis', 'Clinique privée', 'Saint-Louis', '2024', false, null, null],
            ['Cabinet Vision Plus', 'Cabinet d’ophtalmologie', 'Dakar', '2025', false, null, null],
        ];

        foreach ($etablissements as $position => [$nom, $type, $ville, $annee, $phare, $description, $equipements]) {
            Establishment::query()->firstOrCreate(
                ['name' => $nom],
                [
                    'type' => $type,
                    'city' => $ville,
                    'country' => 'Sénégal',
                    'year' => $annee,
                    'description' => $description,
                    'equipments' => $equipements,
                    'is_flagship' => $phare,
                    'is_featured' => true,
                    'is_active' => true,
                    'position' => $position,
                ]
            );
        }

        // --- Formations des techniciens ---
        $formations = [
            ['Maintenance des échographes — niveau avancé', 'Mindray Academy', 'Chine', 'Shenzhen', '2025', 3,
             "Deux semaines de formation chez le fabricant : diagnostic, réparation et calibration des échographes."],
            ['Respirateurs et anesthésie', 'Dräger Training Center', 'Allemagne', 'Lübeck', '2024', 2,
             "Formation certifiante sur la maintenance des respirateurs et des postes d'anesthésie."],
            ['Imagerie numérique et radioprotection', 'Institut de formation biomédicale', 'France', 'Paris', '2024', 2,
             "Perfectionnement sur les équipements de radiologie numérique et les règles de radioprotection."],
        ];

        foreach ($formations as $position => [$titre, $organisme, $pays, $ville, $annee, $participants, $description]) {
            Training::query()->firstOrCreate(
                ['title' => $titre],
                [
                    'organism' => $organisme,
                    'country' => $pays,
                    'city' => $ville,
                    'year' => $annee,
                    'participants' => $participants,
                    'description' => $description,
                    'is_active' => true,
                    'position' => $position,
                ]
            );
        }

        // --- Agences : existantes et projets ---
        $agences = [
            ['Siège de Dakar', 'Dakar', 'Sénégal', Agency::STATUT_OUVERTE, '2015',
             "Notre siège : showroom, atelier technique et magasin de pièces détachées."],
            ['Agence de Thiès', 'Thiès', 'Sénégal', Agency::STATUT_EN_COURS, '2026',
             "Pour intervenir plus rapidement dans le centre du pays."],
            ['Agence de Saint-Louis', 'Saint-Louis', 'Sénégal', Agency::STATUT_PROJET, '2027',
             "Desservir le nord du Sénégal et la vallée du fleuve."],
            ['Agence de Ziguinchor', 'Ziguinchor', 'Sénégal', Agency::STATUT_PROJET, '2028',
             "Rapprocher nos techniciens des structures de santé de la Casamance."],
            ['Bamako', null, 'Mali', Agency::STATUT_PROJET, '2028',
             "Première implantation hors du Sénégal."],
            ['Abidjan', null, "Côte d'Ivoire", Agency::STATUT_PROJET, '2029',
             "Ouverture vers le marché ivoirien."],
        ];

        foreach ($agences as $position => [$nom, $region, $pays, $statut, $annee, $description]) {
            Agency::query()->firstOrCreate(
                ['name' => $nom],
                [
                    'region' => $region,
                    'country' => $pays,
                    'status' => $statut,
                    'opening_year' => $annee,
                    'description' => $description,
                    'is_active' => true,
                    'position' => $position,
                ]
            );
        }

        // --- Entreprises du groupe ---
        $entreprises = [
            ['AVENIR MEDICAL CONSULTING', 'Étude et financement de projets de santé', 'Conseil', '2023', '#16657F',
             "Accompagnement des porteurs de projets : dimensionnement du plateau technique, chiffrage et solutions de financement."],
            ['AVENIR PHARMA', 'Consommables et dispositifs médicaux', 'Distribution', '2024', '#2FA8C4',
             "Distribution de consommables médicaux et de dispositifs à usage unique auprès des structures de santé."],
            ['AVENIR TECH SERVICES', 'Maintenance biomédicale', 'Services techniques', '2025', '#0C3B4C',
             "Contrats de maintenance préventive et curative, y compris sur du matériel non fourni par le groupe."],
        ];

        foreach ($entreprises as $position => [$nom, $signature, $activite, $annee, $couleur, $description]) {
            Subsidiary::query()->firstOrCreate(
                ['name' => $nom],
                [
                    'tagline' => $signature,
                    'activity' => $activite,
                    'founded_year' => $annee,
                    'color' => $couleur,
                    'description' => $description,
                    'is_active' => true,
                    'position' => $position,
                ]
            );
        }
    }
}
