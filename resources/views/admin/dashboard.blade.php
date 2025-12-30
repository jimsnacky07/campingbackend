@extends('admin.layout.main')

@section('title', 'Dashboard')

@section('content')
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total Barang</h6>
                    <h3>{{ $totalBarang }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total Penyewaan</h6>
                    <h3>{{ $totalPenyewaan }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total Pengembalian</h6>
                    <h3>{{ $totalPengembalian }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total Pelanggan</h6>
                    <h3>{{ $totalPelanggan }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    Penyewaan Hari Ini
                </div>
                <div class="card-body">
                    <h4>{{ $penyewaanHariIni }}</h4>
                    <p class="mb-0 text-muted">Transaksi pada tanggal {{ now()->format('d-m-Y') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    Transaksi Terbaru
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pelanggan</th>
                            <th>Tanggal Sewa</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($latestSewa as $sewa)
                            <tr>
                                <td>{{ $sewa->id }}</td>
                                <td>{{ $sewa->pelanggan->user->nama ?? '-' }}</td>
                                <td>{{ $sewa->tanggal_sewa }}</td>
                                <td>{{ ucfirst($sewa->status) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada transaksi</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


