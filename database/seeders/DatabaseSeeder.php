<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SettingsSeeder::class,
            AdminUserSeeder::class,
            CompanyValueSeeder::class,
            ServiceSeeder::class,
            ProcessSeeder::class,
            DomainSeeder::class,
            PartnerSeeder::class,
            MilestoneSeeder::class,
            DiseCatalogueSeeder::class,
            PostSeeder::class,
            TrainingsSeeder::class,
            ReferencesSeeder::class,
            GroupSeeder::class,
        ]);
    }
}
