<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'nom' => 'Utilisateur',
                'description' => 'Utilisateur de l\'application',
                'id' => 1,
            ],
            [
                'nom' => 'Responsable',
                'description' => 'Responsable d\'un projet',
                'id' => 2,
            ],
        ];

        DB::table('roles')->insert($roles);
    }
}
