<?php

namespace App\Http\Controllers;

use App\Models\MetodePembayaran;
use Illuminate\Http\Request;

class MetodePembayaranController extends Controller
{
    public function index()
    {
        $metode = MetodePembayaran::orderBy('nama_bank')->get();

        return response()->json($metode);
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'nama_bank' => ['required', 'string', 'max:255'],
            'no_rek' => ['required', 'string', 'max:255'],
            'nama_pemilik' => ['required', 'string', 'max:255'],
        ]);

        $metode = MetodePembayaran::create($validated);

        return response()->json($metode, 201);
    }

    public function show(MetodePembayaran $metodePembayaran)
    {
        return response()->json($metodePembayaran);
    }

    public function update(Request $request, MetodePembayaran $metodePembayaran)
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'nama_bank' => ['sometimes', 'string', 'max:255'],
            'no_rek' => ['sometimes', 'string', 'max:255'],
            'nama_pemilik' => ['sometimes', 'string', 'max:255'],
        ]);

        $metodePembayaran->update($validated);

        return response()->json($metodePembayaran);
    }

    public function destroy(Request $request, MetodePembayaran $metodePembayaran)
    {
        $this->authorizeAdmin($request);

        $metodePembayaran->delete();

        return response()->json(null, 204);
    }

    protected function authorizeAdmin(Request $request): void
    {
        if (! $request->user() || $request->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang boleh melakukan aksi ini');
        }
    }
}


