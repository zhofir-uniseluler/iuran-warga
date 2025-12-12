@extends('layouts.app')

@section('title', 'Data Warga')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Warga</h6>
            <a href="{{ route('admin.warga.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Warga
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>No HP</th>
                            <th>No Rumah</th>
                            <th>Blok/RT</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($warga as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama }}</td>
                            <td><span class="no-hp-masked">{{ $item->no_hp_masked }}</span>
                                @if(auth()->guard('admin')->check())
                                    <button class="btn btn-sm btn-link show-phone-btn" data-id="{{ $item->id }}">
                                        <i class="fas fa-eye"></i> Lihat
                                    </button>
                                @endif
                            </td>
                            <td>{{ $item->no_rumah }}</td>
                            <td>{{ $item->blok_rt }}</td>
                            <td>
                                <a href="{{ route('admin.warga.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                 <a href="{{ route('admin.warga.riwayat', $item->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-history"></i> Riwayat
                                </a>
                                <form action="{{ route('admin.warga.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin?')">
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

@push('styles')
<style>
.no-hp-masked {
    font-family: monospace;
    letter-spacing: 1px;
}
</style>
@endpush

@push('scripts')
<!-- Load required JavaScript libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>

        // Handle phone number reveal
        $(document).on('click', '.show-phone-btn', function() {
    const button = $(this);
    const wargaId = button.data('id');
    const maskedElement = button.siblings('.no-hp-masked');
    
    button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memuat...');
    
    // Build URL properly
    const url = new URL(`/admin/warga/${wargaId}/show-phone`, window.location.origin);
    
    $.ajax({
        url: url.href,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                maskedElement.text(response.no_hp);
                button.hide();
            } else {
                alert(response.message || 'Gagal memuat nomor HP');
                button.html('<i class="fas fa-eye"></i> Lihat').prop('disabled', false);
            }
        },
        error: function(xhr) {
            const errorMsg = xhr.responseJSON?.message || 'Terjadi kesalahan saat memuat nomor HP';
            alert(errorMsg);
            button.html('<i class="fas fa-eye"></i> Lihat').prop('disabled', false);
        },
        complete: function() {
            setTimeout(() => {
                if (button.is(':visible')) {
                    button.prop('disabled', false);
                }
            }, 3000);
        }
    });
});
</script>
@endpush