<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = KategoriBarang::orderBy('nama_kategori')->paginate(10);

        return view('admin.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:255'],
        ]);

        KategoriBarang::create($validated);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(KategoriBarang $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, KategoriBarang $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:255'],
        ]);

        $kategori->update($validated);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(KategoriBarang $kategori)
    {
        try {
            $kategori->delete();

            return redirect()->route('admin.kategori.index')
                ->with('success', 'Kategori berhasil dihapus.');
        } catch (\Throwable $e) {
            return redirect()->route('admin.kategori.index')
                ->with('error', 'Kategori tidak dapat dihapus karena telah digunakan.');
        }
    }
}


