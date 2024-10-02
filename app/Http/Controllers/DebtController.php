<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\Debt;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class DebtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $currentUser = Auth::user();
      $debts = Debt::with('transaction')->whereHas('transaction.account', function ($query) use ($currentUser) {
        $query->where('user_id', $currentUser->id);
      })->orderBy('debts.due_at','asc')->paginate(10);

      return view('debt.index',[
        'debts' => $debts,
      ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Debt $debt)
    {
      $debt = $debt->with('transaction','repayments.transaction')->first();
      return view('debt.show',[
        'debt' => $debt,
      ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

     /**
     * Show the form for creating a new resource.
     */
    public function createRepayment(Debt $debt)
    {
      $debt->with('transaction','repayments.transaction');
      $userId = Auth::id();
      $categories = Category::where('user_id',$userId)->get();
      $accounts = Account::where('user_id',$userId)->get();

      return view('debt.repayment.create',[
        'debt' => $debt,
        'categories' => $categories,
        'accounts' => $accounts,
      ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeRepayment(Debt $debt, Request $request)
    {
      $request->validate([
        'type' => ['required'],
        'remark' => ['required'],
        'transaction_at' => ['required'],
        'amount' => ['numeric', 'gte:0'],
        'account_id' => ['required'],
      ]);

      $account = Account::where('id',$request->input('account_id'))->first();
      $errors = $account->storeTransaction([
        'type' => $request->input('type'),
        'amount' => $request->input('amount'),
        'remark' => $request->input('remark'),
        'transaction_at' => $request->input('transaction_at'),
        'categories' => $request->input('categories'),
        'is_debt' => false,
        'debt_id' => $debt->id,
      ]);
      if (count($errors) > 0) {
        throw ValidationException::withMessages($errors);
      }

      return redirect(route('debts.index'));
    }
}
