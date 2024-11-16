<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountTransactionController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\BudgetTransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserAdminController;
use App\Http\Middleware\EnsureIsAdmin;
use App\Models\BudgetTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
  if (Auth::check()) {
    if (!Auth::user()->is_admin) {
      return view('dashboard.index');
    } else {
      return view('home');
    }
  }
  return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about');
});

Route::get('/support', function () {
    $admins = User::where('is_admin',true)->get();
    return view('support',[
      'admins' => $admins
    ]);
});

// Auth
Route::get('/register',[RegisterController::class,'create'])->name('register.create');
Route::post('/register',[RegisterController::class,'store'])->name('register.store');

Route::get('/login',[LoginController::class,'create'])->name('login'); // must be named 'login', since laravel auth middleware automatically redirest to 'login' route by default
Route::post('/login',[LoginController::class,'store'])->name('login.store');
Route::post('/logout',[LoginController::class,'destroy'])->name('login.destroy');

Route::middleware(['auth'])->group(function () {
  // Dashboard
  Route::get('/dashboard/incomeExpensesThisYearByMonth',[DashboardController::class, 'incomeExpensesThisYearByMonth'])->name('dashboard.incomeExpensesThisYearByMonth');
  Route::get('/dashboard/incomeExpensesThisYearByAccount',[DashboardController::class, 'incomeExpensesThisYearByAccount'])->name('dashboard.incomeExpensesThisYearByAccount');
  Route::get('/dashboard/incomeExpensesByCategoryThisMonth',[DashboardController::class, 'incomeExpensesByCategoryThisMonth'])->name('dashboard.incomeExpensesByCategoryThisMonth');
  // Category
  Route::resource('categories',CategoryController::class);
  // Account
  Route::resource('accounts',AccountController::class);
  Route::get('/accounts/{account}/transfer',[AccountController::class, 'transfer'])->name('accounts.transfer');
  Route::post('/accounts/{account}/transfer',[AccountController::class, 'processTransfer'])->name('accounts.processTransfer');
  // Account Transaction
  Route::get('/accounts/{account}/transactions',[AccountTransactionController::class, 'index'])->name('accounts.transactions.index');
  Route::get('/accounts/{account}/transactions/create',[AccountTransactionController::class, 'create'])->name('accounts.transactions.create');
  Route::post('/accounts/{account}/transactions',[AccountTransactionController::class, 'store'])->name('accounts.transactions.store');
  // Debt
  Route::get('/debts', [DebtController::class, 'index'])->name('debts.index');
  Route::get('/debts/{debt:transaction_id}', [DebtController::class, 'show'])->name('debts.show');
  Route::get('/debts/{debt:id}/repayments', [DebtController::class, 'createRepayment'])->name('debts.repayments.create');
  Route::post('/debts/{debt:id}/repayments', [DebtController::class, 'storeRepayment'])->name('debts.repayments.store');
  // Budget
  Route::resource('budgets',BudgetController::class);
  Route::get('budgets/transactions/{budget}',[BudgetTransactionController::class, 'index'])->name('budgets.transactions.index');
  // for Admin only is_admin = true
  Route::prefix('admin')->middleware(EnsureIsAdmin::class)->group(function () {
    Route::get('/users', [UserAdminController::class,'index'])->name('admin.users.index');
    Route::patch('/users/{id}', [UserAdminController::class,'patchStatus'])->name('admin.users.status.update');
  });
});
