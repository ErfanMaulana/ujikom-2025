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
        // Update the enum field to include more CC options
        DB::statement("ALTER TABLE motors MODIFY COLUMN type_cc ENUM('100cc', '125cc', '150cc', '250cc', '500cc')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE motors MODIFY COLUMN type_cc ENUM('100cc', '125cc', '150cc')");
    }
};
