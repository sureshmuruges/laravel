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
        if (!Schema::hasTable('invoice_particulars')) {
            Schema::create('invoice_particulars', function (Blueprint $table) {
                $table->increments('Id');
                $table->string('CCode', 10)->nullable()->index();
                $table->string('InvoiceType', 50)->nullable()->index();
                $table->string('ProformaInvoiceNo', 50)->nullable()->index();
                $table->string('BillNo', 50)->nullable()->index();
                $table->string('CreditNoteNo', 50)->nullable()->index();
                $table->string('HSN', 50)->nullable();
                $table->string('Particulars', 200)->nullable();
                $table->string('Additional', 200)->nullable();
                $table->string('Additional_2', 200)->nullable();
                $table->decimal('Quantity', 10, 2)->nullable();
                $table->decimal('Rate', 12, 2)->nullable();
                $table->decimal('NonTaxAmount', 12, 2)->nullable()->default(0.00);
                $table->decimal('NonTaxAmt_NonINR', 12, 2)->nullable()->default(0.00);
                $table->decimal('TaxAmount', 12, 2)->nullable()->default(0.00);
                $table->decimal('IGST', 5, 2)->nullable();
                $table->decimal('IGSTValue', 12, 2)->nullable()->default(0.00);
                $table->decimal('SGST', 5, 2)->nullable();
                $table->decimal('SGSTValue', 12, 2)->nullable()->default(0.00);
                $table->decimal('CGST', 5, 2)->nullable();
                $table->decimal('CGSTValue', 12, 2)->nullable()->default(0.00);
                $table->decimal('Total', 14, 2)->nullable()->default(0.00);
                $table->string('IsService', 3)->nullable();
                $table->string('ExceptionalParticulars', 3)->nullable();
                $table->string('Month', 50)->nullable();
                $table->string('Year', 50)->nullable();
                $table->dateTime('CreateDate')->nullable();
                $table->string('CreateBy', 100)->nullable();
                $table->dateTime('ModifyDate')->nullable();
                $table->string('ModifyBy', 100)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_particulars');
    }
};
