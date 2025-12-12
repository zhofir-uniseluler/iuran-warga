@extends('layouts.app')

@section('title', 'Iuran Transfer')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Iuran Transfer</h6>
            <a href="{{ route('warga.transfer.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Transfer
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th>Bukti Transfer</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($iuranTransfer as $transfer)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>Rp {{ number_format($transfer->nominal, 0, ',', '.') }}</td>
                            <td>{{ $transfer->tanggal }}</td>
                            <td>
                                <a href="{{ asset('storage/' . $transfer->bukti_transfer) }}" 
                                   target="_blank" class="btn btn-sm btn-secondary">
                                    Lihat Bukti
                                </a>
                            </td>
                            <td>
                                @php
                                    $notification = auth('warga')->user()
                                        ->notifikasi()
                                        ->where('tanggal', $transfer->tanggal)
                                        ->where('pesan', 'like', '%transfer%')
                                        ->first();
                                @endphp
                                
                                @if($notification && $notification->status == 'menunggu')
                                    <span class="badge badge-warning"style="color: #000000; font-weight: 500;">Menunggu Verifikasi</span>
                                @else
                                    <span class="badge badge-success">Terverifikasi</span>
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