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
        Schema::create('delivery_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('delivery_id');
            $table->unsignedBigInteger('invoice_item_id'); // FK to invoice_items.id
            $table->unsignedBigInteger('product_id');
            $table->integer('delivered_quantity')->default(0);
            $table->string('warranty')->nullable(); // e.g. '12 months'
            $table->text('description')->nullable(); // additional details
            $table->timestamps();

            $table->foreign('delivery_id')->references('id')->on('deliveries')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_items');
    }
};
