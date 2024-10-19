<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetTransaction extends Model
{
    use HasFactory;

    protected $table = 'budget_transactions';
    
    protected $fillable = [
      'budget_id',
      'transaction_id',
    ];

    public function budget(): BelongsTo
    {
      return $this->belongsTo(Budget::class);
    }
    
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
