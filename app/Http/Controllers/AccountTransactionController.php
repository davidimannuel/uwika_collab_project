<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientBalanceException as ExceptionsInsufficientBalanceException;
use App\Exceptions\InvalidTransactionTypeException as ExceptionsInvalidTransactionTypeException;
use App\Models\Account;
use App\Models\Category;
use App\Models\InsufficientBalanceException;
use App\Models\InvalidTransactionTypeException;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    DB::beginTransaction();
    try {
      $newTransaction = $account->storeTransaction([
        'type' => $request->input('type'),
        'amount' => $request->input('amount'),
        'remark' => $request->input('remark'),
        'transaction_at' => $request->input('transaction_at'),
        'categories' => $request->input('categories'),
        'is_debt' => $is_debt,
        'debt_due_at' => $request->input('debt_due_at'),
      ]);

      // Commit the transaction if everything is successful
      DB::commit();
      return redirect(route('accounts.transactions.index', $account->id))->with('success-alert', "success create transaction");;
    } catch (ExceptionsInsufficientBalanceException $e) {
        DB::rollBack();
        throw ValidationException::withMessages(['amount' => 'insufficient balance']);
    } catch (ExceptionsInvalidTransactionTypeException $e) {
        DB::rollBack();
        throw ValidationException::withMessages(['type' => 'invalid transaction type']);
    } catch (Exception $e) {
        DB::rollBack();
        return redirect(route('accounts.transactions.create'))->with('error-alert', $e->getMessage());
    }
  }
}
