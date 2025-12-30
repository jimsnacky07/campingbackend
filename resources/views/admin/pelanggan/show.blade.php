@extends('admin.layout.main')

@section('title', 'Detail Pelanggan')

@section('content')
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h4>Detail Pelanggan</h4>
        <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>{{ $pelanggan->user->nama ?? '-' }}</h5>
                    <p class="mb-1"><strong>Email:</strong> {{ $pelanggan->user->email ?? '-' }}</p>
                    <p class="mb-1"><strong>Telepon:</strong> {{ $pelanggan->telp }}</p>
                    <p class="mb-1"><strong>NIK:</strong> {{ $pelanggan->nik }}</p>
                    <p class="mb-0"><strong>Alamat:</strong> {{ $pelanggan->alamat }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    Riwayat Sewa
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal Sewa</th>
                            <th>Tanggal Kembali</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($pelanggan->sewa as $sewa)
                            <tr>
                                <td>{{ $sewa->id }}</td>
                                <td>{{ $sewa->tanggal_sewa }}</td>
                                <td>{{ $sewa->tanggal_kembali }}</td>
                                <td>Rp {{ number_format($sewa->total_harga, 0, ',', '.') }}</td>
                                <td>{{ ucfirst($sewa->status) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada transaksi</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


