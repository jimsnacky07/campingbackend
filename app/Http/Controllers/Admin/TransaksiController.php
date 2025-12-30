<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sewa;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Sewa::with(['pelanggan.user'])->orderBy('created_at', 'desc');

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $sewa = $query->paginate(10)->withQueryString();

        return view('admin.transaksi.index', [
            'sewa' => $sewa,
            'status' => $status,
        ]);
    }

    public function show(Sewa $sewa)
    {
        $sewa->load(['pelanggan.user', 'detailSewa.barang', 'pengembalian.detailPengembalian']);

        return view('admin.transaksi.detail', compact('sewa'));
    }

    public function updateStatus(Request $request, Sewa $sewa)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,dibayar,dipinjam,dikembalikan,batal'],
        ]);

        $sewa->update(['status' => $validated['status']]);

        return back()->with('success', 'Status transaksi diperbarui.');
    }
}


