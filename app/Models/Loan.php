<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'book_id', //FK
        'reader_id', //FK
        'loan_date',
        'return_date'
    ];

    public function reader()
    {
        return $this->belongsTo(Reader::class);
    }
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
