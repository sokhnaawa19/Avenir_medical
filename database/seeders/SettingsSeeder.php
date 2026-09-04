<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Services\SettingsRepository;
use Illuminate\Database\Seeder;

/**
 * Enregistre les valeurs par defaut de tous les reglages
 * decrits dans config/settings.php.
 */
class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('settings', []) as $group => $definition) {
            foreach ($definition['fields'] ?? [] as $key => $field) {
                Setting::query()->firstOrCreate(
                    ['key' => $key],
                    [
                        'value' => $field['default'] ?? null,
                        'group' => $group,
                        'type' => $field['type'] ?? 'text',
                    ]
                );
            }
        }

        app(SettingsRepository::class)->flush();
    }
}
