<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IuranTransfer extends Model
{
    use HasFactory;

    protected $table = 'iuran_transfer';
    protected $fillable = [
        'tanggal', 
        'nominal', 
        'bukti_transfer', 
        'warga_id',
        'status' // Kolom baru
    ];

    // Default nilai status
    protected $attributes = [
        'status' => 'menunggu',
    ];

    // Relasi ke warga
    public function warga()
    {
        return $this->belongsTo(Warga::class);
    }

    // Relasi ke notifikasi (opsional)
    public function notifikasi()
{
    return $this->morphOne(Notifikasi::class, 'notifiable');
}
}