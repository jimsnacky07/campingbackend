@extends('admin.layout.main')

@section('title', 'Kategori Barang')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Kategori Barang</h4>
        <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary btn-sm">Tambah Kategori</a>
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
                    <th>#</th>
                    <th>Nama Kategori</th>
                    <th>Opsi</th>
                </tr>
                </thead>
                <tbody>
                @forelse($kategori as $index => $item)
                    <tr>
                        <td>{{ $kategori->firstItem() + $index }}</td>
                        <td>{{ $item->nama_kategori }}</td>
                        <td>
                            <a href="{{ route('admin.kategori.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.kategori.destroy', $item) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Belum ada data</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{ $kategori->links() }}
        </div>
    </div>
@endsection


