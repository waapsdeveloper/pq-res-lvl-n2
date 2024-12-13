<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Laravel\Passport\PersonalAccessToken;
use App\Models\User; // Make sure the correct User model is imported
use Illuminate\Support\Facades\Artisan;

class PassportPersonalAccessTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Artisan::call('passport:install');
        // Find or create a user (or use an existing one)
        $user = User::first(); // You can change this to get a specific user or create one.

        // Create a personal access client if not exists
        $client = \Laravel\Passport\Client::where('personal_access_client', 1)->first();

        if (!$client) {
            // If no personal access client exists, create one.
            $client = \Laravel\Passport\Client::create([
                'user_id' => $user->id,
                'name' => 'AuthToken',
                // 'secret' => \Str::random(40),
                'secret' => str()->random(40),
                'provider' => null,
                'redirect' => 'http://localhost',
                'personal_access_client' => 1,
                'password_client' => 0,
                'revoked' => 0,
            ]);
        }

        // Create the Personal Access Token for the user
        $token = $user->createToken('AuthToken')->accessToken;

        // Insert the token into the database manually (Optional, this is generally handled automatically)
        // \Laravel\Passport\PersonalAccessToken::create([
        //     'tokenable_id' => $user->id,
        //     'tokenable_type' => get_class($user),
        //     'name' => 'AuthToken',
        //     'scopes' => '*',
        //     'revoked' => false,
        //     'expires_at' => now()->addYears(10), // Optional: Set expiration time
        //     'token' => $token, // Store the generated token
        // ]);

        // Output the generated token (Optional for debugging)

        $this->command->info("Personal Access Token: $token");
    }
}
