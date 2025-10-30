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
        Schema::create('aggency_bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aggency_sale_id')->nullable();
            $table->foreign('aggency_sale_id')
                ->references('id')
                ->on('aggency_sales')
                ->onDelete('set null');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('set null');
            $table->integer('quantity')->nullable();
            $table->integer('price')->nullable();
            $table->decimal('discount', 12, 2)->default(0);
            // --- GST Fields ---
            $table->decimal('gst_percent', 5, 2)->default(0)->comment('GST percentage');
            $table->decimal('gst_amount', 12, 2)->default(0)->comment('GST amount for this item');
            $table->string('tax_type')->nullable();
            $table->decimal('total', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aggency_bills');
    }
};
