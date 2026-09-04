<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['email' => 'admin@avenir-medic.com'],
            [
                'name' => 'Administrateur AVENIR MEDICAL',
                'password' => 'AvenirMedical2026',
                'phone' => '+221 33 827 20 36',
                'city' => 'Dakar',
                'is_admin' => true,
            ]
        );
    }
}
