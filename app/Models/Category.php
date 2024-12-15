<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;
    
    protected $table = 'categories';
    
    protected $fillable = [
      'user_id',
      'name',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactionCategories(): BelongsToMany
    {
        return $this->belongsToMany(Transaction::class, 'transaction_categories');
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class);
    }
}
