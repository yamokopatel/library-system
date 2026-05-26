<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reader;

class ReaderController extends Controller
{
    public function index()
    {
        return Reader::all();
    }

    public function show($id)
    {
        return Reader::find($id);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|max:255|unique:readers,email'
        ]);
        return Reader::create($fields);
    }

    public function update(Request $request, $id)
    {
        $fields = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|max:255|unique:readers,email'
        ]);
        $reader = Reader::find($id);
        return $reader->update($fields);
    }

    public function destroy($id)
    {
        $reader = Reader::find($id);
        $reader->delete();
        return ['Reader successfully deleted.'];
    }
}
