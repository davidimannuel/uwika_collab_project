<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\BudgetTransaction;
use Illuminate\Http\Request;

class BudgetTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Budget $budget)
    {
        $budgetTransactions = BudgetTransaction::with('transaction.account')->where('budget_id', $budget->id)->orderBy('updated_at','desc')->paginate(5);
        return view('budget.transaction.index',[
          'budget' => $budget,
          'budgetTransactions' => $budgetTransactions,
        ]);
    }
}
