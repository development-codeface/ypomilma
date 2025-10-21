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
        Schema::create('expenses', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('dairy_id');
        $table->unsignedBigInteger('fund_id');
        $table->unsignedBigInteger('expensecategory_id');
        $table->decimal('amount', 15, 2)->default(0);
        $table->text('description')->nullable();
        $table->timestamps();

        $table->foreign('dairy_id')
            ->references('id')
            ->on('dairies')
            ->onDelete('cascade');

        $table->foreign('fund_id')
            ->references('id')
            ->on('fund_allocations') // ✅ fixed plural form
            ->onDelete('cascade');

        $table->foreign('expensecategory_id')
            ->references('id')
            ->on('expense_categories') // ✅ fixed plural form
            ->onDelete('cascade');
    });

    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
