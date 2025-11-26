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
        Schema::create('aggency_bill_units', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('aggency_bill_id');
        $table->string('serial_no')->nullable();
        $table->string('brand')->nullable();
        $table->string('model')->nullable();
        $table->string('warranty')->nullable(); // e.g. "12 months" or "2025-12-31"
        $table->text('description')->nullable(); // extra notes if needed
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aggency_bill_units');
    }
};
