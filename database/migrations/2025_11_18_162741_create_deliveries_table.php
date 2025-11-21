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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('delivery_no')->unique(); // e.g. DEL00001 or INV00004-1
            $table->string('invoice_id'); // stores invoice id format (INV00004)
            $table->unsignedBigInteger('created_by')->nullable();
            $table->date('delivery_date')->nullable();
            $table->text('notes')->nullable(); // general notes for the delivery
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
};

