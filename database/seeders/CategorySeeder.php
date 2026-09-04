<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Petits matériels',
            'Consommables',
            'Mobiliers hospitaliers',
            'Laboratoire',
            'Oxygène & respiratoire',
            'Réfrigérateurs biomédicaux',
        ];

        foreach ($categories as $position => $name) {
            Category::query()->firstOrCreate(
                ['name' => $name],
                ['position' => $position, 'is_active' => true]
            );
        }
    }
}
