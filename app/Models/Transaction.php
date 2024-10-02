<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Transaction extends Model
{
  use HasFactory;

  const TYPE_DEBIT = 'debit'; // income
  const TYPE_CREDIT = 'credit'; // expenses
    
  protected $table = 'transactions';
  
  protected $fillable = [
    'account_id',
    'remark',
    'type',
    'amount',
    'transaction_at',
  ];

  public function account(): BelongsTo
  {
      return $this->belongsTo(Account::class);
  }

  public function categories(): BelongsToMany
  {
      return $this->belongsToMany(Category::class, 'transaction_categories');
  }
}
