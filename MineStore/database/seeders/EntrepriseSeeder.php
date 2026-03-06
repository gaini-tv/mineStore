<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Entreprise;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntrepriseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $owner = User::where('email', 'clementvolle@gmail.com')->first();

        if (!$owner) {
            return;
        }

        $entreprise = Entreprise::firstOrCreate(
            ['nom' => 'Testicraft'],
            [
                'description' => 'Boutique officielle Testicraft, spécialisée dans les produits Minecraft.',
                'email_contact' => $owner->email,
                'telephone' => '0102030405',
                'adresse' => 'Village Testicraft, Monde Minecraft',
                'user_id' => $owner->id,
                'statut' => 'active',
            ]
        );

        $owner->role = 'owner';
        $owner->entreprise_id = $entreprise->id_entreprise;
        $owner->save();

        $roles = [
            'minestore-Manager@gmail.com' => 'manager',
            'minestore-product@gmail.com' => 'product_manager',
            'minestore-stock@gmail.com' => 'stock_manager',
            'minestore-editor@gmail.com' => 'editor',
        ];

        foreach ($roles as $email => $role) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->role = $role;
                $user->entreprise_id = $entreprise->id_entreprise;
                $user->save();
            }
        }

        $categorieLivre = Categorie::whereRaw('LOWER(nom) = ?', [mb_strtolower('livre')])->first();

        $produits = [
            [
                'reference' => 'BOOK-GUIDE-COMB',
                'nom' => 'Livre Guide Combat',
                'description' => 'Guide complet des techniques de combat Minecraft, des armes aux enchantements.',
                'prix' => 12.99,
                'image' => 'images/produits/defaultdev/livreGuideCombat.webp',
                'stock' => 100,
                'stock_low_threshold' => 20,
                'infinite_stock' => false,
                'rupture_marketing' => false,
                'pegi' => 'images/pegi7.png',
            ],
            [
                'reference' => 'BOOK-GUIDE-EXPL',
                'nom' => 'Livre Guide Exploration',
                'description' => 'Guide de survie et d’exploration pour découvrir chaque biome de Minecraft.',
                'prix' => 12.99,
                'image' => 'images/produits/defaultdev/livreGuideExploration.webp',
                'stock' => 100,
                'stock_low_threshold' => 20,
                'infinite_stock' => false,
                'rupture_marketing' => false,
                'pegi' => 'images/pegi7.png',
            ],
            [
                'reference' => 'BOOK-GUIDE-RED',
                'nom' => 'Livre Guide Redstone',
                'description' => 'Guide avancé pour maîtriser la redstone et créer des machines complexes.',
                'prix' => 14.99,
                'image' => 'images/produits/defaultdev/livreGuideRedstone.webp',
                'stock' => 80,
                'stock_low_threshold' => 15,
                'infinite_stock' => false,
                'rupture_marketing' => false,
                'pegi' => 'images/pegi7.png',
            ],
        ];

        foreach ($produits as $data) {
            $produit = Produit::updateOrCreate(
                ['reference' => $data['reference']],
                [
                    'nom' => $data['nom'],
                    'description' => $data['description'],
                    'prix' => $data['prix'],
                    'image' => $data['image'],
                    'stock' => $data['stock'],
                    'stock_low_threshold' => $data['stock_low_threshold'],
                    'infinite_stock' => $data['infinite_stock'],
                    'rupture_marketing' => $data['rupture_marketing'],
                    'reference' => $data['reference'],
                    'actif' => true,
                    'date_creation' => now(),
                    'pegi' => $data['pegi'],
                    'entreprise_id' => $entreprise->id_entreprise,
                ]
            );

            if ($categorieLivre) {
                if (!$produit->categories()->where('categories.id_categorie', $categorieLivre->id_categorie)->exists()) {
                    $produit->categories()->attach($categorieLivre->id_categorie);
                }
            }
        }
    }
}

