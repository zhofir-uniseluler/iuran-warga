@extends('layouts.app')

@section('title', 'Daftar Pengeluaran')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengeluaran</h6>
            <a href="{{ route('admin.pengeluaran.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Pengeluaran
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No. Pengeluaran</th>
                            <th>Tanggal</th>
                            <th>Jenis Pengeluaran</th>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                            <th>Admin</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengeluaran as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->no_pengeluaran }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ $item->jenis_pengeluaran }}</td>
                            <td>{{ $item->kategoriPengeluaran->nama }}</td>
                            <td>Rp {{ number_format($item->jumlah_pengeluaran, 0, ',', '.') }}</td>
                            <td>{{ $item->admin->name }}</td>
                            <td>
                                <a href="{{ route('admin.pengeluaran.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.pengeluaran.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection