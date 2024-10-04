<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
      $data = [
        'remark' => $this->faker->sentence,
        'transaction_at' => $this->faker->dateTimeThisYear,
        'amount' => $this->faker->randomFloat(3, 10000, 100000),
        'type' => $this->faker->randomElement([Transaction::TYPE_DEBIT,Transaction::TYPE_CREDIT]),
      ];
     
      return $data;
    }
}
