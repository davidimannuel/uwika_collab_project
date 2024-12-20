<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request) {
      // If it's a POST request, validate the input
      if ($request->isMethod('post')) {
        $validated = $request->validate([
            'account_id' => 'nullable|exists:accounts,id',
            'transaction_from' => 'required|date',
            'transaction_to' => 'required|date|after_or_equal:transaction_from',
        ]);
      }
      $accountId = $request->input('account_id') ?? '';
      $transactionFrom = $request->input('transaction_from') 
          ? Carbon::parse($request->input('transaction_from'))->startOfDay() 
          : Carbon::today()->startOfDay();
      $transactionTo = $request->input('transaction_to') 
          ? Carbon::parse($request->input('transaction_to'))->endOfDay() 
          : Carbon::today()->endOfDay();
      $transactions = Transaction::with(['account', 'categories'])
        ->whereHas('account', function ($query) {
            $query->where('user_id', Auth::id()); // Filter transactions by current user
          })
        ->when($accountId != '', function ($query) use ($accountId) {
            $query->where('account_id', $accountId);
        })
        ->whereBetween('transaction_at', [$transactionFrom, $transactionTo])
        ->orderBy('transaction_at', 'asc')
        ->get();
      
      $isPrint = $request->input('is_print') ?? false;
      if ($isPrint) {
        return view('transaction.pdf', [
          'transaction_from' =>  $request->input('transaction_from'),
          'transaction_to' =>  $request->input('transaction_to'),
          'transactions' => $transactions
        ]);
      }

      $accounts = Account::where('user_id', Auth::id())->get();
      return view('transaction.index', [
          'transactions' => $transactions,
          'accounts' => $accounts
      ]);
    }
}
