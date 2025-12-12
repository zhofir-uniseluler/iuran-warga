<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';
    protected $fillable = [
        'tanggal', 
        'status', 
        'pesan', 
        'warga_id', 
        'admin_id',
        'notifiable_id',
        'notifiable_type'
    ];

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function warga()
    {
        return $this->belongsTo(Warga::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}