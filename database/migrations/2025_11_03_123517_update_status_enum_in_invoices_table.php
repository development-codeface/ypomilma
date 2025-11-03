<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Update any existing invalid or null statuses before altering column
        DB::table('invoices')
            ->whereNull('status')
            ->orWhereNotIn('status', ['pending', 'delivered', 'cancelled'])
            ->update(['status' => 'pending']);

        // Step 2: Modify the enum column definition
        Schema::table('invoices', function () {
            DB::statement("
                ALTER TABLE invoices
                MODIFY COLUMN status ENUM('pending', 'delivered', 'cancelled')
                DEFAULT 'pending'
            ");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Convert any 'pending' statuses back to 'approved' before rollback
        DB::table('invoices')
            ->where('status', 'pending')
            ->update(['status' => 'approved']);

        // Step 2: Revert the column to its original enum
        Schema::table('invoices', function () {
            DB::statement("
                ALTER TABLE invoices
                MODIFY COLUMN status ENUM('approved', 'delivered', 'cancelled')
                DEFAULT 'approved'
            ");
        });
    }
};
