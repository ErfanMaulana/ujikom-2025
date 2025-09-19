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
        Schema::create('rental_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('motor_id')->constrained('motors')->onDelete('cascade');
            $table->decimal('daily_rate', 12, 2);
            $table->decimal('weekly_rate', 12, 2);
            $table->decimal('monthly_rate', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_rates');
    }
};
