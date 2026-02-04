<?php

// Users login
Route::post('/login', 'Api\AuthController@login');
Route::middleware('auth:api')->group(function () {
    Route::get('authors/export', 'Api\AuthorController@export');
    Route::get('books/export', 'Api\BookController@export');

    Route::apiResource('users', 'Api\UserController');
    Route::apiResource('authors', 'Api\AuthorController');
    Route::apiResource('books', 'Api\BookController');
});
