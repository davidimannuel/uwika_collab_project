<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Budget extends Model
{
    use HasFactory;

    protected $table = 'budgets';
    
    protected $fillable = [
      'category_id',
      'name',
      'transaction_type',
      'collected_amount',
      'threshold_amount',
      'start_at',
      'end_at',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function budgetTransactions(): HasMany
    {
        return $this->hasMany(BudgetTransaction::class);
    }

    public function storeTransaction(array $transaction)
    {
      $transactionId = $transaction['transaction_id'];
      $amount = $transaction['amount'];
      $this->collected_amount += $amount;
      $this->budgetTransactions()->create([
        'transaction_id' => $transactionId,
      ]);
      $this->save();
    }
}
