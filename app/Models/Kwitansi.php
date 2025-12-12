<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kwitansi extends Model
{
    use HasFactory;

    protected $table = 'kwitansi';
    protected $fillable = ['no_kwitansi', 'nominal', 'tanggal', 'iuran_cash_id'];

    public function iuranCash()
    {
        return $this->belongsTo(IuranCash::class);
    }

    // In Kwitansi.php
public function notifikasi()
{
    return $this->morphOne(Notifikasi::class, 'notifiable');
}
}