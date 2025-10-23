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
        Schema::table('assets', function (Blueprint $table) {
            // Drop old column
            $table->dropColumn('asset_name');

            // Add new columns
            $table->unsignedBigInteger('product_id')->nullable()->after('dairy_id');
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('set null');

            // Rename and modify columns
            $table->renameColumn('current_value', 'sold_price');

            // Add new fields
            $table->decimal('discount', 15, 2)->default(0)->after('sold_price');
            $table->string('invoice_refno')->nullable()->after('discount');
        });
    }

    public function down()
    {
        Schema::table('assets', function (Blueprint $table) {
            // Rollback new columns
            $table->dropForeign(['product_id']);
            $table->dropColumn(['product_id', 'discount', 'invoice_refno']);

            // Rename back to original
            $table->renameColumn('sold_price', 'current_value');

            // Re-add removed column
            $table->string('asset_name')->after('dairy_id');
        });
    }
};
