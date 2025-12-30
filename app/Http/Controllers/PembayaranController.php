<?php

namespace App\Http\Controllers;

use App\Models\Sewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function uploadBukti(Request $request, Sewa $sewa)
    {
        $user = $request->user();

        if ($user->role !== 'admin' && $user->pelanggan?->id !== $sewa->id_pelanggan) {
            abort(403);
        }

        $validated = $request->validate([
            'bukti_bayar' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        if ($sewa->bukti_bayar) {
            Storage::disk('public')->delete($sewa->bukti_bayar);
        }

        $path = $request->file('bukti_bayar')->store('bukti-bayar', 'public');

        $sewa->update([
            'bukti_bayar' => $path,
            'status' => 'dibayar',
        ]);

        return response()->json([
            'message' => 'Bukti pembayaran berhasil diunggah',
            'sewa' => $sewa->fresh(),
        ]);
    }

    public function validasi(Request $request, Sewa $sewa)
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,dibayar,dipinjam,dikembalikan,batal'],
            'catatan' => ['nullable', 'string'],
        ]);

        $sewa->update([
            'status' => $validated['status'],
            'catatan' => $validated['catatan'] ?? $sewa->catatan,
        ]);

        return response()->json([
            'message' => 'Status pembayaran diperbarui',
            'sewa' => $sewa->fresh(),
        ]);
    }

    protected function authorizeAdmin(Request $request): void
    {
        if (! $request->user() || $request->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang boleh melakukan aksi ini');
        }
    }
}


