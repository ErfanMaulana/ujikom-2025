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
        Schema::create('revenue_sharings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->decimal('total_amount', 12, 2);
            $table->decimal('owner_share', 12, 2);
            $table->decimal('admin_share', 12, 2);
            $table->decimal('owner_percentage', 5, 2)->default(70.00);
            $table->decimal('admin_percentage', 5, 2)->default(30.00);
            $table->timestamp('settled_at')->nullable();
            $table->enum('status', ['pending', 'settled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenue_sharings');
    }
};
