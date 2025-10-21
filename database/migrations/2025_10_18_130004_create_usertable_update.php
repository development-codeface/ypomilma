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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_blocked')->default(false); // Add blocked status
            $table->foreignId('region_id')->nullable()->constrained('regions')->onDelete('set null'); // Add region relation
            $table->softDeletes(); // Add deleted_at column
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_blocked');
            $table->dropForeign(['region_id']);
            $table->dropColumn('deleted_at');
        });
    }
};
