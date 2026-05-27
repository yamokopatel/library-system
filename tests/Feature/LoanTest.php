<?php

use App\Models\Loan;
use App\Models\Reader;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\LoanSeeder;
use Database\Seeders\ReaderSeeder;
use Database\Seeders\BookSeeder;

uses(RefreshDatabase::class);

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('returns loan list in JSON format', function(){
    $this->seed(LoanSeeder::class);
    $response = $this->getJson('/api/loans');
    $response->assertStatus(200)->assertJsonCount(7)->assertJsonStructure(['*' => ['id','book_id','reader_id','loan_date', 'return_date', 'created_at', 'updated_at']]);
});

it('shown one loan', function(){
    $this->seed(LoanSeeder::class);
    $loan = Loan::first();
    $response = $this->getJson("/api/loans/{$loan->id}");
    $response->assertStatus(200)->assertJsonPath('id', $loan->id)->assertJsonPath('loan_date', $loan->loan_date);
});

it('could successfully delete loan', function(){
    $this->seed(LoanSeeder::class);
    $loan = Loan::first();
    $response = $this->deleteJson("/api/loans/{$loan->id}");
    $response->assertStatus(200);
    $this->assertDatabaseMissing('loans', ['id' => $loan->id]);
    $this->assertEquals(6, Loan::count());
});

it('could successfully create new loan', function(){
    $this->seed(BookSeeder::class);
    $book = Book::first();
    $this->seed(ReaderSeeder::class);
    $reader = Reader::first();
    $loanData = [
        'book_id' => $book->id,
        'reader_id' => $reader->id,
        'loan_date' => '2026-05-06',
        'return_date' => '2026-05-20'
    ];
    $response = $this->postJson('/api/loans', $loanData);
    $response->assertStatus(201)->assertJsonFragment($loanData);
    $this->assertDatabaseHas('loans', $loanData);
});

it('couldnt failurely create new loan', function(){
    $this->seed(BookSeeder::class);
    $book = Book::first();
    $response = $this->postJson('/api/loans', [
        'book_id' => $book->id,
        'reader_id' => '',
        'loan_date' => '2026-05-06',
        'return_date' => '2026-05-05'
    ]);
    $response->assertStatus(422)->assertJsonValidationErrors(['reader_id', 'return_date']);
});

it('could update exsisted loan information', function(){
    $this->seed(LoanSeeder::class);
    $loan = Loan::first();
    $updateData = [
        'book_id' => $loan->book_id,
        'reader_id' => $loan->reader_id,
        'loan_date' => '2026-05-06',
        'return_date' => '2026-05-27'
    ];
    $response = $this->putJson("/api/loans/{$loan->id}", $updateData);
    $response->assertStatus(200);
    $this->assertDatabaseHas('loans', [
        'id' => $loan->id,
        'return_date' => '2026-05-27'
    ]);
});