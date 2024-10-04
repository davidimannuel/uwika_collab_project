<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // admin
      User::create([
        'name' => 'Admin 1',
        'email' => 'admin1@admin.com',
        'password' => bcrypt('password'),
        // 'password' => bcrypt('d0HFeFx8TN7YSnU4'),
        'is_admin' => true,
        'status' => User::STATUS_ACTIVE,
      ]);
      
      // User::create([
      //   'name' => 'Admin 2',
      //   'email' => 'admin2@admin.com',
      //   'password' => bcrypt('Ujr2L39DT25JpLAK'),
      //   'email_verified_at' => now(),
      //   'is_admin' => true,
      //   'status' => 'active',
      // ]);
      
      // user factory
      // User::factory()
      // ->has(Account::factory()->count(6))
      // ->has(Category::factory()->count(6))
      // ->count(15)
      // ->create(); 

      // Create 15 users
      User::factory(15)->create()->each(function ($user) {
        // Each user has 6 accounts
        $accounts = Account::factory(6)->create(['user_id' => $user->id]);
    
        // Create a set of categories for the user (10 categories for example)
        $categoryIds = Category::factory(15)->create(['user_id' => $user->id])->pluck('id')->toArray();
    
        $accounts->each(function ($account) use ($user, $categoryIds) {
            // Initialize total debit and credit amounts
            $totalDebit = 0;
            $totalCredit = 0;
    
            // Create 20 transactions for each account
            $transactions = Transaction::factory(20)->make();
    
            // Save transactions and categorize them
            $transactions->each(function ($transaction) use ($account, $categoryIds, &$totalDebit, &$totalCredit) {
                // Save the transaction and associate it with the account
                $newTransaction = $account->transactions()->create($transaction->toArray());
    
                // Select random categories (between 1 and 3) from the pre-created category IDs
                $randomCategoryIds = collect($categoryIds)->random(rand(1, 3))->toArray();
    
                // Attach randomly selected categories to the transaction
                $newTransaction->categories()->attach($randomCategoryIds);
    
                // Accumulate total debit and credit amounts
                if ($transaction->type === Transaction::TYPE_DEBIT) {
                    $totalDebit += $transaction->amount;
                } elseif ($transaction->type === Transaction::TYPE_CREDIT) {
                    $totalCredit += $transaction->amount;
                }
            });
    
            // Calculate the final account balance after all transactions
            $newBalance = $account->balance + $totalDebit - $totalCredit;
    
            // Update the account's balance with the new balance
            $account->update(['balance' => $newBalance]);
        });
    });
    
    }
}
