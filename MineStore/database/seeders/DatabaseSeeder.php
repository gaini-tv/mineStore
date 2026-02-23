<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $uncategorized = Categorie::firstOrCreate(
            ['nom' => 'Non catégorisé'],
            ['description' => 'Produits sans catégorie spécifique']
        );

        $categories = [
            'livre',
            'jeux',
            'serveur',
            'montre',
            'figurine',
            'peluche',
            'textile',
        ];

        foreach ($categories as $nom) {
            Categorie::firstOrCreate(
                ['nom' => $nom],
                ['description' => 'Catégorie ' . $nom]
            );
        }

        $this->call(UserSeeder::class);
        $this->call(ProduitSeeder::class);
        $this->call(EntrepriseSeeder::class);
        $this->call(BannedWordSeeder::class);

        $produitsSeeded = Produit::whereIn('nom', [
            'Figurine Pop Minecraft',
            'Pop Minecraft Chat',
            'Pop Minecraft Femme',
            'Minecraft Java & Bedrock',
            'Minecraft Dungeons',
            'Minecraft Legends',
            'Pop Minecraft Creeper',
            'Pop Minecraft Squelette',
            'Pop Chat Jaune',
        ])->get();

        foreach ($produitsSeeded as $produit) {
            if (!$produit->categories()->where('categories.id_categorie', $uncategorized->id_categorie)->exists()) {
                $produit->categories()->attach($uncategorized->id_categorie);
            }
        }

        $figurine = Categorie::whereRaw('LOWER(nom) = ?', [mb_strtolower('figurine')])->first();
        $jeux = Categorie::whereRaw('LOWER(nom) = ?', [mb_strtolower('jeux')])->first();

        $popProducts = $produitsSeeded->whereIn('nom', [
            'Figurine Pop Minecraft',
            'Pop Minecraft Chat',
            'Pop Minecraft Femme',
            'Pop Minecraft Creeper',
            'Pop Minecraft Squelette',
            'Pop Chat Jaune',
        ]);

        $gameProducts = $produitsSeeded->whereIn('nom', [
            'Minecraft Java & Bedrock',
            'Minecraft Dungeons',
            'Minecraft Legends',
        ]);

        if ($figurine) {
            foreach ($popProducts as $p) {
                if (!$p->categories()->where('categories.id_categorie', $figurine->id_categorie)->exists()) {
                    $p->categories()->attach($figurine->id_categorie);
                }
                if ($uncategorized && $p->categories()->where('categories.id_categorie', $uncategorized->id_categorie)->exists()) {
                    $p->categories()->detach($uncategorized->id_categorie);
                }
            }
        }

        if ($jeux) {
            foreach ($gameProducts as $p) {
                if (!$p->categories()->where('categories.id_categorie', $jeux->id_categorie)->exists()) {
                    $p->categories()->attach($jeux->id_categorie);
                }
                if ($uncategorized && $p->categories()->where('categories.id_categorie', $uncategorized->id_categorie)->exists()) {
                    $p->categories()->detach($uncategorized->id_categorie);
                }
            }
        }
    }
}
