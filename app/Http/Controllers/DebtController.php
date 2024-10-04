<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientBalanceException as ExceptionsInsufficientBalanceException;
use App\Exceptions\InvalidTransactionTypeException as ExceptionsInvalidTransactionTypeException;
use App\Exceptions\PaidAmountGreaterThanDebtException as ExceptionsPaidAmountGreaterThanDebtException;
use App\Models\Account;
use App\Models\Category;
use App\Models\Debt;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
      $debt = $debt->where('id',$debt->id)->with('transaction','repayments.transaction')->first();
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

      DB::beginTransaction();
      try {
        $account = Account::where('id',$request->input('account_id'))->first();
        $amount = $request->input('amount');
        $newTransaction = $account->storeTransaction([
          'type' => $request->input('type'),
          'amount' => $amount,
          'remark' => $request->input('remark'),
          'transaction_at' => $request->input('transaction_at'),
          'categories' => $request->input('categories'),
          'is_debt' => false,
          'debt_id' => $debt->id,
        ]);
        // repayment logic
        $debt->storeRepayment([
          'amount' => $amount,
          'transaction_id' => $newTransaction->id,
        ]);
        // Commit the transaction if everything is successful
        DB::commit();
        return redirect(route('debts.show', $debt->transaction_id))->with('success-alert', "success create debt transaction");;
      } catch (ExceptionsInsufficientBalanceException $e) {
          DB::rollBack();
          throw ValidationException::withMessages(['amount' => 'insufficient balance']);
      } catch (ExceptionsPaidAmountGreaterThanDebtException $e) {
          DB::rollBack();
          throw ValidationException::withMessages(['amount' => 'Paid amount greater than debt']);
      } catch (ExceptionsInvalidTransactionTypeException $e) {
          DB::rollBack();
          throw ValidationException::withMessages(['type' => 'invalid transaction type']);
      } catch (Exception $e) {
          DB::rollBack();
          return redirect(route('debts.repayments.create'))->with('error-alert', $e->getMessage());
      }
    }
}
