<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/nos-produits', function () {
    return view('pages.nos-produits');
})->name('nos-produits');

Route::get('/blog', function () {
    return view('pages.blog');
})->name('blog');

Route::get('/profile', function () {
    return view('pages.profile');
})->name('profile');
