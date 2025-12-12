<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $table = 'admin';
    protected $fillable = ['nama', 'jabatan', 'password'];
    protected $hidden = ['password'];

    public function iuranCash()
    {
        return $this->hasMany(IuranCash::class);
    }

    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class);
    }

     public function iuranTransfer()
    {
        return $this->hasMany(IuranTransfer::class);
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }
}