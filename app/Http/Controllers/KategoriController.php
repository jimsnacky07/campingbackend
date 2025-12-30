<?php

namespace App\Http\Controllers;

use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $query = KategoriBarang::query();

        if ($search = $request->query('q')) {
            $query->where('nama_kategori', 'like', "%{$search}%");
        }

        $kategori = $query->orderBy('nama_kategori')->paginate(10);

        return response()->json($kategori);
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:255'],
        ]);

        $kategori = KategoriBarang::create($validated);

        return response()->json($kategori, 201);
    }

    public function show(KategoriBarang $kategori)
    {
        return response()->json($kategori);
    }

    public function update(Request $request, KategoriBarang $kategori)
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:255'],
        ]);

        $kategori->update($validated);

        return response()->json($kategori);
    }

    public function destroy(Request $request, KategoriBarang $kategori)
    {
        $this->authorizeAdmin($request);

        $kategori->delete();

        return response()->json(null, 204);
    }

    protected function authorizeAdmin(Request $request): void
    {
        if (! $request->user() || $request->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang boleh melakukan aksi ini');
        }
    }
}


