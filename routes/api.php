<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Authors Routes
Route::controller(AuthorController::class)->group(function(){
    Route::get('/all-authors','index');
    Route::post('/add-author','store');
    Route::get('/author-detail/{id}','show');
    Route::post('/update-author/{id}', 'update');
    Route::delete('/delete-author/{id}','destroy');
});


// Books Routes
Route::controller(BookController::class)->group(function(){
    Route::get('/books','index');
    Route::post('/add-book','store');
    Route::get('/book-detail/{id}','show');
    Route::post('/update-book/{id}', 'update');
    Route::delete('/delete-book/{id}','destroy');
});


