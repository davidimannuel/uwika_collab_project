<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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

    public function storeTransaction(array $transaction): array
    {
      $errors = DB::transaction(function () use ($transaction) {
        $type = $transaction['type'];
        $amount = $transaction['amount'];
        $categories = $transaction['categories'] ?? [];
        $is_debt = $transaction['is_debt'];
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

        // repayment logic
        $debt_id = $transaction['debt_id'] ?? 0;
        if ($debt_id > 0) {
          $debt = Debt::where('id', $debt_id)->first();
          $debt->paid_amount += $amount;
          if ($debt->paid_amount > $debt->transaction->amount) {
            return ["amount" => 'Paid amount greater than debt'];
          }
          
          if ($debt->paid_amount == $debt->transaction->amount) {
            $debt->status = Debt::STATUS_PAID;
          } else {
            $debt->status = Debt::STATUS_PARTIAL_PAID;
          }

          $debt->repayments()->create([
            'transaction_id' => $newTransaction->id,
          ]);
          $debt->save();
        }

        if ($type === Transaction::TYPE_DEBIT) {
          $this->balance += $amount;
        } elseif ($type === Transaction::TYPE_CREDIT) {
            if ($this->balance < $amount) {
              return ["amount" => 'Insufficient balance'];
            }
            $this->balance -= $amount;
        } else {
          return ["type" => 'invalid transaction type'];
        }
        // Save updated balance
        $this->save();
        return [];
      });

      return $errors;
    }
}
