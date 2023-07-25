<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use  App\Models\User;
use  Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(5)->create();
        Product::factory(30)->create();

        foreach (Product::all() as $product) {
            $users = User::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $product->users()->attach($users, [
                'created_at' => now(),  // Update created_at timestamp
                'updated_at' => now(),  // Update updated_at timestamp
            ]);
        }
    }
}
