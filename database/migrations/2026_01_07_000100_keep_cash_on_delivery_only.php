<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Ne garde que le paiement à la livraison.
 *
 * Les autres moyens (Wave, Orange Money, virement) n'étaient pas encaissés :
 * les afficher au moment de commander créait une attente que la commande ne
 * pouvait pas tenir. Ils restent réactivables depuis
 * Administration → Réglages → Boutique.
 */
return new class extends Migration
{
    private const VALUE = 'Paiement à la livraison';

    public function up(): void
    {
        $setting = DB::table('settings')->where('key', 'payment_methods')->first();

        if ($setting === null) {
            DB::table('settings')->insert([
                'key' => 'payment_methods',
                'value' => self::VALUE,
                'group' => 'shop',
                'type' => 'textarea',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return;
        }

        DB::table('settings')
            ->where('key', 'payment_methods')
            ->update(['value' => self::VALUE, 'updated_at' => now()]);
    }

    public function down(): void
    {
        // Rien à défaire : le réglage se modifie depuis l'administration.
    }
};
