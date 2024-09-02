<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projets = [
            [
                'nom' => 'Projet Alpha',
                'description' => 'Projet de développement web',
                'date_debut' => '2024-09-01',
                'date_fin' => '2024-12-01',
                'etat' => 'En attente',
                'id_responsable' => 1,
            ],
            [
                'nom' => 'Projet Beta',
                'description' => 'Projet de marketing digital',
                'date_debut' => '2024-10-01',
                'date_fin' => '2025-01-01',
                'etat' => 'En attente',
                'id_responsable' => 2,
            ],
        ];

        DB::table('projets')->insert($projets);
    }
}
