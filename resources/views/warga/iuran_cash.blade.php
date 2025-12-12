@extends('layouts.app')

@section('title', 'Iuran Cash')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Iuran Cash</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Kwitansi</th>
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
                            <td>Rp {{ number_format($iuran->nominal, 0, ',', '.') }}</td>
                            <td>{{ $iuran->tgl }}</td>
                            <td>{{ $iuran->admin->nama }}</td>
                            <td>
                                @if($iuran->kwitansi)
                                <a href="{{ route('warga.kwitansi.download', $iuran->kwitansi->id) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-download"></i> Kwitansi
                                </a>
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
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>
@endpush