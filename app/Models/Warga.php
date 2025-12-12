<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use App\utils\EnkripsiChaCha20;

class Warga extends Authenticatable
{
    use HasFactory;

    protected $table = 'warga';
    protected $fillable = [
        'nama', 
        'no_hp',
        'no_rumah',
        'blok_rt',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'no_hp' // Sembunyikan no_hp asli dari serialization
    ];

    protected $appends = ['no_hp_masked', 'no_hp_decrypted'];

    private static $encryptionKey;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        self::$encryptionKey = base64_decode(config('app.enkripsi_kunci'));
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!empty($model->no_hp)) {
                // Skip jika sudah terenkripsi (deteksi base64)
                if (base64_decode($model->no_hp, true) === false) {
                    $model->no_hp = EnkripsiChaCha20::enkripsi($model->no_hp, self::$encryptionKey);
                }
            }
        });
    }

    /**
     * Accessor untuk menampilkan nomor HP yang di-mask (hanya 4 digit terakhir)
     */
    public function getNoHpMaskedAttribute()
    {
        if (empty($this->no_hp)) {
            return null;
        }

        try {
            $decrypted = $this->no_hp_decrypted;
            return $decrypted ? '••••••••'.substr($decrypted, -4) : '••••••••';
        } catch (\Exception $e) {
            \Log::error("Error masking phone for warga {$this->id}: " . $e->getMessage());
            return '••••••••';
        }
    }

    /**
     * Accessor untuk nomor HP terdekripsi (hanya untuk admin)
     */
    public function getNoHpDecryptedAttribute()
    {
        if (!auth('admin')->check()) {
            return null;
        }

        try {
            if (empty($this->no_hp)) {
                return null;
            }
            
            $kunci = base64_decode(config('app.enkripsi_kunci'));
            return EnkripsiChaCha20::dekripsi($this->no_hp, $kunci);
        } catch (\Exception $e) {
            \Log::error("Dekripsi gagal untuk warga {$this->id}: " . $e->getMessage());
            return 'Error: Nomor tidak dapat ditampilkan';
        }
    }

    /**
     * Method khusus untuk login warga
     */
    public static function attemptLogin($no_hp, $password)
    {
        $kunci = base64_decode(config('app.enkripsi_kunci'));
        
        // Ambil semua warga (tetap seperti kode asli jika ada alasan khusus)
        $allWargas = Warga::all();
        
        foreach ($allWargas as $warga) {
            try {
                $decryptedNoHP = EnkripsiChaCha20::dekripsi($warga->no_hp, $kunci);
                
                if ($decryptedNoHP === $no_hp && Hash::check($password, $warga->password)) {
                    return $warga;
                }
            } catch (\Exception $e) {
                // Log error untuk debugging
                \Log::error("Gagal dekripsi untuk warga ID {$warga->id}: " . $e->getMessage());
                continue;
            }
        }
        
        return null;
    }

    // Relasi-relasi
    public function iuranCash()
    {
        return $this->hasMany(IuranCash::class);
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