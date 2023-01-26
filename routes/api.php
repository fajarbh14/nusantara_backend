<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/me', function (Request $request) {
        return $request->user();
    });
    Route::post('/books', [BookController::class, 'store']);
    Route::get('/books/{id}',[BookController::class,'getById']);
    Route::get('/books',[BookController::class,'getAll']);
    Route::put('/books/{id}',[BookController::class,'update']);
    Route::delete('/books/{id}',[BookController::class,'delete']);
});