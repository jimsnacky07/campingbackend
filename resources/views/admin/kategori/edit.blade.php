@extends('admin.layout.main')

@section('title', 'Edit Kategori')

@section('content')
    <div class="mb-3">
        <h4>Edit Kategori</h4>
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
            <form action="{{ route('admin.kategori.update', $kategori) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nama_kategori" class="form-label">Nama Kategori</label>
                    <input type="text" class="form-control" id="nama_kategori" name="nama_kategori"
                           value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection


