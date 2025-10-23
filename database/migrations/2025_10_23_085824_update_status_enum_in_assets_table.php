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
            // Change status from string to ENUM
            $table->enum('status', ['available', 'sold', 'damaged', 'maintenance'])
                  ->default('available')
                  ->change();
        });
    }

    public function down()
    {
        Schema::table('assets', function (Blueprint $table) {
            // Revert back to string if you rollback
            $table->string('status')->default('available')->change();
        });
    }
};
