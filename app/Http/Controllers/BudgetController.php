<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $budgets = Auth::user()->budgets()
        ->with('category') // Eager load the related category
        ->orderBy('updated_at', 'desc')
        ->paginate(5);
      
      return view('budget.index',[
        'budgets' => $budgets,
      ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      $categories = Category::where('user_id',Auth::id())->get();

      return view('budget.create',[
        'categories' => $categories,
      ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      $request->validate([
        'name' => ['required'],
        'category_id' => ['required'],
        'transaction_type' => ['required'],
        'start_at' => ['required'],
        'end_at' => ['required'],
        'threshold_amount' => ['numeric', 'gt:0'],
      ]);

      $startAt = Carbon::createFromFormat('Y-m-d', $request->input('start_at'))->startOfDay();
      $endAt = Carbon::createFromFormat('Y-m-d', $request->input('end_at'))->endOfDay();

      $budget = Budget::create([
        'category_id' => $request->input('category_id'),
        'name' => $request->input('name'),
        'transaction_type' => $request->input('transaction_type'),
        'collected_amount' => 0,
        'threshold_amount' => $request->input('threshold_amount'),
        'start_at' => $request->input('start_at'),
        'end_at' => $request->input('end_at'),
      ]);

      return redirect()->route('budgets.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Budget $budget)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Budget $budget)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Budget $budget)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budget $budget)
    {
      if ($budget->budgetTransactions()->limit(1)->count() > 0) {
        return redirect(route('budgets.index'))->with('alert',"cannot delete $budget->name, because already have transactions");
      }
      $budget->delete();
      return redirect(route('budgets.index'));
    }
}
