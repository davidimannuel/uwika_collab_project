<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountTransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TransactionController;
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
  // Account Transaction
  Route::get('/accounts/{account}/transactions',[AccountTransactionController::class, 'index'])->name('accounts.transactions.index');
  Route::get('/accounts/{account}/transactions/create',[AccountTransactionController::class, 'create'])->name('accounts.transactions.create');
  Route::post('/accounts/{account}/transactions',[AccountTransactionController::class, 'store'])->name('accounts.transactions.store');
  // Debt
  Route::get('/debts', [DebtController::class, 'index'])->name('debts.index');
  Route::get('/debts/{debt:transaction_id}', [DebtController::class, 'show'])->name('debts.show');
  Route::get('/debts/{debt:id}/repayments', [DebtController::class, 'createRepayment'])->name('debts.repayments.create');
  Route::post('/debts/{debt:id}/repayments', [DebtController::class, 'storeRepayment'])->name('debts.repayments.store');
  // Transaction
  Route::get('/transactions',[TransactionController::class, 'index'])->name('transactions.index');
  Route::get('/transactions/create',[TransactionController::class, 'create'])->name('transactions.create');
  Route::post('/transactions',[TransactionController::class, 'create'])->name('transactions.store');
});
