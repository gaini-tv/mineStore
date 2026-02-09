<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProduitController;
use Illuminate\Support\Facades\Route;

// Route d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');

// Recherche de produits
Route::get('/recherche', [HomeController::class, 'search'])->name('search.produits');

// Route produits
Route::get('/produits', [ProduitController::class, 'index'])->name('produits.index');

// Route blog
Route::get('/blog', function () {
    return view('blog.index');
})->name('blog.index');

// Route profil
Route::get('/profil', function () {
    return view('profil.index');
})->name('profil.index');
