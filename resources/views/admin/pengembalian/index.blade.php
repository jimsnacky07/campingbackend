@extends('admin.layout.main')

@section('title', 'Pengembalian Barang')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Pengembalian Barang</h4>
        <a href="{{ route('admin.pengembalian.create') }}" class="btn btn-primary btn-sm">Input Pengembalian</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>ID Pengembalian</th>
                    <th>ID Sewa</th>
                    <th>Pelanggan</th>
                    <th>Tanggal</th>
                    <th>Total Denda</th>
                </tr>
                </thead>
                <tbody>
                @forelse($pengembalian as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->sewa->id }}</td>
                        <td>{{ $item->sewa->pelanggan->user->nama ?? '-' }}</td>
                        <td>{{ $item->tanggal_pengembalian }}</td>
                        <td>Rp {{ number_format($item->total_denda, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{ $pengembalian->links() }}
        </div>
    </div>
@endsection


