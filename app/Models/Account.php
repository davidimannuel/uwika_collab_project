<?php

namespace App\Models;

use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\InvalidTransactionTypeException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    protected $table = 'accounts';
    
    protected $fillable = [
      'user_id',
      'name',
      'balance',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function storeTransaction(array $transaction)
    {
      $type = $transaction['type'];
      $amount = $transaction['amount'];
      $categories = $transaction['categories'] ?? [];
      $is_debt = $transaction['is_debt'] ?? false;
      $debt_due_at = $transaction['debt_due_at'] ?? null;

      $newTransaction = $this->transactions()->create($transaction);
      if (count($categories) > 0) {
        $newTransaction->categories()->attach($categories);
      }
      if ($is_debt) {
        $newTransaction->debt()->create([
          'status' => Debt::STATUS_UNPAID,
          'due_at' => $debt_due_at,
        ]);
      }

      if ($type === Transaction::TYPE_DEBIT) {
        $this->balance += $amount;
      } elseif ($type === Transaction::TYPE_CREDIT) {
          if ($this->balance < $amount) {
            throw new InsufficientBalanceException();
          }
          $this->balance -= $amount;
      } else {
        throw new InvalidTransactionTypeException();
      }
      // Save updated balance
      $this->save();

      return $newTransaction;
    }
}