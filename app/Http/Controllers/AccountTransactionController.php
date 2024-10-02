<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AccountTransactionController extends Controller
{

  public function index(Account $account)
  {
    $transactions = $account->transactions()->orderBy('transaction_at', 'desc')->paginate(10);
    return view('account.transaction.index',[
      'account' => $account,
      'transactions' => $transactions
    ]);
  }
  
  public function create(Account $account)
  {
    $transactions = $account->transactions()->orderBy('transaction_at', 'desc')->paginate(10);
    $categories = Category::where('user_id',Auth::id())->get();
    
    return view('account.transaction.create',[
      'account' => $account,
      'transactions' => $transactions,
      'categories' => $categories,
    ]);
  }
  
  public function store(Account $account, Request $request)
  {
    $request->validate([
      'remark' => ['required'],
      'type' => ['required'],
      'transaction_at' => ['required'],
      'amount' => ['numeric', 'gte:0'],
    ]);

    $is_debt = $request->has('is_debt');
    if ($is_debt) {
      $request->validate([
        'debt_due_at' => ['required'],
      ]);
    }

    $errors = $account->storeTransaction([
      'type' => $request->input('type'),
      'amount' => $request->input('amount'),
      'remark' => $request->input('remark'),
      'transaction_at' => $request->input('transaction_at'),
      'categories' => $request->input('categories'),
      'is_debt' => $is_debt,
      'debt_due_at' => $request->input('debt_due_at'),
    ]);
    if (count($errors) > 0) {
      throw ValidationException::withMessages($errors);
    }
    
    return redirect(route('accounts.transactions.index', $account->id));
  }
}
