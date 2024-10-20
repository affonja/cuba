<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\WordController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ArticleController::class, 'index'])->name('article.index');
Route::post('/import', [ArticleController::class, 'getArticleFromApi'])->name('article.import');
Route::get('/updTable', [ArticleController::class, 'updateTable'])->name('article.updTable');
Route::get('/article/{id}', [ArticleController::class, 'show'])->name('article.show');
Route::post('/search', [WordController::class, 'searchWord'])->name('word.search');
