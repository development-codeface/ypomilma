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
        Schema::table('products', function (Blueprint $table) {
            $table->string('item_code')->after('productname');
            $table->foreignId('category_id')->nullable()->after('vendor_id')->constrained('expense_categories')->onDelete('set null');
        });
    }

  
    
};
