<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
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
    }
}

