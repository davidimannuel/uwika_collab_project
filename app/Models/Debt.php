<?php

namespace App\Models;

use App\Exceptions\PaidAmountGreaterThanDebtException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    $debtType = $repayment['debt_type'] ?? Transaction::TYPE_DEBIT;
    $type = $repayment['type'] ?? Transaction::TYPE_DEBIT;
    $amount = $repayment['amount'];
    if (($debtType == Transaction::TYPE_DEBIT && $type == Transaction::TYPE_DEBIT) || 
      ($debtType == Transaction::TYPE_CREDIT && $type == Transaction::TYPE_CREDIT)) {
      $amount = $amount * -1; // make it negative
    }
    $transactionId = $repayment['transaction_id'];
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