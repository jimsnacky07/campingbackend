@extends('admin.layout.main')

@section('title', 'Laporan Barang Terlaris')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Barang Paling Sering Disewa</h4>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form class="row g-3" method="GET" action="{{ route('admin.laporan.barang-terlaris') }}">
                <div class="col-md-3">
                    <label class="form-label">Jumlah Data</label>
                    <input type="number" name="limit" class="form-control" min="1" max="50" value="{{ $limit }}">
                </div>
                <div class="col-md-3 align-self-end">
                    <button class="btn btn-primary" type="submit">Tampilkan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Barang</th>
                    <th>Total Disewa</th>
                    <th>Total Pendapatan</th>
                </tr>
                </thead>
                <tbody>
                @forelse($barang as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $item->total_disewa }}</td>
                        <td>Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada data</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection


