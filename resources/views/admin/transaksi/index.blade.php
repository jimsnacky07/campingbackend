@extends('admin.layout.main')

@section('title', 'Transaksi Penyewaan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Transaksi Penyewaan</h4>
    </div>

    <form class="row g-2 mb-3" method="GET" action="{{ route('admin.transaksi.index') }}">
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">-- Semua Status --</option>
                @foreach(['pending','dibayar','dipinjam','dikembalikan','batal'] as $item)
                    <option value="{{ $item }}" {{ request('status') === $item ? 'selected' : '' }}>
                        {{ ucfirst($item) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-primary" type="submit">Filter</button>
        </div>
    </form>

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
                    <th>Opsi</th>
                </tr>
                </thead>
                <tbody>
                @forelse($sewa as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->pelanggan->user->nama ?? '-' }}</td>
                        <td>{{ $item->tanggal_sewa }}</td>
                        <td>{{ $item->tanggal_kembali }}</td>
                        <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($item->status) }}</td>
                        <td>
                            <a href="{{ route('admin.transaksi.show', $item) }}" class="btn btn-sm btn-info">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada transaksi</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{ $sewa->links() }}
        </div>
    </div>
@endsection


