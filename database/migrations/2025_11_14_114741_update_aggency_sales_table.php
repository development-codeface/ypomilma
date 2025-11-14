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
            $table->dropColumn(['name', 'contact_no', 'address']);
            // Add new column
            $table->unsignedBigInteger('agency_id')->nullable()->after('id');
            $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aggency_sales', function (Blueprint $table) {
            //
            $table->string('name')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('address')->nullable();

            // Remove agency_id
            $table->dropColumn('agency_id');
        });
    }
};
