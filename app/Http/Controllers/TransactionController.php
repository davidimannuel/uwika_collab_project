<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index() {
      $transactions = Transaction::with(['account', 'categories'])
        ->whereHas('account', function ($query) {
            $query->where('user_id', Auth::id()); // Filter transactions by current user
        })
        ->orderBy('transaction_at', 'asc')
        ->get();

      return view('transaction.index', [
          'transactions' => $transactions
      ]);
    }
}
