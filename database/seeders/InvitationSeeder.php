<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvitationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $invitations = [
            [
                'id_projet' => 2,
                'id_utilisateur' => 1,
                'status' => 'en attente',
            ],
            [
                'id_projet' => 2,
                'id_utilisateur' => 3,
                'status' => 'en attente',
            ],
            [
                'id_projet' => 2,
                'id_utilisateur' => 4,
                'status' => 'en attente',
            ],
        ];

        DB::table('invitations')->insert($invitations);
    }
}
