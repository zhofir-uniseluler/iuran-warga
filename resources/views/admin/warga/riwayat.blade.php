@extends('layouts.app')

@section('title', 'Riwayat Iuran Warga')

@section('content')
<div class="container-fluid">
   <div class="card-header py-3 d-flex justify-content-between align-items-center">
    <h6 class="m-0 font-weight-bold text-primary">Riwayat Iuran {{ $warga->nama }}</h6>
    <div>
        <form method="GET" action="{{ route('admin.warga.riwayat', $warga->id) }}" class="form-inline">
            <input type="month" name="periode" class="form-control mr-2" 
                   value="{{ $periode }}" min="2020-01" max="{{ now()->format('Y-m') }}">
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            <a href="{{ route('admin.data_warga') }}" class="btn btn-secondary btn-sm ml-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </form>
    </div>
</div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Informasi Warga</h5>
                            <p class="mb-1"><strong>Nama:</strong> {{ $warga->nama }}</p>
                            <p class="mb-1"><strong>No. Rumah:</strong> {{ $warga->no_rumah }}</p>
                            <p class="mb-1"><strong>Blok/RT:</strong> {{ $warga->blok_rt }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total Iuran 12 Bulan Terakhir</h5>
                            <p class="mb-1"><strong>Cash:</strong> Rp {{ number_format(collect($riwayat)->sum('cash'), 0, ',', '.') }}</p>
                            <p class="mb-1"><strong>Transfer:</strong> Rp {{ number_format(collect($riwayat)->sum('transfer'), 0, ',', '.') }}</p>
                            <p class="mb-0"><strong>Total:</strong> Rp {{ number_format(collect($riwayat)->sum('total'), 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Bulan</th>
                            <th>Cash</th>
                            <th>Transfer</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riwayat as $item)
                        <tr>
                            <td>{{ $item['bulan'] }}</td>
                            <td>Rp {{ number_format($item['cash'], 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item['transfer'], 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <canvas id="riwayatChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('riwayatChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [
                    {
                        label: 'Cash',
                        data: @json(array_column($riwayat, 'cash')),
                        backgroundColor: '#4e73df'
                    },
                    {
                        label: 'Transfer',
                        data: @json(array_column($riwayat, 'transfer')),
                        backgroundColor: '#1cc88a'
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += 'Rp ' + context.parsed.y.toLocaleString();
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush