<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\WordController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ArticleController::class, 'index']);
Route::post('/import', [ArticleController::class, 'getArticleFromApi']);
Route::get('/updTable', [ArticleController::class, 'updateTable']);
Route::post('/searchWord', [WordController::class, 'searchWord']);
