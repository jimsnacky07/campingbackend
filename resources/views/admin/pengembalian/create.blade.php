@extends('admin.layout.main')

@section('title', 'Input Pengembalian')

@section('content')
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h4>Input Pengembalian</h4>
        <a href="{{ route('admin.pengembalian.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.pengembalian.create') }}" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label">Pilih Transaksi Dipinjam</label>
                    <select name="sewa_id" class="form-select" onchange="this.form.submit()">
                        @foreach($sewaDipinjam as $sewa)
                            <option value="{{ $sewa->id }}" {{ optional($selectedSewa)->id === $sewa->id ? 'selected' : '' }}>
                                #{{ $sewa->id }} - {{ $sewa->pelanggan->user->nama ?? '-' }} ({{ $sewa->tanggal_sewa }} s/d {{ $sewa->tanggal_kembali }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Pengembalian</label>
                    <input type="date" class="form-control" name="tanggal_pengembalian_preview"
                           value="{{ old('tanggal_pengembalian', now()->toDateString()) }}" disabled>
                </div>
            </form>
        </div>
    </div>

    @if($selectedSewa)
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.pengembalian.store') }}">
                    @csrf
                    <input type="hidden" name="id_sewa" value="{{ $selectedSewa->id }}">
                    <div class="mb-3">
                        <label class="form-label">Tanggal Pengembalian</label>
                        <input type="date" class="form-control" name="tanggal_pengembalian"
                               value="{{ old('tanggal_pengembalian', now()->toDateString()) }}" required>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Qty</th>
                                <th>Kondisi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($selectedSewa->detailSewa as $detail)
                                <tr>
                                    <td>
                                        {{ $detail->barang->nama_barang ?? '-' }}
                                        <input type="hidden" name="items[{{ $loop->index }}][id_barang]" value="{{ $detail->id_barang }}">
                                    </td>
                                    <td>{{ $detail->qty }}</td>
                                    <td>
                                        <select name="items[{{ $loop->index }}][kondisi]" class="form-select" required>
                                            @foreach(['baik','rusak ringan','rusak berat','hilang'] as $kondisi)
                                                <option value="{{ $kondisi }}">{{ ucfirst($kondisi) }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Pengembalian</button>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            Belum ada transaksi dengan status dipinjam.
        </div>
    @endif
@endsection


