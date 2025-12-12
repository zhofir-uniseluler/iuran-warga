@extends('layouts.app')

@section('title', 'Data Pengeluaran')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Pengeluaran</h6>
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#tambahPengeluaranModal">
                <i class="fas fa-plus"></i> Tambah Pengeluaran
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Pengeluaran</th>
                            <th>Jenis Pengeluaran</th>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th>Admin</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengeluarans as $pengeluaran)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $pengeluaran->no_pengeluaran }}</td>
                            <td>{{ $pengeluaran->jenis_pengeluaran }}</td>
                            <td>{{ $pengeluaran->kategori->nama }}</td>
                            <td>Rp {{ number_format($pengeluaran->jumlah_pengeluaran, 0, ',', '.') }}</td>
                            <td>{{ $pengeluaran->tanggal }}</td>
                            <td>{{ $pengeluaran->admin->nama }}</td>
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

<!-- Tambah Pengeluaran Modal -->
<div class="modal fade" id="tambahPengeluaranModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pengeluaran</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.pengeluaran.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="jenis_pengeluaran">Jenis Pengeluaran</label>
                        <input type="text" class="form-control" id="jenis_pengeluaran" name="jenis_pengeluaran" required>
                    </div>
                    <div class="form-group">
                        <label for="kategori_pengeluaran_id">Kategori</label>
                        <select class="form-control" id="kategori_pengeluaran_id" name="kategori_pengeluaran_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_pengeluaran">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah_pengeluaran" name="jumlah_pengeluaran" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" required>
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
        $('#dataTable').DataTable();
    });
</script>
@endpush