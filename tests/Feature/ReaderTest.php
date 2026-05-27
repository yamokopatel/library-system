<?php

use App\Models\Reader;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\ReaderSeeder;

uses(RefreshDatabase::class);

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('returns reader list in JSON format', function(){
    $this->seed(ReaderSeeder::class);
    $response = $this->getJson('/api/readers');
    $response->assertStatus(200)->assertJsonCount(15)->assertJsonStructure(['*' => ['id','name','email','created_at', 'updated_at']]);
});

it('could successfully create new reader', function(){
    $readerData = [
        'name' => 'John Doe',
        'email' => 'jdoe@example.com'
    ];
    $response = $this->postJson('/api/readers', $readerData);
    $response->assertStatus(201)->assertJsonFragment($readerData);
    $this->assertDatabaseHas('readers', $readerData);
});

it('couldnt failurely create new reader', function(){
    $response = $this->postJson('/api/readers', [
        'name' => '',
        'email' => 'janedoe@example.com'
    ]);
    $response->assertStatus(422)->assertJsonValidationErrors(['name']);
});

it('shown one reader', function(){
    $this->seed(ReaderSeeder::class);
    $reader = Reader::first();
    $response = $this->getJson("/api/readers/{$reader->id}");
    $response->assertStatus(200)->assertJsonPath('id', $reader->id)->assertJsonPath('name', $reader->name);
});

it('could update exsisted reader information', function(){
    $this->seed(ReaderSeeder::class);
    $reader = Reader::first();
    $updateData = [
        'name' => 'Changed reader name',
        'email' => 'jdoe@example.com'
    ];
    $response = $this->putJson("/api/readers/{$reader->id}", $updateData);
    $response->assertStatus(200);
    $this->assertDatabaseHas('readers', [
        'id' => $reader->id,
        'name' => 'Changed reader name'
    ]);
});

it('could successfully delete reader', function(){
    $this->seed(ReaderSeeder::class);
    $reader = Reader::first();
    $response = $this->deleteJson("/api/readers/{$reader->id}");
    $response->assertStatus(204);
    $this->assertDatabaseMissing('readers', ['id' => $reader->id]);
    $this->assertEquals(14, Reader::count());
});