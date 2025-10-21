<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
   public function up(): void
{
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('dairy_id');
        $table->unsignedBigInteger('fund_allocation_id')->nullable();
        $table->unsignedBigInteger('expense_category_id')->nullable();
        $table->enum('type', ['credit', 'debit', 'hold', 'refund']);
        $table->decimal('amount', 15, 2);
        $table->string('reference_no', 100)->nullable();
        $table->text('description')->nullable();
        $table->enum('status', ['pending', 'completed', 'cancelled'])->default('completed');
        $table->dateTime('transaction_date');
        $table->timestamps();

        $table->foreign('dairy_id')
              ->references('id')
              ->on('dairies')
              ->onDelete('cascade');

        $table->foreign('fund_allocation_id')
              ->references('id')
              ->on('fund_allocations') // ✅ fixed plural
              ->onDelete('set null');

        $table->foreign('expense_category_id')
              ->references('id')
              ->on('expense_categories') // ✅ fixed plural
              ->onDelete('set null');
    });
}


    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

