<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($a = 0; $a < 15; $a++)
        {
            DB::table('readers')->insert([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
