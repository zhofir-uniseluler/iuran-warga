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
        Schema::create('pengeluaran', function (Blueprint $table) {
    $table->id();
    $table->string('no_pengeluaran')->unique();
    $table->date('tanggal');
    $table->string('jenis_pengeluaran');
    $table->decimal('jumlah_pengeluaran', 12, 2);
    $table->foreignId('kategori_pengeluaran_id')->constrained('kategori_pengeluaran');
    $table->foreignId('admin_id')->constrained('admin');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');
    }
};
