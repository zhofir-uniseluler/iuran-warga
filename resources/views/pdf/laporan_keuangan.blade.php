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
        .periode { text-align: center; margin-bottom: 20px; }
        .summary { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .summary-box { width: 30%; padding: 10px; border: 1px solid #ddd; text-align: center; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .footer { margin-top: 30px; text-align: right; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KEUANGAN IURAN WARGA</h1>
        <p>RT 05 RW 10 Kelurahan Contoh</p>
    </div>
    
    <div class="periode">
        <p>Periode: {{ date('d F Y', strtotime($start_date)) }} - {{ date('d F Y', strtotime($end_date)) }}</p>
    </div>
    
    <div class="summary">
        <div class="summary-box">
            <h3>Total Iuran</h3>
            <p>Rp {{ number_format($totalIuran, 0, ',', '.') }}</p>
        </div>
        <div class="summary-box">
            <h3>Total Pengeluaran</h3>
            <p>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
        </div>
        <div class="summary-box">
            <h3>Saldo Akhir</h3>
            <p>Rp {{ number_format($saldo, 0, ',', '.') }}</p>
        </div>
    </div>
    
    <h3>Detail Iuran Cash</h3>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Warga</th>
                <th>Nominal</th>
                <th>Admin</th>
            </tr>
        </thead>
        <tbody>
            @foreach($iuranCash as $index => $iuran)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ date('d/m/Y', strtotime($iuran->tgl)) }}</td>
                <td>{{ $iuran->warga->nama }}</td>
                <td>Rp {{ number_format($iuran->nominal, 0, ',', '.') }}</td>
                <td>{{ $iuran->admin->nama }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <h3>Detail Iuran Transfer</h3>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Warga</th>
                <th>Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($iuranTransfer as $index => $transfer)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ date('d/m/Y', strtotime($transfer->tanggal)) }}</td>
                <td>{{ $transfer->warga->nama }}</td>
                <td>Rp {{ number_format($transfer->nominal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <h3>Detail Pengeluaran</h3>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jenis Pengeluaran</th>
                <th>Kategori</th>
                <th>Nominal</th>
                <th>Admin</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengeluaran as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                <td>{{ $item->jenis_pengeluaran }}</td>
                <td>{{ $item->kategori->nama }}</td>
                <td>Rp {{ number_format($item->jumlah_pengeluaran, 0, ',', '.') }}</td>
                <td>{{ $item->admin->nama }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y H:i:s') }}</p>
    </div>
</body>
</html>