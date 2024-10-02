<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function accounts(): BelongsToMany
  {
      return $this->belongsToMany(Account::class, 'transaction_categories');
  }
}
