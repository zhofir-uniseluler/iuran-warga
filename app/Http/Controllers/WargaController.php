<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IuranCash;
use App\Models\IuranTransfer;
use App\Models\Notifikasi;
use App\Models\Kwitansi;
use App\Models\Warga;
use App\utils\EnkripsiChaCha20;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WargaController extends Controller
{
    public function dashboard()
    {
        $warga = auth('warga')->user();
        
        // Get transfer data with actual status
        $iuranTerakhir = IuranTransfer::where('warga_id', $warga->id)
            ->select('tanggal', 'nominal as jumlah', DB::raw("'transfer' as jenis"), 'status')
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        // Get notifications with polymorphic relationship
        $notifikasi = Notifikasi::where('warga_id', $warga->id)
            ->with(['notifiable'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('warga.dashboard', [
        'iuranTerakhir' => $iuranTerakhir,
        'notifikasi' => $notifikasi,
        'warga' => $warga // Kirim data warga untuk akses no_hp (sudah dimasking)
        ]);
    }

    public function index()
    {
        $warga = Warga::orderBy('created_at', 'desc')->get();
        return view('admin.data_warga', compact('warga'));
    }

    public function create()
{
    return view('admin.warga.create');
}

public function store(Request $request)
{
    $request->validate([
        'nama' => 'required',
        'no_hp' => 'required',
        'no_rumah' => 'required',
        'blok_rt' => 'required',
        'total_warga' => 'required|numeric|min:1'
    ]);

    Warga::create($request->all());

    return redirect()->route('admin.data_warga')->with('success', 'Data warga berhasil ditambahkan');
}

    public function iuranTransfer()
    {
        $warga = auth('warga')->user();
        
        $iuranTransfer = $warga->iuranTransfer()
            ->with(['notifikasi' => function($query) {
                $query->latest()->first();
            }])
            ->latest()
            ->get();

        return view('warga.iuran_transfer', compact('iuranTransfer'));
    }

    public function createTransfer()
    {
        return view('warga.create_transfer');
    }

    public function storeTransfer(Request $request)
{
    $request->validate([
        'nominal' => 'required|numeric|min:1000',
        'tanggal' => 'required|date',
        'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

    $transfer = auth('warga')->user()->iuranTransfer()->create([
        'tanggal' => $request->tanggal,
        'nominal' => $request->nominal,
        'bukti_transfer' => $path,
        'status' => 'menunggu'
    ]);

    // Create notification using polymorphic relationship
    $notifikasi = new Notifikasi([
        'tanggal' => now(),
        'status' => 'menunggu',
        'pesan' => 'Pembayaran transfer sebesar Rp '.number_format($transfer->nominal, 0, ',','.').' sedang menunggu verifikasi',
        'warga_id' => auth('warga')->id(),
    ]);

    $transfer->notifikasi()->save($notifikasi);

    return redirect()->route('warga.iuran.transfer')
           ->with('success', 'Bukti transfer berhasil diupload. Menunggu verifikasi admin.');
}
    public function notifikasi()
    {
        $notifikasi = auth('warga')->user()
            ->notifikasi()
            ->with(['notifiable'])
            ->latest()
            ->get();
                      
        return view('warga.notifikasi', compact('notifikasi'));
    }

    public function downloadKwitansi($id)
    {
        $kwitansi = Kwitansi::findOrFail($id);
        
        // Verify kwitansi ownership
        if ($kwitansi->iuranCash->warga_id !== auth('warga')->id()) {
            abort(403);
        }

        $pdf = PDF::loadView('warga.kwitansi_pdf', compact('kwitansi'));
        return $pdf->download('kwitansi-' . $kwitansi->no_kwitansi . '.pdf');
    }

    public function simpanWarga(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'no_rumah' => 'required|string|max:10',
            'blok_rt' => 'required|string|max:10'
        ]);

        try {
            $kunci = base64_decode(config('app.enkripsi_kunci'));
            $nomorHP = $request->input('no_hp');
            
            // Enkripsi nomor HP
            $nomorTerenkripsi = EnkripsiChaCha20::enkripsi($nomorHP, $kunci);
            
            // Simpan ke database
            $warga = Warga::create([
                'nama' => $request->input('nama'),
                'no_hp' => $nomorTerenkripsi,
                'no_rumah' => $request->input('no_rumah'),
                'blok_rt' => $request->input('blok_rt'),
                'password' => bcrypt($request->input('password'))
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Data warga berhasil disimpan',
                'data' => $warga
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan data warga dengan nomor HP terdekripsi
     */
    
}