@extends('admin.layout.main')

@section('title', 'Data Pelanggan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Data Pelanggan</h4>
    </div>

    <form class="row g-2 mb-3" method="GET" action="{{ route('admin.pelanggan.index') }}">
        <div class="col-md-4">
            <input type="text" name="q" class="form-control" placeholder="Cari nama / email / telp" value="{{ request('q') }}">
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
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                    <th>Opsi</th>
                </tr>
                </thead>
                <tbody>
                @forelse($pelanggan as $index => $item)
                    <tr>
                        <td>{{ $pelanggan->firstItem() + $index }}</td>
                        <td>{{ $item->user->nama ?? '-' }}</td>
                        <td>{{ $item->user->email ?? '-' }}</td>
                        <td>{{ $item->telp }}</td>
                        <td>{{ $item->alamat }}</td>
                        <td>
                            <a href="{{ route('admin.pelanggan.show', $item) }}" class="btn btn-sm btn-info">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{ $pelanggan->links() }}
        </div>
    </div>
@endsection


