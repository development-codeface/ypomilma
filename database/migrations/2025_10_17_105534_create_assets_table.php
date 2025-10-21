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
    Schema::create('assets', function (Blueprint $table) {
        $table->bigIncrements('id');

        // Foreign key to dairies table
        $table->foreignId('dairy_id')
              ->constrained('dairies') // ✅ ensure plural table name
              ->onDelete('cascade');

        $table->string('asset_name');
        $table->decimal('purchase_value', 15, 2)->default(0);
        $table->date('purchase_date')->nullable();
        $table->decimal('current_value', 15, 2)->default(0);
        $table->string('status')->default('available'); // available, sold, damaged, etc.
        $table->text('remarks')->nullable();

        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
