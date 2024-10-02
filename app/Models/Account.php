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
        $categories = $transaction['categories'];
        
        $newTransaction = $this->transactions()->create($transaction);
        $newTransaction->categories()->attach($categories);
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
