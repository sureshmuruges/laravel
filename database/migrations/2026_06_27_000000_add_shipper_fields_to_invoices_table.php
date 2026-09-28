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
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'shipper_invoice')) {
                $table->string('shipper_invoice')->nullable();
            }
            if (!Schema::hasColumn('invoices', 'shipper_consignee')) {
                $table->text('shipper_consignee')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['shipper_invoice', 'shipper_consignee']);
        });
    }
};
