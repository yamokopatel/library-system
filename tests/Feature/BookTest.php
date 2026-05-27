<?php

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\BookSeeder;

// Katrs tests sāks ar tīru datubāzi
uses(RefreshDatabase::class);

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

/*

|--------------------------------------------------------------------------
| GET /api/books (Index)
|--------------------------------------------------------------------------
*/
it('atgriež grāmatu sarakstu JSON formātā', function () {
    // 1. Izpilda Tavu seederi (ieraksta 30 grāmatas)
    $this->seed(BookSeeder::class);

    // 2. Veic pieprasījumu
    $response = $this->getJson('/api/books');

    // 3. Pārbauda rezultātu
    $response->assertStatus(200)
             ->assertJsonCount(30) // Tā kā seederī ir "for < 30", te jābūt tieši 30
             ->assertJsonStructure([
                 '*' => ['id', 'title', 'author', 'isbn', 'count', 'created_at', 'updated_at']
             ]);
});

/*

|--------------------------------------------------------------------------
| POST /api/books (Store)
|--------------------------------------------------------------------------
*/
it('var veiksmīgi izveidot jaunu grāmatu', function () {
    // Šim testam seederi nevajag, jo mēs paši sūtām jaunus datus
    $bookData = [
        'title' => 'The Hobbit',
        'author' => 'J.R.R. Tolkien',
        'isbn' => '978-0261103344',
        'count' => 5,
    ];

    $response = $this->postJson('/api/books', $bookData);

    $response->assertStatus(201)
             ->assertJsonFragment($bookData);

    $this->assertDatabaseHas('books', $bookData);
});

it('neļauj izveidot grāmatu, ja trūkst obligātie lauki', function () {
    $response = $this->postJson('/api/books', [
        'title' => '', // Validācijas kļūda (required)
        'author' => 'J.K. Rowling',
        'isbn' => '12345',
        'count' => 'teksts, nevis skaitlis' // Validācijas kļūda (integer)
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['title', 'count']);
});

/*

|--------------------------------------------------------------------------
| GET /api/books/{id} (Show)
|--------------------------------------------------------------------------
*/
it('parāda vienu konkrētu grāmatu no seeder dotiem', function () {
    $this->seed(BookSeeder::class);

    // Paņem pirmo uzģenerēto grāmatu no DB
    $book = Book::first();

    $response = $this->getJson("/api/books/{$book->id}");

    $response->assertStatus(200)
             ->assertJsonPath('id', $book->id)
             ->assertJsonPath('title', $book->title);
});

/*

|--------------------------------------------------------------------------
| PUT /api/books/{id} (Update)
|--------------------------------------------------------------------------
*/
it('var atjaunināt esošas grāmatas informāciju', function () {
    $this->seed(BookSeeder::class);
    $book = Book::first();

    $updateData = [
        'title' => 'Mainīts grāmatas nosaukums',
        'author' => 'Mainīts Autors',
        'isbn' => '11111-22222222',
        'count' => 12
    ];

    $response = $this->putJson("/api/books/{$book->id}", $updateData);

    $response->assertStatus(200);

    // Pārbauda vai jaunie dati ir saglabāti
    $this->assertDatabaseHas('books', [
        'id' => $book->id,
        'title' => 'Mainīts grāmatas nosaukums'
    ]);
});

/*

|--------------------------------------------------------------------------
| DELETE /api/books/{id} (Destroy)
|--------------------------------------------------------------------------
*/
it('var veiksmīgi izdzēst grāmatu', function () {
    $this->seed(BookSeeder::class);
    $book = Book::first();

    $response = $this->deleteJson("/api/books/{$book->id}");

    // Ja Tavs kontrolieris neatgriež saturu, tad 204. Ja atgriež JSON ziņojumu, nomaini uz 200.
    $response->assertStatus(204);

    // Pārbauda vai grāmata tiešām ir izdzēsta no DB
    $this->assertDatabaseMissing('books', ['id' => $book->id]);

    // Kopējam skaitam datubāzē tagad vajadzētu būt 29 (30 mīnus 1)
    $this->assertEquals(29, Book::count());
});
