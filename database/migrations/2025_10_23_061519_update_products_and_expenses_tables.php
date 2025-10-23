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
        // ✅ Make vendor_id nullable in products table
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('vendor_id')->nullable()->change();
        });

        // ✅ Modify expenses table
        Schema::table('expenses', function (Blueprint $table) {
            // Add new columns
            $table->unsignedBigInteger('product_id')->nullable()->after('id');
            $table->decimal('rate', 15, 2)->nullable()->after('product_id');
            $table->integer('quantity')->nullable()->after('rate');

            // Make fund_id nullable
            $table->unsignedBigInteger('fund_id')->nullable()->change();

            // Add foreign key for product_id
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        // Rollback changes
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('vendor_id')->nullable(false)->change();
        });

        Schema::table('expenses', function (Blueprint $table) {
            // Drop new columns
            $table->dropForeign(['product_id']);
            $table->dropColumn(['product_id', 'rate', 'qty']);

            // Revert fund_id back to non-nullable
            $table->unsignedBigInteger('fund_id')->nullable(false)->change();
        });
    }
};
