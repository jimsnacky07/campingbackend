@extends('admin.layout.main')

@section('title', 'Detail Transaksi')

@section('content')
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h4>Detail Transaksi #{{ $sewa->id }}</h4>
        <a href="{{ route('admin.transaksi.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">Pelanggan</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Nama:</strong> {{ $sewa->pelanggan->user->nama ?? '-' }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $sewa->pelanggan->user->email ?? '-' }}</p>
                    <p class="mb-1"><strong>Telepon:</strong> {{ $sewa->pelanggan->telp }}</p>
                    <p class="mb-0"><strong>Alamat:</strong> {{ $sewa->pelanggan->alamat }}</p>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-header">Status Transaksi</div>
                <div class="card-body">
                    <p>Status saat ini: <strong>{{ ucfirst($sewa->status) }}</strong></p>
                    <form method="POST" action="{{ route('admin.transaksi.update-status', $sewa) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Ubah Status</label>
                            <select name="status" class="form-select">
                                @foreach(['pending','dibayar','dipinjam','dikembalikan','batal'] as $status)
                                    <option value="{{ $status }}" {{ $sewa->status === $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">Detail Barang</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th>Barang</th>
                            <th>Qty</th>
                            <th>Harga/Hari</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($sewa->detailSewa as $detail)
                            <tr>
                                <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                                <td>{{ $detail->qty }}</td>
                                <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-header">Informasi Sewa</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Tanggal Sewa:</strong> {{ $sewa->tanggal_sewa }}</p>
                    <p class="mb-1"><strong>Tanggal Kembali:</strong> {{ $sewa->tanggal_kembali }}</p>
                    <p class="mb-1"><strong>Total Harga:</strong> Rp {{ number_format($sewa->total_harga, 0, ',', '.') }}</p>
                    <p class="mb-0"><strong>Catatan:</strong> {{ $sewa->catatan ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">Foto KTP</div>
                <div class="card-body text-center">
                    @if($sewa->foto_ktp)
                        <img src="{{ asset('storage/' . $sewa->foto_ktp) }}" alt="Foto KTP" class="img-fluid rounded shadow-sm" style="max-height: 400px;">
                        <div class="mt-2">
                            <a href="{{ asset('storage/' . $sewa->foto_ktp) }}" target="_blank" class="btn btn-outline-primary btn-sm">Lihat Full Size</a>
                        </div>
                    @else
                        <div class="py-4 text-muted">
                            <p class="mb-0">Belum ada foto KTP yang diunggah</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">Bukti Pembayaran</div>
                <div class="card-body text-center">
                    @if($sewa->bukti_bayar)
                        <img src="{{ asset('storage/' . $sewa->bukti_bayar) }}" alt="Bukti Bayar" class="img-fluid rounded shadow-sm" style="max-height: 400px;">
                        <div class="mt-2">
                            <a href="{{ asset('storage/' . $sewa->bukti_bayar) }}" target="_blank" class="btn btn-outline-success btn-sm">Lihat Full Size</a>
                        </div>
                    @else
                        <div class="py-4 text-muted">
                            <p class="mb-0">Belum ada bukti pembayaran yang diunggah</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection


