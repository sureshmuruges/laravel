<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // IRN (Invoice Reference Number) from the GST e-invoice system is always a fixed 64-character hash.
        DB::statement("ALTER TABLE invoices MODIFY irn VARCHAR(64) NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE invoices MODIFY irn VARCHAR(255) NULL");
    }
};
