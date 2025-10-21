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
        Schema::create('invoices', function (Blueprint $table) {
            $table->string('id', 20)->primary(); // Custom formatted ID (e.g., INV0001)
            $table->unsignedBigInteger('dairy_id');
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->enum('status', ['approved', 'delivered',  'cancelled'])->default('approved');
            $table->timestamps();

            $table->foreign('dairy_id')->references('id')->on('dairies')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
    
};
