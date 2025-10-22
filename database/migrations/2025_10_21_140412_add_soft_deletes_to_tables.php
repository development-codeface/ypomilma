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
        Schema::table('expense_categories', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('dairies', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('vendors', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('expense_category', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('dairy', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('product', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('vendors', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
