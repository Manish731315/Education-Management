<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user if it doesn't exist
        if (!User::where('email', 'admin@edumart.com')->exists()) {
            User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@edumart.com',
                'role' => 'admin',
                'password' => bcrypt('password'),
            ]);
        }

        // Create regular users if they don't exist
        if (User::where('role', 'user')->count() < 10) {
            User::factory(10)->create([
                'role' => 'user',
            ]);
        }

        // Create categories
        $this->call(CategorySeeder::class);

        // Create products
        $this->call(ProductSeeder::class);
    }
}
