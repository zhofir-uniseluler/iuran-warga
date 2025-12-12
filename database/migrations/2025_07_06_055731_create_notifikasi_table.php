<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('status'); // menunggu/diterima/ditolak
            $table->text('pesan');
            
            // Polymorphic relationship columns
            $table->unsignedBigInteger('notifiable_id');
            $table->string('notifiable_type');
            
            // Foreign keys - adjusted to match your admin table
            $table->unsignedBigInteger('warga_id');
            $table->unsignedBigInteger('admin_id')->nullable();
            
            $table->timestamps();
            
            // Add foreign key constraints - note your admin table is singular 'admin'
            $table->foreign('warga_id')
                  ->references('id')
                  ->on('warga')
                  ->onDelete('cascade');
                  
            $table->foreign('admin_id')
                  ->references('id')
                  ->on('admin')  // Changed from 'admins' to 'admin' to match your table
                  ->onDelete('set null');
            
            // Indexes
            $table->index(['notifiable_id', 'notifiable_type']);
        });
    }

    public function down()
    {
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->dropForeign(['warga_id']);
            $table->dropForeign(['admin_id']);
        });
        
        Schema::dropIfExists('notifikasi');
    }
};