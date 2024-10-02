<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Debt extends Model
{
  
  use HasFactory;

  const STATUS_UNPAID = 'unpaid'; // income
  const STATUS_PARTIAL_PAID = 'partial_paid'; // income
  const STATUS_PAID = 'paid'; // expenses
    
  protected $table = 'debts';
  
  protected $fillable = [
    'transaction_id',
    'status',
    'due_at',
    'paid_amount',
  ];

  public function transaction(): BelongsTo
  {
      return $this->belongsTo(Transaction::class);
  }

  public function repayments(): HasMany
  {
      return $this->hasMany(DebtRepayment::class);
  }
}
