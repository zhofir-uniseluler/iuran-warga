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
        Schema::create('kwitansi', function (Blueprint $table) {
    $table->id();
    $table->string('no_kwitansi')->unique();
    $table->decimal('nominal', 12, 2);
    $table->date('tanggal');
    $table->foreignId('iuran_cash_id')->constrained('iuran_cash');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kwitansi');
    }

    
};
