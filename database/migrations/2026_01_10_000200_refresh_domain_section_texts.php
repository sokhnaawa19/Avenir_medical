<?php

use App\Services\SettingsRepository;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Les textes de la section « Nos domaines » ont été retravaillés.
 *
 * Cette migration ne remplace la valeur enregistrée QUE si elle correspond
 * encore à l'ancien texte par défaut : un texte personnalisé par
 * l'administrateur n'est jamais écrasé.
 */
return new class extends Migration
{
    /** @var array<string, array{0: string, 1: string}> ancienne valeur => nouvelle valeur */
    private array $textes = [
        'domains_title' => [
            'Du bloc opératoire au laboratoire',
            'Une solution pour chaque environnement de soins',
        ],
        'domains_text' => [
            "12 gammes d'équipements et de consommables.",
            "Du bloc opératoire au laboratoire, de l'imagerie à la réanimation : nous réunissons les équipements et l'expertise nécessaires à chaque service de votre établissement.",
        ],
    ];

    public function up(): void
    {
        foreach ($this->textes as $cle => [$ancien, $nouveau]) {
            DB::table('settings')
                ->where('key', $cle)
                ->where(fn ($query) => $query->where('value', $ancien)->orWhereNull('value')->orWhere('value', ''))
                ->update(['value' => $nouveau, 'updated_at' => now()]);
        }

        // Le nouveau petit texte, s'il n'existe pas encore.
        DB::table('settings')->updateOrInsert(
            ['key' => 'domains_eyebrow'],
            ['value' => "Nos domaines d'expertise", 'group' => 'sections', 'type' => 'text', 'updated_at' => now(), 'created_at' => now()]
        );

        app(SettingsRepository::class)->flush();
    }

    public function down(): void
    {
        foreach ($this->textes as $cle => [$ancien, $nouveau]) {
            DB::table('settings')->where('key', $cle)->where('value', $nouveau)->update(['value' => $ancien]);
        }

        app(SettingsRepository::class)->flush();
    }
};
