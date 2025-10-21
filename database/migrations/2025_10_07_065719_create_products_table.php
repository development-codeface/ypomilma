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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('productname');
            $table->string('img')->nullable();
            $table->text('description');
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');  // Foreign key referencing the vendors table
            $table->string('brand');
            $table->string('model');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
