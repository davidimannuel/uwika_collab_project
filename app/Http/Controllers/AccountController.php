<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
      $attributes = $request->validate([
        'name' => ['required'],
      ]);
      $attributes['user_id'] = Auth::id();
      Account::create($attributes);

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
      $account->delete();
      return redirect(route('accounts.index'));
    }
}
