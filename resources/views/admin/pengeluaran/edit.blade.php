@extends('layouts.app')

@section('title', 'Edit Pengeluaran')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Pengeluaran</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pengeluaran.update', $pengeluaran->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="no_pengeluaran">Nomor Pengeluaran</label>
                    <input type="text" class="form-control @error('no_pengeluaran') is-invalid @enderror" id="no_pengeluaran" name="no_pengeluaran" value="{{ old('no_pengeluaran', $pengeluaran->no_pengeluaran) }}" required>
                    @error('no_pengeluaran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', $pengeluaran->tanggal) }}" required>
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="jenis_pengeluaran">Jenis Pengeluaran</label>
                    <input type="text" class="form-control @error('jenis_pengeluaran') is-invalid @enderror" id="jenis_pengeluaran" name="jenis_pengeluaran" value="{{ old('jenis_pengeluaran', $pengeluaran->jenis_pengeluaran) }}" required>
                    @error('jenis_pengeluaran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="jumlah_pengeluaran">Jumlah Pengeluaran</label>
                    <input type="number" class="form-control @error('jumlah_pengeluaran') is-invalid @enderror" id="jumlah_pengeluaran" name="jumlah_pengeluaran" value="{{ old('jumlah_pengeluaran', $pengeluaran->jumlah_pengeluaran) }}" required>
                    @error('jumlah_pengeluaran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="kategori_pengeluaran_id">Kategori</label>
                    <select class="form-control @error('kategori_pengeluaran_id') is-invalid @enderror" id="kategori_pengeluaran_id" name="kategori_pengeluaran_id" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($kategori as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_pengeluaran_id', $pengeluaran->kategori_pengeluaran_id) == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                        @endforeach
                    </select>
                    @error('kategori_pengeluaran_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.pengeluaran.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection