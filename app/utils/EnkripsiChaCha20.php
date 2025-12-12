<?php

namespace App\Utils;

use InvalidArgumentException;
use RuntimeException;

class EnkripsiChaCha20
{
    private const UKURAN_KUNCI = 32;   // 256-bit
    private const UKURAN_NONCE = 12;   // 96-bit (versi IETF)
    private const UKURAN_TAG = 16;     // 128-bit untuk Poly1305
    private const MINIMAL_PANJANG_DATA = self::UKURAN_NONCE + self::UKURAN_TAG;

    /**
     * Enkripsi data menggunakan ChaCha20-Poly1305
     * 
     * @param string $plaintext Data yang akan dienkripsi
     * @param string $kunciEnkripsi Kunci enkripsi 32-byte
     * @return string Data terenkripsi dalam format base64 (nonce + tag + ciphertext)
     * @throws InvalidArgumentException Jika kunci tidak valid
     * @throws RuntimeException Jika enkripsi gagal
     */
    public static function enkripsi(string $plaintext, string $kunciEnkripsi): string
    {
        self::validasiKunci($kunciEnkripsi);

        $nonce = random_bytes(self::UKURAN_NONCE);
        $tag = '';
        
        $ciphertext = openssl_encrypt(
            $plaintext,
            'chacha20-poly1305',
            $kunciEnkripsi,
            OPENSSL_RAW_DATA,
            $nonce,
            $tag
        );
        
        if ($ciphertext === false) {
            throw new RuntimeException('Enkripsi gagal: ' . openssl_error_string());
        }
        
        return base64_encode($nonce . $tag . $ciphertext);
    }

    /**
     * Dekripsi data menggunakan ChaCha20-Poly1305
     * 
     * @param string $dataTerenkripsi Data base64 yang terenkripsi
     * @param string $kunciEnkripsi Kunci enkripsi 32-byte
     * @return string Data asli
     * @throws InvalidArgumentException Jika input tidak valid
     * @throws RuntimeException Jika dekripsi gagal
     */
    public static function dekripsi(string $dataTerenkripsi, string $kunciEnkripsi): string
    {
        self::validasiKunci($kunciEnkripsi);

        $data = base64_decode($dataTerenkripsi, true);
        if ($data === false) {
            throw new InvalidArgumentException('Data base64 tidak valid');
        }
        
        if (strlen($data) < self::MINIMAL_PANJANG_DATA) {
            throw new InvalidArgumentException(sprintf(
                'Data terenkripsi terlalu pendek (minimal %d byte)',
                self::MINIMAL_PANJANG_DATA
            ));
        }
        
        $nonce = substr($data, 0, self::UKURAN_NONCE);
        $tag = substr($data, self::UKURAN_NONCE, self::UKURAN_TAG);
        $ciphertext = substr($data, self::UKURAN_NONCE + self::UKURAN_TAG);
        
        $plaintext = openssl_decrypt(
            $ciphertext,
            'chacha20-poly1305',
            $kunciEnkripsi,
            OPENSSL_RAW_DATA,
            $nonce,
            $tag
        );
        
        if ($plaintext === false) {
            throw new RuntimeException('Dekripsi gagal: ' . openssl_error_string());
        }
        
        return $plaintext;
    }

    /**
     * Membuat kunci enkripsi yang aman
     * 
     * @return string Kunci dalam format base64
     */
    public static function buatKunci(): string
    {
        return base64_encode(random_bytes(self::UKURAN_KUNCI));
    }

    /**
     * Validasi kunci enkripsi
     * 
     * @param string $kunci
     * @throws InvalidArgumentException Jika kunci tidak valid
     */
    private static function validasiKunci(string $kunci): void
    {
        if (strlen($kunci) !== self::UKURAN_KUNCI) {
            throw new InvalidArgumentException(sprintf(
                'Kunci enkripsi harus %d byte (%d diberikan)',
                self::UKURAN_KUNCI,
                strlen($kunci)
            ));
        }
    }
}