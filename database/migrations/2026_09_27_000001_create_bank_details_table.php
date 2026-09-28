<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bank_details', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name', 150);
            $table->string('account_name', 150)->nullable();
            $table->string('account_number', 50);
            $table->string('ifsc', 20)->nullable();
            $table->string('swift', 20)->nullable();
            $table->string('branch', 255)->nullable();
            $table->string('upi_id', 100)->nullable();
            $table->string('upi_qr_path', 255)->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed the bank account that was previously hard-coded in the invoice PDF,
        // so existing invoices keep printing the same details.
        $defaultBankId = DB::table('bank_details')->insertGetId([
            'bank_name' => 'Kotak Mahindra Bank',
            'account_number' => '6450907494',
            'ifsc' => 'KKBK0008045',
            'swift' => 'KKBKINBBCPC',
            'branch' => 'Sahakara Nagar, Bengaluru - 560092',
            'upi_id' => '9611570671@kotak',
            'upi_qr_path' => 'images/upi_qr.png',
            'is_default' => true,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('bank_detail_id')->nullable()->after('bank');
        });

        // Existing invoices always printed the Kotak details regardless of the old
        // "bank" dropdown value, so link them to that account.
        DB::table('invoices')->update([
            'bank_detail_id' => $defaultBankId,
            'bank' => 'Kotak Mahindra Bank',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('bank_detail_id');
        });

        Schema::dropIfExists('bank_details');
    }
};
