<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function incomeExpensesThisYearByMonth() {
      $currentYear = Carbon::now()->year;
      $currentUserId = Auth::id();
  
      // Fetch total income and total expenses grouped by month for the current year
      $data = DB::table('transactions')
        ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
        ->where('accounts.user_id', $currentUserId)
        ->whereYear('transactions.transaction_at', $currentYear)
        ->selectRaw(
            'EXTRACT(MONTH FROM transactions.transaction_at) as month,
             SUM(CASE WHEN transactions.type = ? THEN transactions.amount ELSE 0 END) as total_income,
             SUM(CASE WHEN transactions.type = ? THEN transactions.amount ELSE 0 END) as total_expense',
            [Transaction::TYPE_CREDIT, Transaction::TYPE_DEBIT]
        )
        ->groupByRaw('EXTRACT(MONTH FROM transactions.transaction_at)')
        ->orderBy('month')
        ->get();
      
      // Map numeric month (1-12) to month names (January, February, ...)
      $monthNames = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
      ];
      // Create an array for all months (1-12) with default values
      $allMonths = collect(range(1, 12))->mapWithKeys(function ($month) use ($monthNames) {
        return [
            $month => [
                'month_sequence' => $month,
                'month' => $monthNames[$month],
                'total_income' => 0,
                'total_expense' => 0
            ]
        ];
      });
      // Update the months with actual data from the query
      foreach ($data as $item) {
        $allMonths[$item->month] = [
            'month_sequence' => $item->month,
            'month' => $monthNames[$item->month],
            'total_income' => $item->total_income,
            'total_expense' => $item->total_expense
        ];
      }

      // Ensure all months are in order and return the response
      $finalData = $allMonths->sortBy('month_sequence')->values();
      // dd($finalData);
      return response()->json([
        "data" => $finalData
      ]);
    }

    public function incomeExpensesByCategoryThisMonth() {
      $startOfMonth = Carbon::now()->startOfMonth();
      $endOfMonth = Carbon::now()->endOfMonth();
      $currentUserId = Auth::id();

      $data = DB::table('transactions')
      ->join('transaction_categories', 'transactions.id', '=', 'transaction_categories.transaction_id')
      ->join('categories', 'transaction_categories.category_id', '=', 'categories.id')
      ->join('accounts', 'transactions.account_id', '=', 'accounts.id')
      ->join('users', 'accounts.user_id', '=', 'users.id')
      ->where('users.id', $currentUserId)
      ->whereBetween('transactions.transaction_at', [$startOfMonth, $endOfMonth])
      ->selectRaw(
          'categories.id category_id, categories.name as category, 
          SUM(CASE WHEN transactions.type = ? THEN transactions.amount ELSE 0 END) as total_expense,
          SUM(CASE WHEN transactions.type = ? THEN transactions.amount ELSE 0 END) as total_income',
          [Transaction::TYPE_CREDIT,Transaction::TYPE_DEBIT])
      ->groupByRaw('categories.id')
      ->get();
      
      return response()->json([
        "data" => $data
      ]);
    }
}
