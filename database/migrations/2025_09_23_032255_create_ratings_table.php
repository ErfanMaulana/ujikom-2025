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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('motor_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('set null');
            $table->tinyInteger('rating')->unsigned()->comment('Rating 1-5 stars');
            $table->text('review')->nullable()->comment('Optional review text');
            $table->enum('rating_type', ['condition', 'service', 'overall'])->default('overall');
            $table->boolean('is_verified')->default(false)->comment('Verified purchase rating');
            $table->timestamps();
            
            // Indexes
            $table->index(['motor_id', 'rating']);
            $table->index(['user_id', 'created_at']);
            
            // Unique constraint: one rating per user per motor per booking
            $table->unique(['user_id', 'motor_id', 'booking_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
