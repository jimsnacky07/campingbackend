<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sewa;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        $sewa = Sewa::with('pelanggan.user')
            ->whereNotNull('bukti_bayar')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('admin.pembayaran.index', compact('sewa'));
    }

    public function show(Sewa $sewa)
    {
        $sewa->load(['pelanggan.user', 'detailSewa.barang']);

        return view('admin.pembayaran.validasi', compact('sewa'));
    }

    public function validatePayment(Request $request, Sewa $sewa)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,dibayar,dipinjam,dikembalikan,batal'],
            'catatan' => ['nullable', 'string'],
        ]);

        $sewa->update([
            'status' => $validated['status'],
            'catatan' => $validated['catatan'] ?? $sewa->catatan,
        ]);

        return redirect()->route('admin.pembayaran.index')->with('success', 'Pembayaran diperbarui.');
    }
}


