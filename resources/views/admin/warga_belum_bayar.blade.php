@extends('layouts.app')

@section('title', 'Warga Belum Bayar Iuran')

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Warga Belum Bayar Iuran Bulan {{ $periode }}</h3>
        </div>
        
        <div class="card-body">
            @if($wargaBelumBayar->isEmpty())
                <div class="alert alert-info">
                    Semua warga sudah membayar iuran untuk periode ini.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Warga</th>
                                <th>No. Rumah</th>
                                <th>Blok/RT</th>
                                <th>No. HP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($wargaBelumBayar as $key => $warga)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $warga->nama }}</td>
                                <td>{{ $warga->no_rumah }}</td>
                                <td>{{ $warga->blok_rt }}</td>
                                <td>
                                    <span class="no-hp-masked">{{ $warga->no_hp_masked }}</span>
                                    <button class="btn btn-sm btn-link show-phone-btn" data-id="{{ $warga->id }}">
                                        <i class="fas fa-eye"></i> Lihat
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Load required JavaScript libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
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

<style>
.no-hp-masked {
    font-family: monospace;
    letter-spacing: 1px;
}
</style>
@endpush