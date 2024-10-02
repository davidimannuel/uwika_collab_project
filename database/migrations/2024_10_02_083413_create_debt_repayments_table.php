<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('debt_repayments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('debt_id');
            $table->foreignId('transaction_id'); // repayment transaction
            $table->foreign('debt_id')->references('id')->on('debts');
            $table->foreign('transaction_id')->references('id')->on('transactions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debt_repayments');
    }
};
