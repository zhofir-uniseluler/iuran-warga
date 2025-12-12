<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 5px 0 0; }
        .content { margin: 30px 0; }
        .info { margin-bottom: 15px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .signature { display: flex; justify-content: space-between; margin-top: 50px; }
        .signature-box { width: 45%; text-align: center; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>KWITANSI PEMBAYARAN IURAN</h1>
        <p>RT 05 RW 10 Kelurahan Contoh</p>
    </div>
    
    <div class="info">
        <p><strong>No. Kwitansi:</strong> {{ $kwitansi->no_kwitansi }}</p>
        <p><strong>Tanggal:</strong> {{ date('d F Y', strtotime($kwitansi->tanggal)) }}</p>
    </div>
    
    <div class="content">
        <p>Telah diterima uang sebesar:</p>
        <h3>Rp {{ number_format($kwitansi->nominal, 0, ',', '.') }}</h3>
        <p>Untuk pembayaran iuran warga periode {{ date('F Y', strtotime($kwitansi->tanggal)) }}</p>
        
        <table class="table">
            <tr>
                <th width="30%">Nama Warga</th>
                <td>{{ $kwitansi->iuranCash->warga->nama }}</td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{{ $kwitansi->iuranCash->warga->no_rumah }}, {{ $kwitansi->iuranCash->warga->blok_rt }}</td>
            </tr>
            <tr>
                <th>Tanggal Bayar</th>
                <td>{{ date('d F Y', strtotime($kwitansi->iuranCash->tgl)) }}</td>
            </tr>
            <tr>
                <th>Diterima Oleh</th>
                <td>{{ $kwitansi->iuranCash->admin->nama }} ({{ $kwitansi->iuranCash->admin->jabatan }})</td>
            </tr>
        </table>
    </div>
    
    <div class="signature">
        <div class="signature-box">
            <p>Penyetor,</p>
            <br><br><br>
            <p>(_______________________)</p>
        </div>
        <div class="signature-box">
            <p>Penerima,</p>
            <br><br><br>
            <p>(_______________________)</p>
        </div>
    </div>
    
    <div class="footer">
        <p>*Kwitansi ini sebagai bukti pembayaran yang sah</p>
    </div>
</body>
</html>