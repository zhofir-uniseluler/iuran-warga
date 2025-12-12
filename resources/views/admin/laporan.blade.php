@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Laporan Keuangan</h6>
            <div>
                <button class="btn btn-primary btn-sm" onclick="window.print()">
                    <i class="fas fa-print"></i> Cetak Laporan
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Date Filter Form -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('pdf.laporan') }}" method="GET" class="form-inline">
                                <div class="form-group mr-3">
                                    <label for="start_date" class="mr-2">Dari Tanggal:</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                                </div>
                                <div class="form-group mr-3">
                                    <label for="end_date" class="mr-2">Sampai Tanggal:</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-file-pdf"></i> Cetak PDF
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Iuran</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($totalIuran, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Total Pengeluaran</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Saldo Kas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($saldo, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">Detail Iuran</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Jenis</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Iuran Cash</td>
                                            <td>Rp {{ number_format($totalIuranCash, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Iuran Transfer</td>
                                            <td>Rp {{ number_format($totalIuranTransfer, 0, ',', '.') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">Grafik Keuangan</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="financeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Transaksi Terakhir</h6>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="iuran-tab" data-toggle="tab" href="#iuran" role="tab">Iuran</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pengeluaran-tab" data-toggle="tab" href="#pengeluaran" role="tab">Pengeluaran</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="iuran" role="tabpanel">
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Warga</th>
                                            <th>Jenis</th>
                                            <th>Jumlah</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($iuranCash as $iuran)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $iuran->warga->nama }}</td>
                                            <td>Cash</td>
                                            <td>Rp {{ number_format($iuran->nominal, 0, ',', '.') }}</td>
                                            <td>{{ $iuran->tgl }}</td>
                                        </tr>
                                        @endforeach
                                        @foreach($iuranTransfer as $transfer)
                                        <tr>
                                            <td>{{ $loop->iteration + $iuranCash->count() }}</td>
                                            <td>{{ $transfer->warga->nama }}</td>
                                            <td>Transfer</td>
                                            <td>Rp {{ number_format($transfer->nominal, 0, ',', '.') }}</td>
                                            <td>{{ $transfer->tanggal }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pengeluaran" role="tabpanel">
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Jenis Pengeluaran</th>
                                            <th>Kategori</th>
                                            <th>Jumlah</th>
                                            <th>Tanggal</th>
                                            <th>Admin</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pengeluarans as $pengeluaran)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $pengeluaran->jenis_pengeluaran }}</td>
                                            <td>{{ $pengeluaran->kategori->nama }}</td>
                                            <td>Rp {{ number_format($pengeluaran->jumlah_pengeluaran, 0, ',', '.') }}</td>
                                            <td>{{ $pengeluaran->tanggal }}</td>
                                            <td>{{ $pengeluaran->admin->nama }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Finance Chart
    const ctx = document.getElementById('financeChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Iuran Cash', 'Iuran Transfer', 'Pengeluaran'],
            datasets: [{
                data: [{{ $totalIuranCash }}, {{ $totalIuranTransfer }}, {{ $totalPengeluaran }}],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.7)',
                    'rgba(23, 162, 184, 0.7)',
                    'rgba(220, 53, 69, 0.7)'
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(23, 162, 184, 1)',
                    'rgba(220, 53, 69, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.raw.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endpush