@extends('layouts.app')

@section('title', 'Dashboard Warga')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Warga</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i> Unduh Kwitansi
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Welcome Card -->
        <div class="col-xl-12 col-md-12 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="h5 mb-1 font-weight-bold text-gray-800">Selamat datang, {{ auth('warga')->user()->nama }}!</div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                {{ auth('warga')->user()->no_rumah }} - Blok/RT: {{ auth('warga')->user()->blok_rt }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-home fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Iuran Terakhir -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Iuran Terakhir</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Jenis</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($iuranTerakhir as $iuran)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $iuran->tanggal }}</td>
                                    <td>{{ ucfirst($iuran->jenis) }}</td>
                                    <td>Rp {{ number_format($iuran->jumlah, 0, ',', '.') }}</td>
                                    <td>
                                        @if($iuran->status == 'menunggu')
                                            <span class="badge badge-warning" style="color: #000000; font-weight: 500;">Menunggu Verifikasi</span>
                                        @else
                                            <span class="badge badge-success" style="color: #000000; font-weight: 500;">Lunas</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($iuran->jenis == 'cash')
                                        <a href="#" class="btn btn-sm btn-info">Lihat Kwitansi</a>
                                        @else
                                        <a href="#" class="btn btn-sm btn-secondary">Lihat Bukti</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifikasi -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Notifikasi Terbaru</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Dropdown Header:</div>
                            <a class="dropdown-item" href="#">Tandai semua sudah dibaca</a>
                        </div>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="list-group">
                        @foreach($notifikasi as $notif)
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $notif->pesan }}</h6>
                                <small>{{ $notif->created_at->diffForHumans() }}</small>
                            </div>
                            <small class="text-muted">{{ $notif->tanggal }}</small>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Pembayaran Iuran -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Bayar Iuran</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 15rem;"
                            src="{{ asset('img/undraw_posting_photo.svg') }}" alt="...">
                    </div>
                    <p>Silakan pilih metode pembayaran iuran Anda:</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('warga.iuran.cash') }}" class="btn btn-primary btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-money-bill-wave"></i>
                            </span>
                            <span class="text">Bayar Cash</span>
                        </a>
                        <a href="{{ route('warga.iuran.transfer') }}" class="btn btn-success btn-icon-split" data-toggle="modal" data-target="#bayarTransferModal">
                            <span class="icon text-white-50">
                                <i class="fas fa-exchange-alt"></i>
                            </span>
                            <span class="text">Transfer Bank</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bayar Cash Modal-->
<div class="modal fade" id="bayarCashModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Bayar Iuran Cash</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="jumlah">Jumlah Iuran</label>
                        <input type="number" class="form-control" id="jumlah" placeholder="Masukkan jumlah iuran">
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Tanggal Pembayaran</label>
                        <input type="date" class="form-control" id="tanggal">
                    </div>
                </form>
                <p class="mt-3">Silakan datang ke sekretariat RT dengan membawa uang tunai sejumlah yang telah diisi.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                <a class="btn btn-primary" href="#">Konfirmasi</a>
            </div>
        </div>
    </div>
</div>

<!-- Bayar Transfer Modal-->
<div class="modal fade" id="bayarTransferModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Bayar Iuran via Transfer</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="jumlahTransfer">Jumlah Iuran</label>
                        <input type="number" class="form-control" id="jumlahTransfer" placeholder="Masukkan jumlah iuran">
                    </div>
                    <div class="form-group">
                        <label for="tanggalTransfer">Tanggal Transfer</label>
                        <input type="date" class="form-control" id="tanggalTransfer">
                    </div>
                    <div class="form-group">
                        <label for="buktiTransfer">Upload Bukti Transfer</label>
                        <input type="file" class="form-control-file" id="buktiTransfer">
                    </div>
                </form>
                <div class="alert alert-info mt-3">
                    <h6>Rekening Pembayaran:</h6>
                    <p>Bank: BCA<br>
                    No. Rekening: 1234567890<br>
                    Atas Nama: Pengurus RT 05</p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                <a class="btn btn-primary" href="#">Kirim Bukti</a>
            </div>
        </div>
    </div>
</div>
@endsection