<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reader extends Model
{
    protected $fillable = [
        'name',
        'email'
    ];
}
