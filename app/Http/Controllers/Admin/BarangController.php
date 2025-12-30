<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with('kategori')->orderBy('nama_barang');

        if ($search = $request->query('q')) {
            $query->where('nama_barang', 'like', "%{$search}%");
        }

        $barang = $query->paginate(10)->withQueryString();

        return view('admin.barang.index', compact('barang'));
    }

    public function create()
    {
        $kategori = KategoriBarang::orderBy('nama_kategori')->get();

        return view('admin.barang.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kategori' => ['required', 'exists:kategori_barang,id'],
            'nama_barang' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'harga_sewa' => ['required', 'integer', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'foto' => ['nullable', 'url'],
        ]);

        Barang::create($validated);

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Barang $barang)
    {
        $kategori = KategoriBarang::orderBy('nama_kategori')->get();

        return view('admin.barang.edit', compact('barang', 'kategori'));
    }

    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'id_kategori' => ['required', 'exists:kategori_barang,id'],
            'nama_barang' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'harga_sewa' => ['required', 'integer', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'foto' => ['nullable', 'url'],
        ]);

        $barang->update($validated);

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        try {
            $barang->delete();

            return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil dihapus.');
        } catch (\Throwable $e) {
            return redirect()->route('admin.barang.index')->with('error', 'Barang tidak dapat dihapus karena sedang dipakai.');
        }
    }
}


