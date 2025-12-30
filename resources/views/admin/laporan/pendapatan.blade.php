@extends('admin.layout.main')

@section('title', 'Laporan Pendapatan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Laporan Pendapatan Bulanan</h4>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form class="row g-3" method="GET" action="{{ route('admin.laporan.pendapatan') }}">
                <div class="col-md-3">
                    <label class="form-label">Tahun</label>
                    <input type="number" name="tahun" class="form-control" value="{{ $tahun }}" min="2000" max="{{ now()->year }}">
                </div>
                <div class="col-md-3 align-self-end">
                    <button class="btn btn-primary" type="submit">Tampilkan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body text-center">
            <h6 class="text-muted">Total Pendapatan {{ $tahun }}</h6>
            <h3>Rp {{ number_format($totalTahun, 0, ',', '.') }}</h3>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Total Pendapatan</th>
                </tr>
                </thead>
                <tbody>
                @forelse($data as $row)
                    <tr>
                        <td>{{ $row->nama_bulan }}</td>
                        <td>Rp {{ number_format($row->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center">Belum ada data untuk tahun ini</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection


