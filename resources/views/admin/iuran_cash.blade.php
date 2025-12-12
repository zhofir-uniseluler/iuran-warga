@extends('layouts.app')

@section('title', 'Input Iuran Cash')

@section('content')
<div class="container">
    @if(Auth::guard('admin')->check())
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4>Form Iuran Cash</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.iuran.cash.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Warga</label>
                        <select name="warga_id" class="form-control" required>
                            @foreach($wargas as $warga)
                                <option value="{{ $warga->id }}">{{ $warga->nama }} ({{ $warga->no_rumah }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nominal</label>
                        <input type="number" name="nominal" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tgl" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-danger">
            <strong>Error!</strong> Hanya admin yang boleh menginput iuran cash.
        </div>
    @endif
</div>
@endsection