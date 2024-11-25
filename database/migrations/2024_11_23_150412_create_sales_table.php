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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('amount_received', 10, 2);  // Added field to capture the amount received
            $table->string('status')->default('completed');  // Added status field to track sale state
            $table->timestamp('sale_date')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
