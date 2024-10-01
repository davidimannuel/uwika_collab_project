<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});

// Auth
Route::get('/register',[RegisterController::class,'create'])->name('register.create');
Route::post('/register',[RegisterController::class,'store'])->name('register.store');

Route::get('/login',[LoginController::class,'create'])->name('login'); // must be named 'login', since laravel auth middleware automatically redirest to 'login' route by default
Route::post('/login',[LoginController::class,'store'])->name('login.store');
Route::post('/logout',[LoginController::class,'destroy'])->name('login.destroy');

Route::middleware(['auth'])->group(function () {
  // Category
  Route::resource('categories',CategoryController::class);
  // Account
  Route::resource('accounts',AccountController::class);
});
