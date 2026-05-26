<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
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
