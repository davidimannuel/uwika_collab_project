<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Debt;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
      // Paginator
      Paginator::useBootstrapFive();
      // Gate
      Gate::define('create-category',function(User $user) {
        return ($user->status === User::STATUS_ACTIVE);
      });
      Gate::define('edit-category',function(User $user, Category $category) {
        return $category->user()->is($user) && ($user->status === User::STATUS_ACTIVE);
      });
      Gate::define('delete-category',function(User $user, Category $category) {
        return $category->user()->is($user) && ($user->status === User::STATUS_ACTIVE);
      });
      Gate::define('create-transaction',function(User $user) {
        return ($user->status === User::STATUS_ACTIVE);
      });
      Gate::define('create-debt-repayment',function(User $user) {
        return ($user->status === User::STATUS_ACTIVE);
      });
    }
}
