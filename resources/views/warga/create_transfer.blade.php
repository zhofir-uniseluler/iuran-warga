@extends('layouts.app')

@section('title', 'Tambah Iuran Transfer')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Form Iuran Transfer</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('warga.transfer.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="nominal" class="form-label">Nominal Iuran</label>
                            <input type="number" class="form-control @error('nominal') is-invalid @enderror" 
                                   id="nominal" name="nominal" required>
                            @error('nominal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal Transfer</label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                                   id="tanggal" name="tanggal" required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="bukti_transfer" class="form-label">Bukti Transfer</label>
                            <input type="file" class="form-control @error('bukti_transfer') is-invalid @enderror" 
                                   id="bukti_transfer" name="bukti_transfer" required>
                            @error('bukti_transfer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format: JPG/PNG, maksimal 2MB</small>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Kirim</button>
                            <a href="{{ route('warga.iuran.transfer') }}" class="btn btn-secondary">
                                Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection