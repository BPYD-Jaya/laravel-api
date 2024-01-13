<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Uncomment the following line if you want to use the User factory.
        // \App\Models\User::factory(10)->create();

        $this->call([
            UserSeeder::class,
        ]);
    }
}

class UserSeeder extends Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run()
    {
        try {
            $hashedPassword = Hash::make('P@ssw0rd');

            User::create([
                'name' => 'Admin',
                'email' => 'admin@ptwpi.co.id',
                'password' => $hashedPassword,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);

            echo 'Seeding successful: User created' . PHP_EOL;
        } catch (\Exception $e) {
            echo 'Seeding failed: ' . $e->getMessage() . PHP_EOL;
        }
    }
}
