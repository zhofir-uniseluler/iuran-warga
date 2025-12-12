<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran';
    protected $fillable = ['no_pengeluaran', 'tanggal', 'jenis_pengeluaran', 'jumlah_pengeluaran', 'kategori_pengeluaran_id', 'admin_id'];

    public function kategoriPengeluaran()
    {
        return $this->belongsTo(KategoriPengeluaran::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}