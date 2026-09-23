<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_details', function (Blueprint $table) {
            // PDF layout settings
            $table->string('pdf_logo_address_layout', 30)->default('logo_left_address_right')->after('company_code_enabled');
            $table->unsignedInteger('pdf_line_items_rows')->default(10)->after('pdf_logo_address_layout');
            $table->text('terms_conditions')->nullable()->after('pdf_line_items_rows');
            $table->boolean('irn_qr_enabled')->default(true)->after('terms_conditions');

            // Code / numbering prefix settings
            $table->string('booking_code_prefix', 20)->nullable()->after('irn_qr_enabled');
            $table->string('tax_invoice_prefix', 20)->default('CSHL')->after('booking_code_prefix');
        });
    }

    public function down(): void
    {
        Schema::table('company_details', function (Blueprint $table) {
            $table->dropColumn([
                'pdf_logo_address_layout',
                'pdf_line_items_rows',
                'terms_conditions',
                'irn_qr_enabled',
                'booking_code_prefix',
                'tax_invoice_prefix',
            ]);
        });
    }
};
