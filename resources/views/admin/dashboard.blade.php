@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Admin</h1>
        <!-- Add the button here in the page header -->
        <div>
            <a href="{{ route('admin.iuran.cash.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Input Pembayaran Cash
            </a>
            <a href="{{ route('admin.pengeluaran.create') }}" class="btn btn-danger btn-sm">
                <i class="fas fa-minus"></i> Tambah Pengeluaran
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Total Warga Card -->
<div class="col-xl-3 col-md-6 mb-4">
    <a href="{{ route('admin.data_warga') }}" class="text-decoration-none">
        <div class="card border-left-primary shadow h-100 py-2 hover-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Warga</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalWarga }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>

<!-- Warga Belum Bayar Card -->
<div class="col-xl-3 col-md-6 mb-4">
    <a href="{{ route('admin.warga.belum_bayar') }}" class="text-decoration-none">
        <div class="card border-left-danger shadow h-100 py-2 hover-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Warga Belum Bayar</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $wargaBelumBayarCount ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>

        <!-- Quick Action Card for Cash Payment -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Pembayaran Cash</div>
                            <div class="h5 mb-0">
                                <a href="{{ route('admin.iuran.cash.create') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-money-bill-wave"></i> Input Manual
                                </a>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cash-register fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Iuran Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Iuran</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalIuran, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pengeluaran Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Pengeluaran</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-receipt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Area Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary" >Grafik Iuran Bulanan</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Metode Pembayaran</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="myPieChart"></canvas>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> Cash
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Transfer
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions Table -->


    <!-- Tambahkan ini setelah "Recent Transactions Table" -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Pengeluaran Terakhir</h6>
                <a href="{{ route('admin.pengeluaran.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No. Pengeluaran</th>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pengeluaranTerakhir as $pengeluaran)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $pengeluaran->no_pengeluaran }}</td>
                                <td>{{ \Carbon\Carbon::parse($pengeluaran->tanggal)->format('d/m/Y') }}</td>
                                <td>{{ $pengeluaran->jenis_pengeluaran }}</td>
                                <td>Rp {{ number_format($pengeluaran->jumlah_pengeluaran, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Transaksi Terakhir</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Warga</th>
                                    <th>Jenis</th>
                                    <th>Nominal</th>
                                    <th>Metode</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($iuranTerakhir as $transaction)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $transaction->warga->nama }}</td>
                                    <td>{{ $transaction->jenis_iuran }}</td>
                                    <td>Rp {{ number_format($transaction->nominal, 0, ',', '.') }}</td>
                                    <td>{{ $transaction->metode_pembayaran }}</td>
                                   <td>{{ \Carbon\Carbon::parse($transaction->tgl)->format('d M Y') }}</td>
                                   <td>
                                        <span class="badge badge-{{ $transaction->status == 'lunas' ? 'success' : 'warning' }}" style="color: #252525ff; font-weight: 600; font-style: Inter; font-size:15px;">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
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
<div class="d-none">
    <h4>Debug Data:</h4>
    <p>Chart Months: {{ json_encode($chartMonths) }}</p>
    <p>Chart Data: {{ json_encode($chartData) }}</p>
    <p>Payment Methods Data: {{ json_encode($paymentMethodsData) }}</p>
</div>
@endsection

@push('scripts')
<!-- Page level plugins -->
<script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

<!-- Page level custom scripts -->
<script>
// Function to format numbers
function number_format(number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(',', '').replace(' ', '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? '.' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? ',' : dec_point,
        s = '',
        toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

// Area Chart Implementation with Error Handling
try {
    var areaCtx = document.getElementById("myAreaChart");
    if (areaCtx) {
        console.log("Creating Area Chart with data:", {
            labels: {!! json_encode($chartMonths) !!},
            datasets: [{
                data: {!! json_encode($chartData) !!}
            }]
        });

        var myLineChart = new Chart(areaCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartMonths) !!},
                datasets: [{
                    label: "Iuran",
                    lineTension: 0.3,
                    backgroundColor: "rgba(6, 6, 6, 0.05)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: {!! json_encode($chartData) !!},
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 75,
                        right: 50,
                        top: 35,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            callback: function(value, index, values) {
                                return 'Rp ' + number_format(value);
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': Rp ' + number_format(tooltipItem.yLabel);
                        }
                    }
                }
            }
        });
    } else {
        console.error("Area Chart Error: Canvas element with ID 'myAreaChart' not found");
    }
} catch (areaError) {
    console.error("Area Chart Error:", areaError);
}

// Pie Chart Implementation with Error Handling
try {
    var pieCtx = document.getElementById("myPieChart");
    if (pieCtx) {
        console.log("Creating Pie Chart with data:", {!! json_encode($paymentMethodsData) !!});

        var myPieChart = new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: ["Cash", "Transfer"],
                datasets: [{
                    data: {!! json_encode($paymentMethodsData) !!},
                    backgroundColor: ['#4e73df', '#1cc88a'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var datasetLabel = chart.labels[tooltipItem.index] || '';
                            var value = chart.datasets[0].data[tooltipItem.index];
                            return datasetLabel + ': ' + number_format(value) + ' transaksi';
                        }
                    }
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
            },
        });
    } else {
        console.error("Pie Chart Error: Canvas element with ID 'myPieChart' not found");
    }
} catch (pieError) {
    console.error("Pie Chart Error:", pieError);
}
</script>
@endpush