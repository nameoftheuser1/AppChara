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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_key', 50)->unique();
            $table->string('name', 100);
            $table->string('contact_number', 15);
            $table->string('coupon')->nullable();
            $table->date('pick_up_date');
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
