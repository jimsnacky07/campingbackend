<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with('kategori');

        // Search by nama barang
        if ($search = $request->query('search') ?? $request->query('q')) {
            $query->where('nama_barang', 'like', "%{$search}%");
        }

        // Filter by kategori
        if ($kategoriId = $request->query('kategori') ?? $request->query('kategori_id')) {
            $query->where('id_kategori', $kategoriId);
        }

        // Filter by harga minimum
        if ($minHarga = $request->query('min_harga')) {
            $query->where('harga_sewa', '>=', $minHarga);
        }

        // Filter by harga maximum
        if ($maxHarga = $request->query('max_harga')) {
            $query->where('harga_sewa', '<=', $maxHarga);
        }

        // Sorting
        $sortBy = $request->query('sort', 'nama_barang');
        $order = $request->query('order', 'asc');
        
        $allowedSorts = ['nama_barang', 'harga_sewa', 'stok', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $order === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('nama_barang', 'asc');
        }

        $barang = $query->paginate(10);

        return response()->json($barang);
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'id_kategori' => ['required', 'exists:kategori_barang,id'],
            'nama_barang' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'harga_sewa' => ['required', 'integer', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'foto' => ['nullable', 'string'],
        ]);

        $barang = Barang::create($validated);

        return response()->json($barang, 201);
    }

    public function show(Barang $barang)
    {
        $barang->load('kategori');

        return response()->json($barang);
    }

    public function update(Request $request, Barang $barang)
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'id_kategori' => ['sometimes', 'exists:kategori_barang,id'],
            'nama_barang' => ['sometimes', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'harga_sewa' => ['sometimes', 'integer', 'min:0'],
            'stok' => ['sometimes', 'integer', 'min:0'],
            'foto' => ['nullable', 'string'],
        ]);

        $barang->update($validated);

        return response()->json($barang);
    }

    public function destroy(Request $request, Barang $barang)
    {
        $this->authorizeAdmin($request);

        $barang->delete();

        return response()->json(null, 204);
    }

    protected function authorizeAdmin(Request $request): void
    {
        if (! $request->user() || $request->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang boleh melakukan aksi ini');
        }
    }
}


