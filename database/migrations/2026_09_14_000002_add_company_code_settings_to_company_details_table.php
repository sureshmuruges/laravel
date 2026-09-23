<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_details', function (Blueprint $table) {
            $table->string('company_code_prefix', 10)->default('CEH')->after('is_active');
            $table->boolean('company_code_enabled')->default(false)->after('company_code_prefix');
        });
    }

    public function down(): void
    {
        Schema::table('company_details', function (Blueprint $table) {
            $table->dropColumn(['company_code_prefix', 'company_code_enabled']);
        });
    }
};
