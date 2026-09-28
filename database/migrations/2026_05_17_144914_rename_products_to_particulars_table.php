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
        Schema::rename('products', 'particulars');
        Schema::table('particulars', function (Blueprint $table) {
            $table->dropColumn('c_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('particulars', function (Blueprint $table) {
            $table->string('c_code')->nullable();
        });
        Schema::rename('particulars', 'products');
    }
};
