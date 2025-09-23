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
        Schema::table('motors', function (Blueprint $table) {
            $table->string('model', 100)->after('brand')->nullable(); // Nama motor
            $table->year('year')->after('type_cc')->nullable(); // Tahun motor
            $table->string('color', 50)->after('year')->nullable(); // Warna motor
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('motors', function (Blueprint $table) {
            $table->dropColumn(['model', 'year', 'color']);
        });
    }
};
