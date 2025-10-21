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
    Schema::create('accounts', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('dairy_id');
        $table->unsignedBigInteger('fund_id')->nullable();
        $table->decimal('opening_balance', 15, 2)->default(0);
        $table->decimal('main_balance', 15, 2)->default(0);
        $table->timestamps();

        $table->foreign('dairy_id')
              ->references('id')
              ->on('dairies')
              ->onDelete('cascade');

        $table->foreign('fund_id')
              ->references('id')
              ->on('fund_allocations') // ✅ plural table name
              ->onDelete('set null');
    });
}


    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
