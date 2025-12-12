@extends('layouts.app')

@section('title', 'Tambah Iuran Cash')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Form Pembayaran Iuran Cash</h6>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.iuran.cash.store') }}">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="warga_id" class="form-label">Warga</label>
                        <select class="form-control @error('warga_id') is-invalid @enderror" 
                               id="warga_id" name="warga_id" required>
                            <option value="">Pilih Warga</option>
                            @foreach($wargas as $warga)
                            <option value="{{ $warga->id }}" {{ old('warga_id') == $warga->id ? 'selected' : '' }}>
                                {{ $warga->nama }} - {{ $warga->blok_rt }}
                            </option>
                            @endforeach
                        </select>
                        @error('warga_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="jenis_iuran" class="form-label">Jenis Iuran</label>
                        <input type="text" class="form-control @error('jenis_iuran') is-invalid @enderror" 
                               id="jenis_iuran" name="jenis_iuran" value="{{ old('jenis_iuran') }}" required>
                        @error('jenis_iuran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nominal" class="form-label">Nominal</label>
                        <input type="number" class="form-control @error('nominal') is-invalid @enderror" 
                               id="nominal" name="nominal" value="{{ old('nominal') }}" required min="1000">
                        @error('nominal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                        <select class="form-control @error('metode_pembayaran') is-invalid @enderror" 
                               id="metode_pembayaran" name="metode_pembayaran" required>
                            <option value="">Pilih Metode</option>
                            <option value="cash" {{ old('metode_pembayaran') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                        </select>
                        @error('metode_pembayaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="tgl" class="form-label">Tanggal Pembayaran</label>
                        <input type="date" class="form-control @error('tgl') is-invalid @enderror" 
                               id="tgl" name="tgl" value="{{ old('tgl', date('Y-m-d')) }}" required>
                        @error('tgl')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control @error('status') is-invalid @enderror" 
                               id="status" name="status" required>
                            <option value="">Pilih Status</option>
                            <option value="lunas" {{ old('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection