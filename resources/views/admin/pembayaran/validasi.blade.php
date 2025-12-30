@extends('admin.layout.main')

@section('title', 'Validasi Pembayaran')

@section('content')
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h4>Validasi Pembayaran - Transaksi #{{ $sewa->id }}</h4>
        <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">Bukti Transfer</div>
                <div class="card-body">
                    @if($sewa->bukti_bayar)
                        <a href="{{ asset('storage/'.$sewa->bukti_bayar) }}" target="_blank">
                            <img src="{{ asset('storage/'.$sewa->bukti_bayar) }}" class="img-fluid" alt="Bukti bayar">
                        </a>
                    @else
                        <p class="text-muted">Belum ada bukti bayar</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">Detail Pembayaran</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Pelanggan:</strong> {{ $sewa->pelanggan->user->nama ?? '-' }}</p>
                    <p class="mb-1"><strong>Total:</strong> Rp {{ number_format($sewa->total_harga, 0, ',', '.') }}</p>
                    <p class="mb-1"><strong>Status:</strong> {{ ucfirst($sewa->status) }}</p>
                    <p class="mb-3"><strong>Catatan:</strong> {{ $sewa->catatan ?? '-' }}</p>

                    <form method="POST" action="{{ route('admin.pembayaran.validate', $sewa) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Status Baru</label>
                            <select name="status" class="form-select">
                                @foreach(['pending','dibayar','dipinjam','dikembalikan','batal'] as $status)
                                    <option value="{{ $status }}" {{ $sewa->status === $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea name="catatan" class="form-control" rows="3">{{ old('catatan', $sewa->catatan) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


