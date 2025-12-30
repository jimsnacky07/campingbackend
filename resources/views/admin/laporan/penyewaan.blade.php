@extends('admin.layout.main')

@section('title', 'Laporan Penyewaan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Laporan Penyewaan</h4>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form class="row g-3" method="GET" action="{{ route('admin.laporan.penyewaan') }}">
                <div class="col-md-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control" value="{{ $filter['tanggal_mulai'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control" value="{{ $filter['tanggal_selesai'] ?? '' }}">
                </div>
                <div class="col-md-3 align-self-end">
                    <button class="btn btn-primary" type="submit">Terapkan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-6">
                    <h6 class="text-muted">Total Transaksi</h6>
                    <h3>{{ $totalTransaksi }}</h3>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Total Pendapatan</h6>
                    <h3>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Pelanggan</th>
                    <th>Tanggal Sewa</th>
                    <th>Tanggal Kembali</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @forelse($data as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->pelanggan->user->nama ?? '-' }}</td>
                        <td>{{ $item->tanggal_sewa }}</td>
                        <td>{{ $item->tanggal_kembali }}</td>
                        <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($item->status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data untuk periode ini</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection


