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
    Schema::create('invoice_items', function (Blueprint $table) {
        $table->bigIncrements('id');

        // Foreign key to invoices table
       $table->string('invoice_id', 20);  // same length as invoices.id
      $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');

        // Item details
        $table->string('product_name');
        $table->integer('quantity')->default(1);
        $table->decimal('unit_price', 12, 2)->default(0);

        // --- GST Fields ---
        $table->decimal('gst_percent', 5, 2)->default(0)->comment('GST percentage');
        $table->decimal('gst_amount', 12, 2)->default(0)->comment('GST amount for this item');

        // --- Discount & Calculated Values ---
        $table->decimal('discount', 12, 2)->default(0);
        $table->decimal('taxable_value', 12, 2)->default(0)->comment('Amount before GST and after discount');
        $table->decimal('total', 12, 2)->default(0)->comment('Final total including GST');

        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
