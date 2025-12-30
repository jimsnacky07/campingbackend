@extends('admin.layout.main')

@section('title', 'Data Barang')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Data Barang</h4>
        <a href="{{ route('admin.barang.create') }}" class="btn btn-primary btn-sm">Tambah Barang</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form class="row g-2 mb-3" method="GET" action="{{ route('admin.barang.index') }}">
        <div class="col-md-4">
            <input type="text" name="q" class="form-control" placeholder="Cari nama barang..." value="{{ request('q') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-primary" type="submit">Cari</button>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Harga Sewa / Hari</th>
                    <th>Stok</th>
                    <th>Opsi</th>
                </tr>
                </thead>
                <tbody>
                @forelse($barang as $index => $item)
                    <tr>
                        <td>{{ $barang->firstItem() + $index }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                        <td>Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}</td>
                        <td>{{ $item->stok }}</td>
                        <td>
                            <a href="{{ route('admin.barang.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.barang.destroy', $item) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus barang ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{ $barang->links() }}
        </div>
    </div>
@endsection


