<?php

namespace Database\Seeders;

use App\Models\Produit;
use Illuminate\Database\Seeder;

class ProduitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vérifier si le produit existe déjà
        $produitExistant = Produit::where('nom', 'Figurine Pop Minecraft')->first();
        
        if (!$produitExistant) {
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
            
            $this->command->info('Produit "Figurine Pop Minecraft" ajouté avec succès !');
        } else {
            $this->command->info('Le produit "Figurine Pop Minecraft" existe déjà.');
        }
    }
}
