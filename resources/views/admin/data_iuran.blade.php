@extends('layouts.app')

@section('title', 'Data Iuran')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Iuran</h6>
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#tambahIuranModal">
                <i class="fas fa-plus"></i> Tambah Iuran Cash
            </button>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="cash-tab" data-toggle="tab" href="#cash" role="tab">Iuran Cash</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="transfer-tab" data-toggle="tab" href="#transfer" role="tab">Iuran Transfer</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="cash" role="tabpanel">
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered" id="cashTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Kwitansi</th>
                                    <th>Warga</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal</th>
                                    <th>Admin</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($iuranCash as $iuran)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $iuran->kwitansi->no_kwitansi ?? '-' }}</td>
                                    <td>{{ $iuran->warga->nama }}</td>
                                    <td>Rp {{ number_format($iuran->nominal, 0, ',', '.') }}</td>
                                    <td>{{ $iuran->tgl }}</td>
                                    <td>{{ $iuran->admin->nama }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-info">Detail</a>
                                        @if($iuran->kwitansi)
                                        <a href="{{ route('pdf.kwitansi.admin', $iuran->kwitansi->id) }}" class="btn btn-sm btn-primary">
                                            Cetak Kwitansi
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="transfer" role="tabpanel">
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered" id="transferTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Warga</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal</th>
                                    <th>Bukti Transfer</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($iuranTransfer as $transfer)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $transfer->warga->nama }}</td>
                                    <td>Rp {{ number_format($transfer->nominal, 0, ',', '.') }}</td>
                                    <td>{{ $transfer->tanggal }}</td>
                                    <td>
                                        <a href="{{ asset('storage/' . $transfer->bukti_transfer) }}" target="_blank" class="btn btn-sm btn-secondary">
                                            Lihat Bukti
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-info">Detail</a>
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

<!-- Tambah Iuran Modal -->
<div class="modal fade" id="tambahIuranModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Iuran Cash</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.iuran.cash.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="warga_id">Warga</label>
                        <select class="form-control" id="warga_id" name="warga_id" required>
                            <option value="">Pilih Warga</option>
                            @foreach($wargas as $warga)
                            <option value="{{ $warga->id }}">{{ $warga->nama }} - {{ $warga->blok_rt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nominal">Jumlah Iuran</label>
                        <input type="number" class="form-control" id="nominal" name="nominal" required>
                    </div>
                    <div class="form-group">
                        <label for="tgl">Tanggal</label>
                        <input type="date" class="form-control" id="tgl" name="tgl" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#cashTable').DataTable();
        $('#transferTable').DataTable();
    });
</script>
@endpush