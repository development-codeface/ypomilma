<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('fund_allocations', function (Blueprint $table) {
            $table->id(); // Allocation ID
            $table->foreignId('dairy_id')->constrained('dairies')->onDelete('cascade'); // Dairy receiving funds
            $table->decimal('amount', 15, 2); // Amount allocated
            $table->date('allocation_date');
            $table->string('financial_year');
            $table->text('remarks')->nullable();
            $table->enum('status', ['approved', 'pending', 'rejected'])->default('approved');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fund_allocations');
    }
};
