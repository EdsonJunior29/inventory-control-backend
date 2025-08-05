<?php

namespace Database\Seeders;

use App\Domain\Enums\Profiles;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProfilesAndUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cria perfis baseados no enum
        $profiles = [
            ['id' => Profiles::ADMIN->value, 'name' => 'Admin'],
        ];

        foreach ($profiles as $profile) {
            Profile::updateOrCreate(
                ['id' => $profile['id']],
                ['name' => $profile['name']]
            );
        }

        if (!User::where('email', 'admin@example.com')->exists()) {
            $this->createUser(
                'Admin User', 
                'admin@example.com', 
                'Teste2@145',
                Profiles::ADMIN->value
            );
        }
    }

    private function createUser($name, $email, $password,  $profileId)
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $user->profiles()->attach($profileId);

        return $user;
    }
}