@extends('layouts.app')

@section('title', 'Tambah Data Warga')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tambah Data Warga</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.warga.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                </div>
                <div class="form-group">
                    <label for="no_hp">No HP</label>
                    <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                </div>
                <div class="form-group">
                    <label for="no_rumah">No Rumah</label>
                    <input type="text" class="form-control" id="no_rumah" name="no_rumah" required>
                </div>
                <div class="form-group">
                    <label for="blok_rt">Blok/RT</label>
                    <input type="text" class="form-control" id="blok_rt" name="blok_rt" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <br>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.data_warga') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection