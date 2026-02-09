<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categorie;
use App\Models\Produit;
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

        // 3. Create sample products
        Produit::create([
            'nom' => 'Figurine Pop Minecraft',
            'description' => 'Figurine Pop Funko de Minecraft, collection officielle. Parfaite pour les fans de Minecraft !',
            'prix' => 14.99,
            'image' => 'images/produits/pop-1.png',
            'stock' => 50,
            'reference' => 'POP-MC-001',
            'actif' => true,
            'date_creation' => now(),
        ]);
    }
}
