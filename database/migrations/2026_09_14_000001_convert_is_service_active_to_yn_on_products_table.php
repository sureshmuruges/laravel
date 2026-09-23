<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Widen the columns to hold 'Y'/'N' text while keeping the existing 0/1 values intact.
        DB::statement("ALTER TABLE particulars MODIFY is_service VARCHAR(1) NOT NULL DEFAULT 'Y'");
        DB::statement("ALTER TABLE particulars MODIFY active VARCHAR(1) NOT NULL DEFAULT 'Y'");

        // Convert previously stored boolean values (1/0) into 'Y'/'N'.
        DB::statement("UPDATE particulars SET is_service = CASE WHEN is_service = '1' THEN 'Y' ELSE 'N' END");
        DB::statement("UPDATE particulars SET active = CASE WHEN active = '1' THEN 'Y' ELSE 'N' END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE particulars SET is_service = CASE WHEN is_service = 'Y' THEN '1' ELSE '0' END");
        DB::statement("UPDATE particulars SET active = CASE WHEN active = 'Y' THEN '1' ELSE '0' END");

        DB::statement("ALTER TABLE particulars MODIFY is_service TINYINT(1) NOT NULL DEFAULT 1");
        DB::statement("ALTER TABLE particulars MODIFY active TINYINT(1) NOT NULL DEFAULT 1");
    }
};
