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
        Schema::create('transactions', function (Blueprint $table) {
          $table->id();
          $table->foreignId('account_id');
          $table->string('remark');
          $table->string('type');
          $table->decimal('amount', total: 20, places: 3)->default(0);
          $table->timestamp('transaction_at');
          $table->timestamps();
          $table->foreign('account_id')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
