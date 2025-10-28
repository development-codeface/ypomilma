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
        Schema::table('expenses', function (Blueprint $table) {
            // Drop foreign key on product_id
            $table->dropForeign(['product_id']); // Ensure you replace this with the actual foreign key name if needed.

            // Add new expense_item column
            $table->string('expense_item', 255)->nullable()->after('dairy_id');

            // Add is_head_office column with default value 0
            $table->boolean('is_head_office')->default(0)->after('expense_item');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('expenses', function (Blueprint $table) {
            // Rollback changes

            // Add the foreign key back if needed
            // $table->foreign('product_id')->references('id')->on('products'); // Adjust as necessary.

            // Drop the new columns
            $table->dropColumn('expense_item');
            $table->dropColumn('is_head_office');
        });
    }
};
