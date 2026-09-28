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
        if (!Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('version')->nullable();
                $table->string('taxsch')->nullable();
                $table->string('stype')->nullable();
                $table->string('category')->nullable();
                $table->string('invoice_category')->nullable();
                $table->string('invoice_type')->nullable();
                $table->string('irn')->nullable();
                $table->string('booking_no')->nullable();
                $table->string('proforma_invoice_no')->nullable();
                $table->date('proforma_invoice_date')->nullable();
                $table->string('billno')->nullable()->index();
                $table->date('billdate')->nullable();
                $table->string('credit_note_no')->nullable();
                $table->date('credit_note_date')->nullable();
                $table->string('shipper_invoice')->nullable();
                $table->text('shipper_consignee')->nullable();
                $table->string('acode')->nullable()->index();
                $table->string('company_name')->nullable();
                $table->string('aline1')->nullable();
                $table->string('aline2')->nullable();
                $table->string('location')->nullable();
                $table->string('pincode')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('gst_no')->nullable();
                $table->string('pan')->nullable();
                $table->string('state')->nullable();
                $table->string('state_code')->nullable();
                $table->string('po_supply')->nullable();
                $table->string('guarantee_l1')->nullable();
                $table->string('guarantee_l2')->nullable();
                $table->string('guarantee_l3')->nullable();
                $table->string('guarantee_l4')->nullable();
                $table->decimal('total_non_tax', 12, 2)->nullable()->default(0.00);
                $table->decimal('total_tax', 12, 2)->nullable()->default(0.00);
                $table->decimal('sub_total', 12, 2)->nullable()->default(0.00);
                $table->decimal('igst_value', 12, 2)->nullable()->default(0.00);
                $table->decimal('sgst_value', 12, 2)->nullable()->default(0.00);
                $table->decimal('cgst_value', 12, 2)->nullable()->default(0.00);
                $table->decimal('total', 14, 2)->nullable()->default(0.00);
                $table->decimal('total_non_inr', 12, 2)->nullable()->default(0.00);
                $table->decimal('round_off', 5, 2)->nullable()->default(0.00);
                $table->decimal('grand_total', 14, 2)->nullable()->default(0.00);
                $table->decimal('advance', 12, 2)->nullable()->default(0.00);
                $table->decimal('balance', 14, 2)->nullable()->default(0.00);
                $table->string('status')->nullable()->default('UnPaid');
                $table->string('currency')->nullable()->default('INR');
                $table->decimal('ex_rate', 10, 4)->nullable()->default(1.0000);
                $table->text('remarks')->nullable();
                $table->string('month')->nullable();
                $table->string('year')->nullable();
                $table->date('exten_date')->nullable();
                $table->date('due_date')->nullable();
                $table->integer('credit_days')->nullable();
                $table->string('bank')->nullable();
                $table->string('hcode')->nullable();
                $table->decimal('total_expense', 12, 2)->nullable()->default(0.00);
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
