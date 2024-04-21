<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Car;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Login role sebagai user
        $user = User::create([
            'name' => 'user',
            'email' => 'user@mail.com',
            'role' => 'user',
            'password' => Hash::make('rahasia')
        ]);
        Contact::create([
            'name' => $user->name,
            'user_id' => $user->id
        ]);

        // Login role sebagai admin
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'role' => 'Admin',
            'password' => Hash::make('rahasia')
        ]);

        Contact::create([
            'name' => $admin->name,
            'user_id' => $admin->id
        ]);

        // Master Car
        Car::create([
            'merek' => 'Toyota Alphard 2020',
            'model' => 'City Car',
            'no_plat' => 'D 5100 BM',
            'tarif_sewa' => 30000,
            'qty' => 10
        ]);
    }
}
