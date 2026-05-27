<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($a = 0; $a < 7; $a++)
        {
            $randomBookId = DB::table('books')->inRandomOrder()->value('id');
            $randomReaderId = DB::table('readers')->inRandomOrder()->value('id');
            $loanDate = fake()->date();
            DB::table('loans')->insert([
                'book_id' => $randomBookId,
                'reader_id' => $randomReaderId,
                'loan_date' => $loanDate,
                'return_date' => date('Y-m-d', strtotime($loanDate . ' + 14 days')),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
