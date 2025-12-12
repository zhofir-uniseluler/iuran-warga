<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IuranCash extends Model
{
    use HasFactory;

    protected $table = 'iuran_cash';
    protected $fillable = [
    'admin_id',
    'warga_id', 
    'jenis_iuran', 
    'nominal', 
    'metode_pembayaran',
    'tgl',
    'status',
    ];
    
    public function warga()
    {
        return $this->belongsTo(Warga::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function kwitansi()
    {
        return $this->hasOne(Kwitansi::class);
    }
}