<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ArticleController::class, 'index']);
Route::post('/import', [ArticleController::class, 'getArticleFromApi']);
Route::get('/updTable', [ArticleController::class, 'updateTable']);
