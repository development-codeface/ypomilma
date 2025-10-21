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
        Schema::create('dairies', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('name', 150); // Dairy Name
            $table->string('location', 255); // Dairy Location
            $table->string('admin_userid', 100); // Admin Name
            $table->string('phone', 20); // Phone Number
            $table->timestamps(); // created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('dairies');
    }
};
