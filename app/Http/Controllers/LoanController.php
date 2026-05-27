<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;

class LoanController extends Controller
{
    public function index()
    {
        return Loan::all();
    }

    public function show($id)
    {
        return Loan::find($id);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'book_id' => 'required|exists:books,id',
            'reader_id' => 'required|exists:readers,id',
            'loan_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:loan_date'
        ]);
        return Loan::create($fields);
    }

    public function update(Request $request, $id)
    {
        $fields = $request->validate([
            'book_id' => 'required|exists:books,id',
            'reader_id' => 'required|exists:readers,id',
            'loan_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:loan_date'
        ]);
        $loan = Loan::find($id);
        return $loan->update($fields);
    }

    public function destroy($id)
    {
        $loan = Loan::find($id);
        $loan->delete();
        return ['Loan successfully deleted.'];
    }
}
