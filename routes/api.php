<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReaderController;

Route::apiResource('/books', BookController::class);
Route::apiRecource('/readers', ReaderController::class);