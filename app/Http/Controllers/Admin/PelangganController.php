<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggan::with('user');

        if ($search = $request->query('q')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telp', 'like', "%{$search}%");
            });
        }

        $pelanggan = $query->paginate(10)->withQueryString();

        return view('admin.pelanggan.index', compact('pelanggan'));
    }

    public function show(Pelanggan $pelanggan)
    {
        $pelanggan->load(['user', 'sewa.detailSewa.barang', 'sewa.pengembalian']);

        return view('admin.pelanggan.show', compact('pelanggan'));
    }
}


