<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('iuran_transfer', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->decimal('nominal', 12, 2); // Menggunakan decimal untuk presisi nilai uang
            $table->string('bukti_transfer'); // Path/lokasi file bukti transfer
            $table->enum('status', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');
            $table->foreignId('warga_id')->constrained('warga')->onDelete('cascade');
            $table->timestamps();
            
            // Index untuk kolom yang sering di-query
            $table->index('tanggal');
            $table->index('status');
            $table->index('warga_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('iuran_transfer');
    }
};