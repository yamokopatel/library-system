<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index()
    {
        return Book::all();
    }

    public function show($id)
    {
        return Book::find($id);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'isbn' => 'required|max:20',
            'count' => 'required|integer'
        ]);
        return Book::create($fields);
    }

    public function update(Request $request, $id)
    {
        $fields = $request->validate([
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'isbn' => 'required|max:20',
            'count' => 'required|integer'
        ]);
        $book = Book::find($id);
        $book->update($fields);
        return $book;
    }

    public function destroy($id)
    {
        $book = Book::find($id);
        $book->delete();
        return ['Book successfully deleted;'];
    }
}
