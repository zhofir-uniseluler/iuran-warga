<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Warga;
use App\utils\EnkripsiChaCha20; // Perhatikan penulisan namespace (Utils bukan utils)
use App\Models\KategoriPengeluaran;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $kunci = base64_decode(config('app.enkripsi_kunci'));

        // Create initial admin
        Admin::create([
            'nama' => 'Admin RT',
            'jabatan' => 'Ketua RT',
            'password' => Hash::make('password123'),
        ]);

        // Create initial warga dengan nomor HP terenkripsi
        Warga::create([
            'nama' => 'Muh',
            'no_hp' => EnkripsiChaCha20::enkripsi('089888789897', $kunci), // Nomor HP dienkripsi
            'no_rumah' => '12',
            'blok_rt' => 'C/03',
            'password' => Hash::make('Warga1'),
        ]);

        // Tambahkan data warga tambahan jika diperlukan
        Warga::create([
            'nama' => 'Warga Contoh 2',
            'no_hp' => EnkripsiChaCha20::enkripsi('081234567890', $kunci),
            'no_rumah' => '15',
            'blok_rt' => 'A/01',
            'password' => Hash::make('password123'),
        ]);

        // Seed kategori pengeluaran
        $kategoris = [
            ['nama' => 'Kebersihan', 'deskripsi' => 'Biaya kebersihan lingkungan'],
            ['nama' => 'Keamanan', 'deskripsi' => 'Biaya keamanan lingkungan'],
            ['nama' => 'Acara Warga', 'deskripsi' => 'Biaya kegiatan warga'],
            ['nama' => 'Perbaikan', 'deskripsi' => 'Biaya perbaikan fasilitas'],
            ['nama' => 'Lain-lain', 'deskripsi' => 'Biaya lainnya'],
        ];

        foreach ($kategoris as $kategori) {
            KategoriPengeluaran::create($kategori);
        }

        $this->command->info('Database seeded successfully!');
    }
}