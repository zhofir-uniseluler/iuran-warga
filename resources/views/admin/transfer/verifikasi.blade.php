@extends('layouts.app')

@section('title', 'Verifikasi Transfer')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Transfer Perlu Verifikasi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Warga</th>
                            <th>Nominal</th>
                            <th>Tanggal</th>
                            <th>Bukti Transfer</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transfers as $transfer)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $transfer->warga->nama }}</td>
                            <td>Rp {{ number_format($transfer->nominal, 0, ',', '.') }}</td>
                            <td>{{ $transfer->tanggal->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ asset('storage/'.$transfer->bukti_transfer) }}" target="_blank" class="btn btn-sm btn-info">
                                    Lihat Bukti
                                </a>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalVerifikasi{{ $transfer->id }}">
                                    Verifikasi
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Verifikasi -->
                        <div class="modal fade" id="modalVerifikasi{{ $transfer->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.transfer.verifikasi.proses', $transfer->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Verifikasi Transfer</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select name="status" class="form-control" required>
                                                    <option value="lunas">Setujui</option>
                                                    <option value="ditolak">Tolak</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Catatan (Khusus jika ditolak)</label>
                                                <textarea name="catatan" class="form-control" rows="3"></textarea>
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection