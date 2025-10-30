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
        Schema::table('assets', function (Blueprint $table) {
            $table->unsignedBigInteger('invoice_items_id')->nullable()->after('id');

            // Optional: Add a foreign key constraint (if related to another table)
            $table->foreign('invoice_items_id')
                ->references('id')
                ->on('invoice_items')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
              $table->dropForeign(['invoice_items_id']);
            $table->dropColumn('invoice_items_id');
        });
    }
};
