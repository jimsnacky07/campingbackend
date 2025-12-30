@extends('admin.layout.main')

@section('title', 'Tambah Barang')

@section('content')
    <div class="mb-3">
        <h4>Tambah Barang</h4>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.barang.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="id_kategori" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $item)
                            <option value="{{ $item->id }}" {{ old('id_kategori') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" class="form-control" name="nama_barang" value="{{ old('nama_barang') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Harga Sewa per Hari</label>
                    <input type="number" class="form-control" name="harga_sewa" value="{{ old('harga_sewa') }}" min="0" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Stok</label>
                    <input type="number" class="form-control" name="stok" value="{{ old('stok') }}" min="0" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">URL Foto (opsional)</label>
                    <input type="url" class="form-control" name="foto" value="{{ old('foto') }}">
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.barang.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection


