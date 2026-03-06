<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

// Route d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');

// Recherche de produits
Route::get('/recherche', [HomeController::class, 'search'])->name('search.produits');

// Route produits
Route::get('/produits', [ProduitController::class, 'index'])->name('produits.index');
Route::get('/produits/{id}', [ProduitController::class, 'show'])->name('produits.show');

// Route commentaires
Route::post('/produits/{produitId}/commentaires', [CommentaireController::class, 'store'])->name('commentaires.store')->middleware('auth');
Route::delete('/commentaires/{commentaire}', [CommentaireController::class, 'destroy'])->name('commentaires.destroy')->middleware('auth');

// Route blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{article}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/blog', [BlogController::class, 'store'])->name('blog.store')->middleware('auth');
Route::post('/blog/{article}/commentaires', [BlogController::class, 'storeComment'])->name('blog.commentaires.store');
Route::delete('/blog/commentaires/{commentaire}', [BlogController::class, 'destroyComment'])->name('blog.commentaires.destroy')->middleware('auth');

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

// Routes panier
Route::middleware('auth')->group(function () {
    Route::get('/panier', [PanierController::class, 'index'])->name('panier.index');
    Route::post('/panier/ajouter/{id}', [PanierController::class, 'add'])->name('panier.add');
    Route::post('/panier/ligne/{ligneId}/quantite', [PanierController::class, 'updateLine'])->name('panier.updateLine');
    Route::delete('/panier/ligne/{ligneId}', [PanierController::class, 'removeLine'])->name('panier.removeLine');
    Route::post('/panier/payer', [PanierController::class, 'checkout'])->name('panier.checkout');
    Route::get('/panier/succes', [PanierController::class, 'success'])->name('panier.success');
});

Route::middleware('auth')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('admin.users.updateRole');
        Route::post('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::post('/admin/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::put('/admin/categories/{categorie}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/admin/categories/{categorie}', [AdminController::class, 'destroyCategory'])->name('admin.categories.destroy');
    Route::post('/admin/banned-words', [AdminController::class, 'storeBannedWord'])->name('admin.banned-words.store');
    Route::delete('/admin/banned-words/{bannedWord}', [AdminController::class, 'destroyBannedWord'])->name('admin.banned-words.destroy');
    Route::post('/admin/entreprises/{entreprise}/approve', [AdminController::class, 'approveEntreprise'])->name('admin.entreprises.approve');
    Route::post('/admin/entreprises/{entreprise}/refuse', [AdminController::class, 'refuseEntreprise'])->name('admin.entreprises.refuse');
    Route::post('/admin/entreprises/{entreprise}/approve-deletion', [AdminController::class, 'approveEntrepriseDeletion'])->name('admin.entreprises.approveDeletion');
    Route::post('/admin/entreprises/{entreprise}/cancel-deletion', [AdminController::class, 'cancelEntrepriseDeletion'])->name('admin.entreprises.cancelDeletion');
    Route::post('/entreprises', [ProfilController::class, 'storeEntreprise'])->name('entreprises.store');
    Route::get('/entreprise', [EntrepriseController::class, 'index'])->name('entreprise.index');
    Route::post('/entreprise/delete-request', [EntrepriseController::class, 'requestDeletion'])->name('entreprise.requestDeletion');
    Route::post('/entreprise/add-member', [EntrepriseController::class, 'addMember'])->name('entreprise.addMember');
    Route::post('/entreprise/members/{member}/role', [EntrepriseController::class, 'updateMemberRole'])->name('entreprise.members.updateRole');
    Route::delete('/entreprise/members/{member}', [EntrepriseController::class, 'removeMember'])->name('entreprise.members.remove');
    Route::post('/articles', [EntrepriseController::class, 'storeArticle'])->name('articles.store');
    Route::put('/articles/{id}', [EntrepriseController::class, 'updateArticle'])->name('articles.update');
    Route::delete('/articles/{id}', [EntrepriseController::class, 'destroyArticle'])->name('articles.destroy');
    Route::post('/produits', [ProduitController::class, 'store'])->name('produits.store');
    Route::put('/produits/{id}', [ProduitController::class, 'update'])->name('produits.update');
    Route::delete('/produits/{id}', [ProduitController::class, 'destroy'])->name('produits.destroy');
    Route::patch('/produits/{id}/stock', [ProduitController::class, 'updateStockSettings'])->name('produits.updateStockSettings');
});
