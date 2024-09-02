<?php

namespace Database\Seeders;
use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 100; $i++) {
            DB::table('users')->insert([
                'nom' => $faker->lastName,
                'prenom' => $faker->firstName,
                'email' => $faker->unique()->safeEmail,
                'mot_de_passe' => Hash::make('password'), // Vous pouvez mettre un mot de passe par défaut
                'role' => 1, // Supposant que vous avez des rôles 1, 2, 3
            ]);
        }

        $users = [
            [
                'nom' => 'mohamed',
                'prenom' => 'kone',
                'email' => 'kone35811@gmail.com',
                'mot_de_passe' => Hash::make('password'),
                'role' => 1,
            ],
        ];

        foreach ($users as $user) {
            $newUser = DB::table('users')->insertGetId($user);
            $this->sendInvitation($newUser);
        }

    }

    private function sendInvitation($userId)
    {
        $user = User::find($userId);
        $data = [
            'subject' => 'Invitation à rejoindre un projet',
            'email' => $user->email,
            'content' => 'Vous avez été invité à rejoindre notre plateforme.'
        ];
        Mail::raw($data['content'], function ($message) use ($data) {
            $message->to($data['email'])
                    ->subject($data['subject']);
        });
    }
    
}
