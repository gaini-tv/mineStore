<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

// Route d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');

// Recherche de produits
Route::get('/recherche', [HomeController::class, 'search'])->name('search.produits');

// Route produits
Route::get('/produits', [ProduitController::class, 'index'])->name('produits.index');
Route::get('/produits/{id}', [ProduitController::class, 'show'])->name('produits.show');

// Route commentaires
Route::post('/produits/{produitId}/commentaires', [CommentaireController::class, 'store'])->name('commentaires.store');

// Route blog
Route::get('/blog', function () {
    return view('blog.index');
})->name('blog.index');

// Route profil
Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
Route::post('/profil/update', [ProfilController::class, 'update'])->name('profil.update');
Route::post('/profil/avatar', [ProfilController::class, 'updateAvatar'])->name('profil.updateAvatar');
Route::post('/profil/updatePassword', [ProfilController::class, 'updatePassword'])->name('profil.updatePassword');

// Routes d'authentification
Route::get('/login', function () {
    return redirect()->route('profil.index');
})->name('login');
Route::get('/register', function () {
    return redirect()->route('profil.index');
})->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
