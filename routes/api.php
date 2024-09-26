<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;

Route::get('/welcome',function(){
return "Welcome Boss";
});
Route::controller(AuthController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::get('user', 'userProfile')->middleware('auth:sanctum');
    Route::get('logout', 'userLogout')->middleware('auth:sanctum');
});

// Authors Routes
Route::controller(AuthorController::class)->middleware('auth:sanctum')->group(function(){
    Route::get('/all-authors','index');
    Route::post('/add-author','store');
    Route::get('/author-detail/{id}','show');
    Route::put('/update-author/{id}', 'update');
    Route::delete('/delete-author/{id}','destroy');
    Route::get('/authors/search', 'searchAuthor');
});


// Books Routes
Route::controller(BookController::class)->middleware('auth:sanctum')->group(function(){
    Route::get('/books','index');
    Route::post('/add-book','store');
    Route::get('/book-detail/{id}','show');
    Route::put('/update-book/{id}', 'update');
    Route::delete('/delete-book/{id}','destroy');
    Route::get('/books/search', 'searchBook');
});


