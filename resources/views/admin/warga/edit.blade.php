@extends('layouts.app')

@section('title', 'Edit Data Warga')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Data Warga</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.warga.update', $warga->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="{{ $warga->nama }}" required>
                </div>
                <div class="form-group">
                    <label for="no_hp">No HP</label>
                    <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ $warga->no_hp }}" required>
                </div>
                <div class="form-group">
                    <label for="no_rumah">No Rumah</label>
                    <input type="text" class="form-control" id="no_rumah" name="no_rumah" value="{{ $warga->no_rumah }}" required>
                </div>
                <div class="form-group">
                    <label for="blok_rt">Blok/RT</label>
                    <input type="text" class="form-control" id="blok_rt" name="blok_rt" value="{{ $warga->blok_rt }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.data_warga') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection