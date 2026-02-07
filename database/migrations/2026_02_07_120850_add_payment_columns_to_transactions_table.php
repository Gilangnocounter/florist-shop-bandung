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
    Schema::table('transactions', function (Blueprint $table) {
        // Menambahkan kolom baru setelah kolom 'status'
        $table->string('bank_name')->nullable()->after('status');
        $table->string('payment_proof')->nullable()->after('bank_name');
    });
}

public function down(): void
{
    Schema::table('transactions', function (Blueprint $table) {
        $table->dropColumn(['bank_name', 'payment_proof']);
    });
}
};
