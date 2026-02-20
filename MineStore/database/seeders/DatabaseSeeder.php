<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Support\Facades\Hash;
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

        // 2. Create base users
        $users = [
            [
                'prenom' => 'Adminis',
                'nom' => 'trateur',
                'email' => 'minestore-Admin@gmail.com',
                'role' => 'admin',
                'avatar' => 'Plan de travail 1 copie 6-2.png',
                'date_naissance' => '2001-01-01',
            ],
            [
                'prenom' => 'Util',
                'nom' => 'isateur',
                'email' => 'minestore-User@gmail.com',
                'role' => 'user',
                'avatar' => 'base.png',
                'date_naissance' => '2004-01-01',
            ],
            [
                'prenom' => 'Propri',
                'nom' => 'étaire',
                'email' => 'clementvolle@gmail.com',
                'role' => 'user',
                'avatar' => 'Plan de travail 1 copie 5.png',
                'date_naissance' => '2005-01-01',
            ],
            [
                'prenom' => 'Dire',
                'nom' => 'ecteur',
                'email' => 'minestore-Manager@gmail.com',
                'role' => 'user',
                'avatar' => 'Plan de travail 1 copie 2.png',
                'date_naissance' => '2006-01-01',
            ],
            [
                'prenom' => 'Responsable',
                'nom' => 'Produit',
                'email' => 'minestore-product@gmail.com',
                'role' => 'user',
                'avatar' => 'Plan de travail 1 copie 3.png',
                'date_naissance' => '2007-01-01',
            ],
            [
                'prenom' => 'Responsable',
                'nom' => 'Stock',
                'email' => 'minestore-stock@gmail.com',
                'role' => 'user',
                'avatar' => 'Plan de travail 1 copie 4.png',
                'date_naissance' => '2008-01-01',
            ],
            [
                'prenom' => 'Edi',
                'nom' => 'teur',
                'email' => 'minestore-editor@gmail.com',
                'role' => 'user',
                'avatar' => 'Plan de travail 1 copie 7.png',
                'date_naissance' => '2009-01-01',
            ],
        ];

        foreach ($users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'prenom' => $data['prenom'],
                    'nom' => $data['nom'],
                    'role' => $data['role'],
                    'password' => Hash::make('Minecraft'),
                    'statut' => 'actif',
                    'date_inscription' => now(),
                    'avatar' => $data['avatar'],
                    'date_naissance' => $data['date_naissance'],
                ]
            );

            $user->forceFill([
                'email_verified_at' => now(),
            ])->save();
        }

        // 3. Create or update sample products
        $produit1 = Produit::updateOrCreate(
            ['nom' => 'Figurine Pop Minecraft'],
            [
                'description' => 'Découvrez la figurine Pop Funko Minecraft officielle, un must-have pour tous les fans du célèbre jeu de construction ! Cette figurine de collection représente fidèlement le personnage emblématique de Minecraft avec son design pixelisé caractéristique. Fabriquée avec des matériaux de qualité supérieure, cette Pop Funko mesure environ 10 cm de hauteur et arbore les couleurs emblématiques du jeu. Parfaite pour décorer votre bureau, votre étagère ou votre collection de figurines, cette Pop Funko Minecraft est un cadeau idéal pour les joueurs de tous âges. Collectionnez toutes les Pop Funko Minecraft pour créer votre propre monde Minecraft en miniature !',
                'prix' => 14.99,
                'image' => 'images/produits/pop-1.png',
                'stock' => 50,
                'reference' => 'POP-MC-001',
                'actif' => true,
                'date_creation' => now(),
            ]
        );

        $produit2 = Produit::updateOrCreate(
            ['nom' => 'Pop Minecraft Chat'],
            [
                'description' => 'Adoptez le compagnon le plus fidèle de Minecraft avec cette adorable figurine Pop Funko Minecraft Chat ! Ce petit félin pixelisé vous accompagnera dans toutes vos aventures. La figurine capture parfaitement l\'essence du chat Minecraft avec ses yeux expressifs et sa posture caractéristique. Mesurant environ 8 cm de hauteur, cette Pop Funko est fabriquée avec des matériaux de qualité et présente un fini soigné. Que vous soyez un collectionneur passionné ou simplement un fan de Minecraft, cette figurine sera un ajout parfait à votre collection. Le chat Minecraft est connu pour être un compagnon loyal qui vous suit partout - maintenant, vous pouvez l\'avoir avec vous dans le monde réel !',
                'prix' => 14.99,
                'image' => 'images/produits/pop-2.png',
                'stock' => 50,
                'reference' => 'POP-MC-002',
                'actif' => true,
                'date_creation' => now(),
            ]
        );

        $produit3 = Produit::updateOrCreate(
            ['nom' => 'Pop Minecraft Femme'],
            [
                'description' => 'Ajoutez de la diversité à votre collection avec cette magnifique figurine Pop Funko Minecraft représentant un personnage féminin ! Cette Pop Funko capture l\'esprit inclusif de Minecraft avec un design élégant et fidèle au style pixelisé du jeu. Mesurant environ 10 cm de hauteur, cette figurine est fabriquée avec des matériaux de qualité supérieure et présente un fini détaillé. Parfaite pour représenter la communauté diversifiée des joueurs Minecraft, cette Pop Funko est un excellent ajout à toute collection. Que vous soyez un joueur expérimenté ou débutant, cette figurine vous rappellera que Minecraft est un jeu pour tous. Collectionnez-la avec les autres Pop Funko Minecraft pour créer une collection complète et représentative !',
                'prix' => 14.99,
                'image' => 'images/produits/pop-3.png',
                'stock' => 50,
                'reference' => 'POP-MC-003',
                'actif' => true,
                'date_creation' => now(),
            ]
        );

        $produit4 = Produit::updateOrCreate(
            ['nom' => 'Minecraft Java & Bedrock'],
            [
                'description' => 'Plongez dans l\'univers infini de Minecraft avec cette édition complète incluant à la fois Java Edition et Bedrock Edition ! Minecraft est le jeu de construction et d\'aventure le plus vendu au monde, avec des millions de joueurs actifs chaque mois. Avec cette édition, vous obtenez les deux versions du jeu : Java Edition pour les joueurs PC avec ses mods et serveurs personnalisés, et Bedrock Edition pour jouer sur console, mobile et Windows 10/11 avec le cross-play. Explorez des mondes générés procéduralement, construisez des structures épiques, combattez des créatures, minez des ressources précieuses et créez tout ce que votre imagination peut concevoir. Rejoignez une communauté mondiale de créateurs, participez à des serveurs multijoueurs, téléchargez des mods et des textures, et vivez des aventures infinies. Minecraft Java & Bedrock Edition offre des milliers d\'heures de jeu et une expérience unique à chaque partie. Que vous préfériez la survie, le mode créatif, l\'aventure ou le multijoueur, cette édition complète a tout pour vous plaire !',
                'prix' => 29.99,
                'image' => 'images/1.png',
                'pegi' => 'images/pegi7.png',
                'stock' => 100,
                'reference' => 'MC-JB-001',
                'actif' => true,
                'date_creation' => now(),
            ]
        );

        $produit5 = Produit::updateOrCreate(
            ['nom' => 'Minecraft Dungeons'],
            [
                'description' => 'Vivez une aventure épique dans l\'univers de Minecraft avec Minecraft Dungeons, un jeu d\'action-aventure captivant ! Plongez dans des donjons remplis de dangers, combattez des hordes de créatures hostiles et découvrez des trésors légendaires. Minecraft Dungeons combine l\'esthétique pixelisée emblématique de Minecraft avec un gameplay d\'action-aventure palpitant. Explorez des niveaux variés, collectez des armes et armures puissantes, et utilisez des artefacts magiques pour vaincre vos ennemis. Le jeu propose un mode solo passionnant ainsi qu\'un mode multijoueur coopératif jusqu\'à 4 joueurs, où vous pourrez affronter ensemble les défis les plus difficiles. Avec son système de progression, ses nombreuses quêtes et ses donjons générés procéduralement, Minecraft Dungeons offre des heures de gameplay addictif. Parfait pour les fans de Minecraft qui cherchent une expérience d\'action plus intense, ce jeu vous emmènera dans une aventure épique à travers le monde de Minecraft comme vous ne l\'avez jamais vue !',
                'prix' => 19.99,
                'image' => 'images/2.png',
                'pegi' => 'images/pegi7.png',
                'stock' => 100,
                'reference' => 'MC-DG-001',
                'actif' => true,
                'date_creation' => now(),
            ]
        );

        $produit6 = Produit::updateOrCreate(
            ['nom' => 'Minecraft Legends'],
            [
                'description' => 'Découvrez Minecraft Legends, un jeu d\'action-stratégie révolutionnaire qui vous plonge dans une aventure épique dans l\'univers de Minecraft ! Dans ce nouveau chapitre de la franchise Minecraft, vous devrez défendre votre monde contre une invasion de Piglins hostiles en utilisant vos compétences stratégiques et votre courage. Minecraft Legends combine des éléments d\'action en temps réel avec une stratégie tactique profonde, vous permettant de construire des défenses, de recruter des alliés et de mener des batailles épiques. Explorez un monde ouvert magnifique rempli de secrets à découvrir, de ressources à collecter et de créatures à rencontrer. Le jeu propose une campagne solo immersive ainsi qu\'un mode multijoueur compétitif où vous pouvez affronter d\'autres joueurs dans des batailles stratégiques. Avec ses graphismes magnifiques, sa bande-son épique et son gameplay innovant, Minecraft Legends offre une expérience unique qui ravira les fans de stratégie et d\'action. Rejoignez la légende et sauvez le monde de Minecraft !',
                'prix' => 39.99,
                'image' => 'images/3.png',
                'pegi' => 'images/pegi7.png',
                'stock' => 100,
                'reference' => 'MC-LG-001',
                'actif' => true,
                'date_creation' => now(),
            ]
        );

        $produit7 = Produit::updateOrCreate(
            ['nom' => 'Pop Minecraft Creeper'],
            [
                'description' => 'Attention, danger ! Ajoutez la créature la plus emblématique et redoutée de Minecraft à votre collection avec cette figurine Pop Funko Minecraft Creeper ! Ce Creeper vert pixelisé est instantanément reconnaissable avec son visage caractéristique et sa posture menaçante. Mesurant environ 10 cm de hauteur, cette Pop Funko capture parfaitement l\'essence de cette créature explosive qui fait partie intégrante de l\'expérience Minecraft. Fabriquée avec des matériaux de qualité supérieure, cette figurine présente un fini soigné avec les détails verts et noirs emblématiques du Creeper. Que vous soyez un collectionneur passionné ou un fan de Minecraft, cette Pop Funko Creeper est un must-have pour toute collection. Le Creeper est l\'une des créatures les plus iconiques du jeu - maintenant, vous pouvez l\'avoir dans votre collection sans risquer d\'explosion ! Parfaite pour décorer votre bureau, votre étagère ou votre collection de figurines Minecraft.',
                'prix' => 14.99,
                'image' => 'images/produits/pop-4.png',
                'stock' => 50,
                'reference' => 'POP-MC-004',
                'actif' => true,
                'date_creation' => now(),
            ]
        );

        $produit8 = Produit::updateOrCreate(
            ['nom' => 'Pop Minecraft Squelette'],
            [
                'description' => 'Ajoutez une touche d\'os à votre collection avec cette impressionnante figurine Pop Funko Minecraft Squelette ! Ce squelette armé d\'un arc est l\'une des créatures hostiles les plus reconnaissables de Minecraft. La figurine capture parfaitement l\'apparence pixelisée du squelette avec ses os blancs caractéristiques et son arc menaçant. Mesurant environ 10 cm de hauteur, cette Pop Funko est fabriquée avec des matériaux de qualité et présente un fini détaillé. Le squelette Minecraft est connu pour être un adversaire redoutable qui attaque à distance avec ses flèches - maintenant, vous pouvez avoir cette créature emblématique dans votre collection sans risquer de vous faire tirer dessus ! Parfaite pour les collectionneurs et les fans de Minecraft, cette Pop Funko Squelette complétera parfaitement votre collection de figurines Minecraft. Idéale pour décorer votre espace de jeu ou votre collection de souvenirs Minecraft.',
                'prix' => 14.99,
                'image' => 'images/produits/pop-5.png',
                'stock' => 50,
                'reference' => 'POP-MC-005',
                'actif' => true,
                'date_creation' => now(),
            ]
        );

        $produit9 = Produit::updateOrCreate(
            ['nom' => 'Pop Chat Jaune'],
            [
                'description' => 'Adoptez ce magnifique compagnon avec la figurine Pop Funko Chat Jaune Minecraft ! Ce chat jaune pixelisé est l\'un des animaux de compagnie les plus populaires dans Minecraft, connu pour sa loyauté et son charme. La figurine capture parfaitement l\'apparence adorable du chat Minecraft avec sa couleur jaune caractéristique et sa posture amicale. Mesurant environ 8 cm de hauteur, cette Pop Funko est fabriquée avec des matériaux de qualité supérieure et présente un fini soigné. Les chats dans Minecraft sont des compagnons fidèles qui vous suivent partout et vous protègent des créatures hostiles - maintenant, vous pouvez avoir ce compagnon loyal dans votre collection ! Parfaite pour les collectionneurs et les amoureux des animaux, cette Pop Funko Chat Jaune apportera une touche de douceur et de couleur à votre collection de figurines Minecraft. Idéale pour décorer votre bureau, votre chambre ou votre espace de jeu, cette figurine rappellera les moments joyeux passés avec votre compagnon félin dans Minecraft.',
                'prix' => 14.99,
                'image' => 'images/produits/pop-6.png',
                'stock' => 50,
                'reference' => 'POP-MC-006',
                'actif' => true,
                'date_creation' => now(),
            ]
        );

        $produitsSeeded = collect([
            $produit1,
            $produit2,
            $produit3,
            $produit4,
            $produit5,
            $produit6,
            $produit7,
            $produit8,
            $produit9,
        ])->filter();

        foreach ($produitsSeeded as $produit) {
            if (!$produit->categories()->where('categories.id_categorie', $uncategorized->id_categorie)->exists()) {
                $produit->categories()->attach($uncategorized->id_categorie);
            }
        }

        $figurine = Categorie::whereRaw('LOWER(nom) = ?', [mb_strtolower('figurine')])->first();
        $jeux = Categorie::whereRaw('LOWER(nom) = ?', [mb_strtolower('jeux')])->first();

        $popProducts = collect([$produit1, $produit2, $produit3, $produit7, $produit8, $produit9])->filter();
        $gameProducts = collect([$produit4, $produit5, $produit6])->filter();

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
