<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAdmin($request);

        $query = Pelanggan::with('user');

        if ($search = $request->query('q')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pelanggan = $query->orderBy('id', 'desc')->paginate(10);

        return response()->json($pelanggan);
    }

    public function show(Request $request, Pelanggan $pelanggan)
    {
        $this->authorizeAdmin($request);

        $pelanggan->load('user');

        return response()->json($pelanggan);
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'nik' => ['sometimes', 'string', 'max:100', 'unique:pelanggan,nik,' . $pelanggan->id],
            'alamat' => ['sometimes', 'string'],
            'telp' => ['sometimes', 'string', 'max:50'],
        ]);

        $pelanggan->update($validated);

        return response()->json($pelanggan);
    }

    public function destroy(Request $request, Pelanggan $pelanggan)
    {
        $this->authorizeAdmin($request);

        $pelanggan->delete();

        return response()->json(null, 204);
    }

    protected function authorizeAdmin(Request $request): void
    {
        if (! $request->user() || $request->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang boleh melakukan aksi ini');
        }
    }
}


