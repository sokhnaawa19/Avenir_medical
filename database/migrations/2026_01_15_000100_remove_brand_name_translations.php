<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Supprime les traductions du nom de l'entreprise et des coordonnées.
 *
 * Si une traduction automatique a transformé « AVENIR MEDICAL » en
 * « MEDICAL FUTURE », cette migration la retire : le nom d'origine
 * réapparaît dans toutes les langues.
 */
return new class extends Migration
{
    private array $cles = [
        'site_name', 'site_baseline', 'address',
        'phone_1', 'phone_2', 'phone_3',
        'whatsapp_1_label', 'whatsapp_2_label', 'whatsapp_3_label',
    ];

    public function up(): void
    {
        $identifiants = Setting::query()->whereIn('key', $this->cles)->pluck('id');

        if ($identifiants->isNotEmpty()) {
            DB::table('translations')
                ->where('translatable_type', Setting::class)
                ->whereIn('translatable_id', $identifiants)
                ->delete();
        }

        // Une traduction qui reprend mot pour mot un nom de marque
        // n'a pas de raison d'exister : on la retire aussi.
        foreach (config('services.translate.protected', []) as $marque) {
            DB::table('translations')
                ->whereRaw('LOWER(TRIM(value)) = ?', [mb_strtolower($marque)])
                ->delete();
        }

        settings()->flush();
    }

    public function down(): void
    {
        // Rien à défaire : ces traductions ne devaient pas exister.
    }
};
