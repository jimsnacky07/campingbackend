<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    private function getPelangganId()
    {
        $pelanggan = Pelanggan::where('id_user', Auth::id())->first();
        return $pelanggan ? $pelanggan->id : null;
    }

    public function index()
    {
        $id_pelanggan = $this->getPelangganId();
        if (!$id_pelanggan) {
            return response()->json(['message' => 'Profile pelanggan tidak ditemukan'], 404);
        }

        $items = Keranjang::with('barang')
            ->where('id_pelanggan', $id_pelanggan)
            ->get();

        return response()->json(['data' => $items]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang,id',
            'qty' => 'required|integer|min:1',
        ]);

        $id_pelanggan = $this->getPelangganId();
        if (!$id_pelanggan) {
            return response()->json(['message' => 'Profile pelanggan tidak ditemukan'], 404);
        }

        $item = Keranjang::updateOrCreate(
            ['id_pelanggan' => $id_pelanggan, 'id_barang' => $request->id_barang],
            ['qty' => $request->qty]
        );

        return response()->json([
            'message' => 'Item berhasil ditambahkan ke keranjang',
            'data' => $item->load('barang')
        ]);
    }

    public function update(Request $request, $id_barang)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        $id_pelanggan = $this->getPelangganId();
        if (!$id_pelanggan) {
            return response()->json(['message' => 'Profile pelanggan tidak ditemukan'], 404);
        }

        $item = Keranjang::where('id_pelanggan', $id_pelanggan)
            ->where('id_barang', $id_barang)
            ->first();

        if (!$item) {
            return response()->json(['message' => 'Item tidak ditemukan'], 404);
        }

        $item->update(['qty' => $request->qty]);

        return response()->json([
            'message' => 'Qty berhasil diupdate',
            'data' => $item->load('barang')
        ]);
    }

    public function destroy($id_barang)
    {
        $id_pelanggan = $this->getPelangganId();
        if (!$id_pelanggan) {
            return response()->json(['message' => 'Profile pelanggan tidak ditemukan'], 404);
        }

        Keranjang::where('id_pelanggan', $id_pelanggan)
            ->where('id_barang', $id_barang)
            ->delete();

        return response()->json(['message' => 'Item berhasil dihapus dari keranjang']);
    }

    public function clear()
    {
        $id_pelanggan = $this->getPelangganId();
        if (!$id_pelanggan) {
            return response()->json(['message' => 'Profile pelanggan tidak ditemukan'], 404);
        }

        Keranjang::where('id_pelanggan', $id_pelanggan)->delete();

        return response()->json(['message' => 'Keranjang berhasil dikosongkan']);
    }
}
