<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
}