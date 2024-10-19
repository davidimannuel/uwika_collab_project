<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\InvalidTransactionTypeException;
use App\Models\Account;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $accounts = Account::where('user_id', Auth::id())->orderBy('updated_at','desc')->paginate(5);
      return view('account.index',[
        'accounts' => $accounts,
      ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      return view('account.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {      
      $account = Account::create([
        'user_id' => Auth::id(),
        'name' => $request->input('name'),
      ]);
      
      return redirect(route('accounts.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
      return view('account.edit',[
        'account' => $account
      ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
      $request->validate([
        'name' => ['required'],
      ]);
      $account->name = $request->input('name');
      $account->save();

      return redirect(route('accounts.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
      if ($account->transactions()->limit(1)->count() > 0) {
        return redirect(route('accounts.index'))->with('alert',"cannot delete $account->name, because already have transactions");
      }
      $account->delete();
      return redirect(route('accounts.index'));
    }

    public function transfer(Account $account)
    {
      $destinationAccounts = Account::where('user_id',Auth::id())
        ->where('id', '!=', $account->id)->get();

      return view('account.transfer',[
        'originAccount' => $account,
        'destinationAccounts' => $destinationAccounts
      ]);
    }
    
    public function processTransfer(Account $account, Request $request)
    { 
      Gate::authorize('create-transaction');
      
      $request->validate([
        'destination_account_id' => ['required'],
        'transaction_at' => ['required'],
        'amount' => ['numeric', 'gt:0'],
        'admin_fee' => ['numeric', 'gte:0'],
      ]);

      $destinationAccount = Account::where('id', $request->input('destination_account_id'))->first();
      DB::beginTransaction();
      try {
        $originTransaction = $account->storeTransaction([
          'type' => Transaction::TYPE_CREDIT,
          'amount' => $request->input('amount'),
          'remark' => "transfer balance to $destinationAccount->name",
          'transaction_at' => $request->input('transaction_at'),
        ]);

        if ($request->input('admin_fee') > 0) {
          $originAdminFeeTransaction = $account->storeTransaction([
            'type' => Transaction::TYPE_CREDIT,
            'amount' => $request->input('admin_fee'),
            'remark' => "transfer balance to $destinationAccount->name" . '-(admin fee)',
            'transaction_at' => $request->input('transaction_at'),
          ]);
        }
        
        $newTransaction = $destinationAccount->storeTransaction([
          'type' => Transaction::TYPE_DEBIT,
          'amount' => $request->input('amount'),
          'remark' => "received balance from $account->name",
          'transaction_at' => $request->input('transaction_at'),
        ]);

        // Commit the transaction if everything is successful
        DB::commit();
        return redirect(route('accounts.transfer', $account->id))->with('success-alert', "success transfer balance");;
      } catch (InsufficientBalanceException $e) {
        DB::rollBack();
        throw ValidationException::withMessages(['amount' => 'insufficient balance']);
      } catch (Exception $e) {
        DB::rollBack();
        return redirect(route('accounts.transfer', $account->id))->with('error-alert', $e->getMessage());
      }
    }
}