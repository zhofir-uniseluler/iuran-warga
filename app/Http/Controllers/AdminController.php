<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warga;
use App\Models\IuranCash;
use App\Models\Kwitansi;
use App\Models\Notifikasi;
use App\Models\IuranTransfer;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use App\Models\KategoriPengeluaran;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalWarga = Warga::count();
        $totalIuranCash = IuranCash::sum('nominal');
        $totalIuranTransfer = IuranTransfer::sum('nominal');
        $totalIuran = $totalIuranCash + $totalIuranTransfer;
        $totalPengeluaran = Pengeluaran::sum('jumlah_pengeluaran');
        $saldo = $totalIuran - $totalPengeluaran;

        // Hitung persentase
        $total = $totalIuranCash + $totalIuranTransfer + $totalPengeluaran;
        $persenCash = $total > 0 ? round(($totalIuranCash / $total) * 100) : 0;
        $persenTransfer = $total > 0 ? round(($totalIuranTransfer / $total) * 100) : 0;
        $persenPengeluaran = $total > 0 ? round(($totalPengeluaran / $total) * 100) : 0;

        // Hitung warga belum bayar bulan ini
        $periode = now()->format('Y-m');
        $wargaSudahBayarCash = IuranCash::where('tgl', 'like', $periode.'%')
            ->pluck('warga_id')
            ->toArray();
        $wargaSudahBayarTransfer = IuranTransfer::where('tanggal', 'like', $periode.'%')
            ->where('status', 'lunas')
            ->pluck('warga_id')
            ->toArray();
        $wargaSudahBayar = array_unique(array_merge($wargaSudahBayarCash, $wargaSudahBayarTransfer));
        $wargaBelumBayarCount = Warga::whereNotIn('id', $wargaSudahBayar)->count();

        $iuranTerakhir = IuranCash::with(['warga'])
            ->select('warga_id', 'jenis_iuran', 'nominal', 'metode_pembayaran', 'tgl', 'status')
            ->latest()
            ->take(5)
            ->get();
        
        $pengeluaranTerakhir = Pengeluaran::with(['kategoriPengeluaran', 'admin'])
            ->latest()
            ->take(5)
            ->get();

        // Data untuk chart
        $cashPerMonth = IuranCash::selectRaw('MONTH(tgl) as month, SUM(nominal) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        $transferPerMonth = IuranTransfer::selectRaw('MONTH(tanggal) as month, SUM(nominal) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        $paymentMethodsData = [
            'cash' => $totalIuranCash,
            'transfer' => $totalIuranTransfer,
            'total' => $totalIuran
        ];

        $chartMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        // Prepare chart data
        $chartData = [
            'labels' => $chartMonths,
            'datasets' => [
                [
                    'label' => 'Iuran Cash',
                    'data' => $this->fillMonthlyData($cashPerMonth),
                    'backgroundColor' => '#4e73df'
                ],
                [
                    'label' => 'Iuran Transfer',
                    'data' => $this->fillMonthlyData($transferPerMonth),
                    'backgroundColor' => '#1cc88a'
                ]
            ]
        ];

        return view('admin.dashboard', compact(
            'totalWarga',
            'totalIuran',
            'totalIuranCash',
            'totalIuranTransfer',
            'totalPengeluaran',
            'saldo',
            'persenCash',
            'persenTransfer',
            'persenPengeluaran',
            'iuranTerakhir',
            'pengeluaranTerakhir',
            'chartData',
            'paymentMethodsData',
            'chartMonths',
            'wargaBelumBayarCount'
        ));
    }

    // Helper untuk mengisi 0 jika bulan tidak ada data
    private function fillMonthlyData($monthlyData)
    {
        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            $result[] = $monthlyData[$i] ?? 0;
        }
        return $result;
    }

    public function showDecryptedPhone($id)
{
    // Log akses
    \Log::info('Accessing decrypted phone number', [
        'admin_id' => auth('admin')->id(),
        'warga_id' => $id,
        'ip' => request()->ip(),
        'time' => now()->toDateTimeString()
    ]);

    if (!auth('admin')->check()) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized access'
        ], 403);
    }

    try {
        $warga = Warga::findOrFail($id);
        
        // Dapatkan nomor HP terdekripsi
        $noHp = $warga->no_hp_decrypted;
        
        if (empty($noHp)) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor HP tidak tersedia'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'no_hp' => $noHp
        ]);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        \Log::error("Warga not found: {$id}");
        return response()->json([
            'success' => false,
            'message' => 'Data warga tidak ditemukan'
        ], 404);
    } catch (\Exception $e) {
        \Log::error("Decryption error for warga {$id}: " . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Gagal memuat nomor HP'
        ], 500);
    }
}
    public function riwayatIuranWarga($id, Request $request)
{
    $warga = Warga::findOrFail($id);
    
    // Ambil parameter periode dari request atau gunakan bulan sekarang
    $periode = $request->input('periode', now()->format('Y-m'));
    $selectedDate = Carbon::createFromFormat('Y-m', $periode);
    
    // Ambil data 12 bulan terakhir dari periode yang dipilih
    $startDate = $selectedDate->copy()->startOfMonth()->subMonths(11);
    $endDate = $selectedDate->copy()->endOfMonth();
    
    // Data iuran cash
    $iuranCash = IuranCash::where('warga_id', $id)
        ->whereBetween('tgl', [$startDate, $endDate])
        ->selectRaw('DATE_FORMAT(tgl, "%Y-%m") as bulan, SUM(nominal) as total')
        ->groupBy('bulan')
        ->get()
        ->keyBy('bulan');
    
    // Data iuran transfer
    $iuranTransfer = IuranTransfer::where('warga_id', $id)
        ->whereBetween('tanggal', [$startDate, $endDate])
        ->where('status', 'lunas')
        ->selectRaw('DATE_FORMAT(tanggal, "%Y-%m") as bulan, SUM(nominal) as total')
        ->groupBy('bulan')
        ->get()
        ->keyBy('bulan');
    
    // Buat data untuk 12 bulan terakhir
    $riwayat = [];
    $labels = [];
    
    for ($i = 0; $i < 12; $i++) {
        $date = $selectedDate->copy()->startOfMonth()->subMonths(11 - $i);
        $bulan = $date->format('Y-m');
        $namaBulan = $date->translatedFormat('F Y');
        
        $labels[] = $namaBulan;
        
        $riwayat[$bulan] = [
            'bulan' => $namaBulan,
            'cash' => $iuranCash->has($bulan) ? $iuranCash[$bulan]->total : 0,
            'transfer' => $iuranTransfer->has($bulan) ? $iuranTransfer[$bulan]->total : 0,
            'total' => ($iuranCash->has($bulan) ? $iuranCash[$bulan]->total : 0) + 
                      ($iuranTransfer->has($bulan) ? $iuranTransfer[$bulan]->total : 0)
        ];
    }
    
    return view('admin.warga.riwayat', compact('warga', 'riwayat', 'labels', 'periode'));
}

    // Method untuk menampilkan data warga
    public function dataWarga()
    {
        $warga = Warga::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.data_warga', compact('warga'));
    }

    // Method untuk menampilkan form tambah warga
    public function createWarga()
    {
        return view('admin.warga.create');
    }

    public function storeWarga(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'no_rumah' => 'required|string|max:10',
            'blok_rt' => 'required|string|max:10',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Warga::create([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'no_rumah' => $request->no_rumah,
            'blok_rt' => $request->blok_rt,
            'password' => bcrypt($request->password), // Enkripsi password
        ]);

        return redirect()->route('admin.data_warga')
            ->with('success', 'Warga baru berhasil ditambahkan');
    }

    // Method untuk menampilkan form edit warga
    public function editWarga($id)
    {
        $warga = Warga::findOrFail($id);
        return view('admin.warga.edit', [
            'warga' => $warga,
            'no_hp_decrypted' => $warga->no_hp_decrypted
        ]);
    }

    // Method untuk update data warga
    public function updateWarga(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'no_rumah' => 'required|string|max:10',
            'blok_rt' => 'required|string|max:10',
        ]);

        $warga = Warga::findOrFail($id);
        $warga->update($request->all());

        return redirect()->route('admin.data_warga')
            ->with('success', 'Data warga berhasil diperbarui');
    }

    // Method untuk menghapus data warga
    public function destroyWarga($id)
    {
        $warga = Warga::findOrFail($id);
        $warga->delete();

        return redirect()->route('admin.data_warga')
            ->with('success', 'Warga berhasil dihapus');
    }

   public function wargaBelumBayar(Request $request)
{
    $periode = $request->input('periode', now()->format('Y-m'));
        // Ambil semua warga
        $semuaWarga = Warga::orderBy('nama')->get();
        
        // Ambil warga yang sudah bayar (baik cash maupun transfer) dalam periode tertentu
        $periode = now()->format('Y-m'); // Bulan ini
        $wargaSudahBayarCash = IuranCash::where('tgl', 'like', $periode.'%')
            ->pluck('warga_id')
            ->toArray();
            
        $wargaSudahBayarTransfer = IuranTransfer::where('tanggal', 'like', $periode.'%')
            ->where('status', 'lunas')
            ->pluck('warga_id')
            ->toArray();
        
        $wargaSudahBayar = array_unique(array_merge($wargaSudahBayarCash, $wargaSudahBayarTransfer));
        
        // Filter warga yang belum bayar
        $wargaBelumBayar = $semuaWarga->reject(function ($warga) use ($wargaSudahBayar) {
            return in_array($warga->id, $wargaSudahBayar);
        });
        
        return view('admin.warga_belum_bayar', compact('wargaBelumBayar', 'periode'));
    }

    // Method untuk menampilkan daftar Iuran Cash
    public function indexIuranCash()
    {
        $iuranCash = IuranCash::with('warga')->latest()->get();
        return view('admin.iuran_cash.index', compact('iuranCash'));
    }

    // Method untuk menampilkan form tambah Iuran Cash
    public function createIuranCash()
    {
        $wargas = Warga::orderBy('nama')->get();
        return view('admin.create_cash', compact('wargas'));
    }

    // Method untuk menyimpan Iuran Cash
    public function storeIuranCash(Request $request)
    {
        $validated = $request->validate([
            'warga_id' => 'required|exists:warga,id',
            'nominal' => 'required|numeric|min:1000',
            'jenis_iuran' => 'required|string|max:100',
            'metode_pembayaran' => 'required|in:cash,transfer',
            'tgl' => 'required|date',
            'status' => 'required|in:lunas,pending',
        ]);

        $iuran = IuranCash::create([
            'warga_id' => $validated['warga_id'],
            'admin_id' => Auth::guard('admin')->id(),
            'jenis_iuran' => $validated['jenis_iuran'],
            'nominal' => $validated['nominal'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'tgl' => $validated['tgl'],
            'status' => $validated['status'],
        ]);

        // Generate kwitansi
        $kwitansiNumber = 'KWT-' . date('Ymd') . '-' . str_pad(IuranCash::count(), 4, '0', STR_PAD_LEFT);

        $kwitansi = Kwitansi::create([
            'no_kwitansi' => $kwitansiNumber,
            'nominal' => $iuran->nominal,
            'tanggal' => $iuran->tgl,
            'iuran_cash_id' => $iuran->id,
        ]);

        // Create notification
        $kwitansi->notifikasi()->create([
            'tanggal' => now(),
            'status' => 'dibayar',
            'pesan' => 'Pembayaran iuran cash telah diterima',
            'warga_id' => $iuran->warga_id,
            'admin_id' => Auth::guard('admin')->id(),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Pembayaran cash berhasil dicatat!');
    }

    // Method untuk menampilkan daftar Iuran Transfer
    public function indexIuranTransfer()
    {
        $iuranTransfer = IuranTransfer::with('warga')->latest()->get();
        return view('admin.iuran_transfer.index', compact('iuranTransfer'));
    }

    // Method untuk menampilkan daftar Pengeluaran
    public function indexPengeluaran()
    {
        $pengeluaran = Pengeluaran::with(['kategoriPengeluaran', 'admin'])->latest()->get();
        return view('admin.pengeluaran.index', compact('pengeluaran'));
    }

    // Method untuk menampilkan form tambah Pengeluaran
    public function createPengeluaran()
    {
        $kategori = KategoriPengeluaran::all();
        return view('admin.pengeluaran.create', compact('kategori'));
    }

    // Method untuk menyimpan data Pengeluaran
    public function storePengeluaran(Request $request)
    {
        $request->validate([
            'no_pengeluaran' => 'required|string|max:50|unique:pengeluaran',
            'tanggal' => 'required|date',
            'jenis_pengeluaran' => 'required|string|max:100',
            'jumlah_pengeluaran' => 'required|numeric|min:1000',
            'kategori_pengeluaran_id' => 'required|exists:kategori_pengeluaran,id',
        ]);

        Pengeluaran::create([
            'no_pengeluaran' => $request->no_pengeluaran,
            'tanggal' => $request->tanggal,
            'jenis_pengeluaran' => $request->jenis_pengeluaran,
            'jumlah_pengeluaran' => $request->jumlah_pengeluaran,
            'kategori_pengeluaran_id' => $request->kategori_pengeluaran_id,
            'admin_id' => Auth::guard('admin')->id(),
        ]);

        return redirect()->route('admin.pengeluaran.index')->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    // Method untuk menampilkan form edit Pengeluaran
    public function editPengeluaran($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        $kategori = KategoriPengeluaran::all();
        return view('admin.pengeluaran.edit', compact('pengeluaran', 'kategori'));
    }

    // Method untuk update Pengeluaran
    public function updatePengeluaran(Request $request, $id)
    {
        $request->validate([
            'no_pengeluaran' => 'required|string|max:50|unique:pengeluaran,no_pengeluaran,'.$id,
            'tanggal' => 'required|date',
            'jenis_pengeluaran' => 'required|string|max:100',
            'jumlah_pengeluaran' => 'required|numeric|min:1000',
            'kategori_pengeluaran_id' => 'required|exists:kategori_pengeluaran,id',
        ]);

        $pengeluaran = Pengeluaran::findOrFail($id);
        $pengeluaran->update([
            'no_pengeluaran' => $request->no_pengeluaran,
            'tanggal' => $request->tanggal,
            'jenis_pengeluaran' => $request->jenis_pengeluaran,
            'jumlah_pengeluaran' => $request->jumlah_pengeluaran,
            'kategori_pengeluaran_id' => $request->kategori_pengeluaran_id,
        ]);

        return redirect()->route('admin.pengeluaran.index')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    // Method untuk menghapus Pengeluaran
    public function destroyPengeluaran($id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);
        $pengeluaran->delete();
        return redirect()->route('admin.pengeluaran.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }
}