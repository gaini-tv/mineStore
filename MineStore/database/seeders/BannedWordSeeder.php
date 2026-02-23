<?php

namespace Database\Seeders;

use App\Models\BannedWord;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannedWordSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $words = [
            'con',
            'connard',
            'connasse',
            'pute',
            'salope',
            'enculé',
            'encule',
            'fdp',
            'ntm',
            'merde',
            'batard',
            'bâtard',
            'abruti',
            'idiot',
            'imbécile',
            'pd',
            'tg',
            'ta gueule',
            'suicide',
            'kys',
            'fuck',
            'shit',
            'bitch',
            'nazi',
            'hitler',
            'raciste',
            'racial',
            'putelette',
            'sexual',
            'viol',
            'violer',
            'gros con',
            'sale con',
        ];

        foreach ($words as $word) {
            BannedWord::firstOrCreate(['word' => $word]);
        }
    }
}

