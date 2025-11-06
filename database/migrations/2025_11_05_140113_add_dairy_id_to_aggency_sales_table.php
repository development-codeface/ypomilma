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
        Schema::table('aggency_sales', function (Blueprint $table) {
            $table->unsignedBigInteger('dairy_id')->nullable()->after('id'); // add after id or wherever you prefer

            // If there's a dairies table and you want to link it as a foreign key:
            $table->foreign('dairy_id')->references('id')->on('dairies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aggency_sales', function (Blueprint $table) {
            $table->dropForeign(['dairy_id']);
            $table->dropColumn('dairy_id');
        });
    }
};
