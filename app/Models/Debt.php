<?php

namespace App\Models;

use Exception;
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

  public function storeRepayment(array $repayment)
  {
    $transactionId = $repayment['transaction_id'];
    $amount = $repayment['amount'];
    $this->paid_amount += $amount;
    if ($this->paid_amount > $this->transaction->amount) {
      throw new PaidAmountGreaterThanDebtException;
    }
    if ($this->paid_amount == $this->transaction->amount) {
      $this->status = Debt::STATUS_PAID;
    } else {
      $this->status = Debt::STATUS_PARTIAL_PAID;
    }
    $this->repayments()->create([
      'transaction_id' => $transactionId,
    ]);
    $this->save();
  }
}

class PaidAmountGreaterThanDebtException extends Exception
{
    public function __construct($message = "Paid amount greater than debt")
    {
        // Call the base class constructor with the custom message
        parent::__construct($message);
    }
}