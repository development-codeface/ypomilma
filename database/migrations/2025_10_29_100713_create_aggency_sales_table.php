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
        Schema::create('aggency_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_id')->nullable();
            $table->foreign('asset_id')
                ->references('id')
                ->on('assets')
                ->onDelete('set null');
            $table->string('invoice_id')->nullable();
            $table->string('name')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('address')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aggency_sales');
    }
};
