<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($a = 0; $a < 30; $a++)
        {
            DB::table('books')->insert([
                'title' => fake()->sentence(fake()->numberBetween(1,5)),
                'author' => fake()->name(),
                'isbn' => fake()->numerify('#####-########'),
                'count' => fake()->numberBetween(1,8),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
