@extends('admin.layout.main')

@section('title', 'Validasi Pembayaran')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Validasi Pembayaran</h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>ID Sewa</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Bukti</th>
                    <th>Opsi</th>
                </tr>
                </thead>
                <tbody>
                @forelse($sewa as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->pelanggan->user->nama ?? '-' }}</td>
                        <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($item->status) }}</td>
                        <td>
                            @if($item->bukti_bayar)
                                <a href="{{ asset('storage/'.$item->bukti_bayar) }}" target="_blank">Lihat</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.pembayaran.show', $item) }}" class="btn btn-sm btn-info">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada pembayaran yang perlu divalidasi</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{ $sewa->links() }}
        </div>
    </div>
@endsection


