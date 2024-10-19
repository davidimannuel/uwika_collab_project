<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DebtRepayment extends Model
{
  
  use HasFactory;

  protected $table = 'debt_repayments';
  
  protected $fillable = [
    'debt_id',
    'transaction_id',
  ];

  public function debt(): BelongsTo
  {
      return $this->belongsTo(Debt::class);
  }

  public function transaction(): BelongsTo
  {
      return $this->belongsTo(Transaction::class);
  }
}
