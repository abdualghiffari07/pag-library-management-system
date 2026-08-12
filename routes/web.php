<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\BookCopyController;


Route::resource('books', BookController::class);
Route::resource('authors', AuthorController::class);
Route::resource('categories', CategoryController::class);
Route::resource('locations', LocationController::class);
Route::resource('book-copies', BookCopyController::class);

Route::post(
    '/loans/{loan}/return',
    [LoanController::class, 'returnBook']
)->name('loans.return');
Route::resource('loans', LoanController::class);

Route::post(
    '/loan-details/{loanDetail}/return',
    [LoanController::class, 'returnBookDetail']
)->name('loan-details.return');
