<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\AdminController;
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
Route::get('/email/verify/{token}', [AuthController::class, 'verifyEmail'])->name('verification.verify');

Route::get('/entreprise/confirm-delete/{token}', [EntrepriseController::class, 'confirmDeletion'])->name('entreprise.confirmDeletion');

Route::middleware('auth')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('admin.users.updateRole');
        Route::post('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::post('/admin/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::put('/admin/categories/{categorie}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/admin/categories/{categorie}', [AdminController::class, 'destroyCategory'])->name('admin.categories.destroy');
    Route::post('/admin/entreprises/{entreprise}/approve', [AdminController::class, 'approveEntreprise'])->name('admin.entreprises.approve');
    Route::post('/admin/entreprises/{entreprise}/refuse', [AdminController::class, 'refuseEntreprise'])->name('admin.entreprises.refuse');
    Route::post('/admin/entreprises/{entreprise}/approve-deletion', [AdminController::class, 'approveEntrepriseDeletion'])->name('admin.entreprises.approveDeletion');
    Route::post('/admin/entreprises/{entreprise}/cancel-deletion', [AdminController::class, 'cancelEntrepriseDeletion'])->name('admin.entreprises.cancelDeletion');
    Route::post('/entreprises', [ProfilController::class, 'storeEntreprise'])->name('entreprises.store');
    Route::get('/entreprise', [EntrepriseController::class, 'index'])->name('entreprise.index');
    Route::post('/entreprise/delete-request', [EntrepriseController::class, 'requestDeletion'])->name('entreprise.requestDeletion');
    Route::post('/entreprise/add-member', [EntrepriseController::class, 'addMember'])->name('entreprise.addMember');
    Route::post('/produits', [ProduitController::class, 'store'])->name('produits.store');
    Route::put('/produits/{id}', [ProduitController::class, 'update'])->name('produits.update');
    Route::delete('/produits/{id}', [ProduitController::class, 'destroy'])->name('produits.destroy');
});
