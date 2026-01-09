<?php

namespace App\Http\Controllers;

use App\Models\Sewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function uploadBukti(Request $request, Sewa $sewa)
    {
        \Log::info('Upload bukti called', [
            'sewa_id' => $sewa->id,
            'has_file' => $request->hasFile('bukti_bayar'),
            'has_base64' => $request->has('bukti_bayar_base64'),
            'request_data' => $request->all(),
        ]);

        $user = $request->user();

        if ($user->role !== 'admin' && $user->pelanggan?->id !== $sewa->id_pelanggan) {
            abort(403);
        }

        // Handle base64 upload
        if ($request->has('bukti_bayar_base64')) {
            $validated = $request->validate([
                'bukti_bayar_base64' => ['required', 'string'],
                'filename' => ['nullable', 'string'],
                'mime_type' => ['nullable', 'string'],
            ]);

            try {
                $base64Image = $validated['bukti_bayar_base64'];
                $filename = $validated['filename'] ?? 'bukti_' . time() . '.jpg';
                
                // Decode base64
                $imageData = base64_decode($base64Image);
                
                // Generate path
                $path = 'bukti-bayar/' . $filename;
                
                // Delete old file if exists
                if ($sewa->bukti_bayar) {
                    Storage::disk('public')->delete($sewa->bukti_bayar);
                }
                
                // Save file
                Storage::disk('public')->put($path, $imageData);
                
                // Update database
                $sewa->update(['bukti_bayar' => $path]);
                
                \Log::info('Bukti uploaded successfully (base64)', ['path' => $path]);
                
                return response()->json([
                    'message' => 'Bukti pembayaran berhasil diunggah',
                    'sewa' => $sewa->fresh(),
                ]);
            } catch (\Exception $e) {
                \Log::error('Upload failed', ['error' => $e->getMessage()]);
                return response()->json(['message' => 'Gagal menyimpan file: ' . $e->getMessage()], 500);
            }
        }

        // Handle traditional file upload (fallback)
        $validated = $request->validate([
            'bukti_bayar' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        if ($request->hasFile('bukti_bayar')) {
            if ($sewa->bukti_bayar) {
                Storage::disk('public')->delete($sewa->bukti_bayar);
            }
            $path = $request->file('bukti_bayar')->store('bukti-bayar', 'public');
            $sewa->update(['bukti_bayar' => $path]);
            
            \Log::info('Bukti uploaded successfully', ['path' => $path]);
        }

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


