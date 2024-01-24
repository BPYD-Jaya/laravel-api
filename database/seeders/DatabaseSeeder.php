<?php

namespace Database\Seeders;

use App\Models\About;
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

            About::create([
                'address' => 'Komplek Ruko Transmart Unit Ruko 8-H, Jl. Raya Kalimalang No. 1, Kel. Pondok Kelapa, Kec. Duren Sawit, Jakarta Timur 13450',
                'phone_company' => '085710116209',
                'email_company' => 'wpihub@gmail.com',
                'ig_link' => 'https://www.instagram.com/wpindonesia_official/',
                'fb_link' => 'https://www.facebook.com/WPIndonesiaOfficial',
                'wa_link' => 'https://wa.me/6285710116209'
            ]);

            echo 'Seeding successful: Seeders created' . PHP_EOL;
        } catch (\Exception $e) {
            echo 'Seeding failed: ' . $e->getMessage() . PHP_EOL;
        }
    }
}
