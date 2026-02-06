<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categorie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Categories
        $categories = [
            'livre',
            'jeux',
            'serveur',
            'montre',
            'figurine',
            'peluche',
            'textile'
        ];

        foreach ($categories as $nom) {
            Categorie::create([
                'nom' => $nom,
                'description' => 'Catégorie ' . $nom
            ]);
        }

        // 2. Create 10 random users (without specific admin)
        // User::factory(10)->create();
    }
}
