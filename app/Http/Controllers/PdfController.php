<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kwitansi;
use App\Models\IuranCash;
use App\Models\IuranTransfer;
use App\Models\Pengeluaran;
use Barryvdh\DomPDF\Facade\Pdf; 

class PdfController extends Controller
{
    /**
     * Generate PDF kwitansi untuk warga
     */
    public function generateWargaKwitansi($id)
    {
        $kwitansi = Kwitansi::with(['iuranCash.warga', 'iuranCash.admin'])
                        ->findOrFail($id);

        // Verifikasi bahwa kwitansi milik warga yang login
        if (auth('warga')->check() && 
            $kwitansi->iuranCash->warga_id !== auth('warga')->id()) {
            abort(403, 'Unauthorized action.');
        }

        $data = [
            'kwitansi' => $kwitansi,
            'title' => 'Kwitansi Pembayaran Iuran',
            'date' => date('d/m/Y')
        ];

        $pdf = PDF::loadView('pdf.kwitansi_warga', $data);
        
        // Return untuk preview
        // return $pdf->stream('kwitansi-'.$kwitansi->no_kwitansi.'.pdf');
        
        // Return untuk download
        return $pdf->download('kwitansi-'.$kwitansi->no_kwitansi.'.pdf');
    }

    /**
     * Generate PDF kwitansi untuk admin
     */
    public function generateAdminKwitansi($id)
    {
        $kwitansi = Kwitansi::with(['iuranCash.warga', 'iuranCash.admin'])
                        ->findOrFail($id);

        $data = [
            'kwitansi' => $kwitansi,
            'title' => 'Kwitansi Pembayaran Iuran',
            'date' => date('d/m/Y')
        ];

        $pdf = PDF::loadView('pdf.kwitansi_admin', $data);
        
        // Opsi untuk print langsung
        // return $pdf->stream('kwitansi-'.$kwitansi->no_kwitansi.'.pdf');
        
        return $pdf->download('kwitansi-'.$kwitansi->no_kwitansi.'.pdf');
    }

    /**
     * Generate laporan keuangan PDF
     */
    public function generateLaporan(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $iuranCash = IuranCash::whereBetween('tgl', [$startDate, $endDate])->get();
        $iuranTransfer = IuranTransfer::whereBetween('tanggal', [$startDate, $endDate])->get();
        $pengeluaran = Pengeluaran::whereBetween('tanggal', [$startDate, $endDate])->get();

        $totalIuran = $iuranCash->sum('nominal') + $iuranTransfer->sum('nominal');
        $totalPengeluaran = $pengeluaran->sum('jumlah_pengeluaran');
        $saldo = $totalIuran - $totalPengeluaran;

        $data = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'iuranCash' => $iuranCash,
            'iuranTransfer' => $iuranTransfer,
            'pengeluaran' => $pengeluaran,
            'totalIuran' => $totalIuran,
            'totalPengeluaran' => $totalPengeluaran,
            'saldo' => $saldo,
            'title' => 'Laporan Keuangan Iuran Warga'
        ];

        $pdf = PDF::loadView('pdf.laporan_keuangan', $data)
                  ->setPaper('a4', 'landscape');
        
        return $pdf->download('laporan-keuangan-'.date('Y-m-d').'.pdf');
    }
}