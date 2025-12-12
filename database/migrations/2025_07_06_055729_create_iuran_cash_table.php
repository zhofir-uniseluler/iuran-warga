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
        Schema::create('iuran_cash', function (Blueprint $table) {
    $table->id();
    $table->date('tgl');
    $table->decimal('nominal', 12, 2);
    $table->foreignId('warga_id')->constrained('warga');
    $table->foreignId('admin_id')->constrained('admin');
    $table->string('jenis_iuran');
    $table->string('metode_pembayaran');
    $table->string('status');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iuran_cash');
    }
};
