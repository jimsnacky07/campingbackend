<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailSewa;
use App\Models\Sewa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SewaController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAdmin($request);

        $query = Sewa::with(['pelanggan.user', 'detailSewa.barang'])->orderBy('created_at', 'desc');

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $sewa = $query->paginate(10);

        return response()->json($sewa);
    }

    public function mySewa(Request $request)
    {
        $pelanggan = $request->user()->pelanggan;

        if (! $pelanggan) {
            return response()->json([
                'message' => 'Data pelanggan belum lengkap',
            ], 422);
        }

        $sewa = Sewa::with(['detailSewa.barang', 'pengembalian'])
            ->where('id_pelanggan', $pelanggan->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($sewa);
    }

    public function store(Request $request)
    {
        $pelanggan = $request->user()->pelanggan;

        if (! $pelanggan) {
            return response()->json([
                'message' => 'Data pelanggan belum lengkap',
            ], 422);
        }

        $validated = $request->validate([
            'tanggal_sewa' => ['required', 'date'],
            'tanggal_kembali' => ['required', 'date', 'after:tanggal_sewa'],
            'catatan' => ['nullable', 'string'],
            'foto_ktp' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id_barang' => ['required', 'exists:barang,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        $tanggalSewa = Carbon::parse($validated['tanggal_sewa']);
        $tanggalKembali = Carbon::parse($validated['tanggal_kembali']);
        $lamaSewa = max(1, (int) $tanggalSewa->diffInDays($tanggalKembali));

        \App\Helpers\DebugHelper::info('Sewa Calculation Debug', [
            'tanggal_sewa' => $validated['tanggal_sewa'],
            'tanggal_kembali' => $validated['tanggal_kembali'],
            'lama_sewa' => $lamaSewa
        ]);

        DB::beginTransaction();

        try {
            $barangList = Barang::whereIn('id', collect($validated['items'])->pluck('id_barang'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $totalHarga = 0;

            foreach ($validated['items'] as $item) {
                $barang = $barangList->get($item['id_barang']);

                if (! $barang) {
                    throw new \RuntimeException('Barang tidak ditemukan');
                }

                if ($barang->stok < $item['qty']) {
                    throw new \RuntimeException("Stok barang {$barang->nama_barang} tidak mencukupi");
                }

                $subtotal = $barang->harga_sewa * (int)$item['qty'] * $lamaSewa;
                $totalHarga += $subtotal;

                \App\Helpers\DebugHelper::info('Item subtotal debug', [
                    'barang_id' => $barang->id,
                    'harga_sewa' => $barang->harga_sewa,
                    'qty' => $item['qty'],
                    'subtotal' => $subtotal
                ]);

                $barang->decrement('stok', $item['qty']);
            }

            \App\Helpers\DebugHelper::info('Total Harga Final', ['total' => $totalHarga]);

            // Handle foto KTP upload
            $fotoKtpPath = null;
            if ($request->hasFile('foto_ktp')) {
                $fotoKtpPath = $request->file('foto_ktp')->store('ktp', 'public');
            }

            if ($totalHarga <= 0) {
                throw new \RuntimeException('Total harga sewa tidak boleh Rp 0. Periksa harga barang.');
            }

            $sewa = Sewa::create([
                'id_pelanggan' => $pelanggan->id,
                'tanggal_sewa' => $tanggalSewa->format('Y-m-d'),
                'tanggal_kembali' => $tanggalKembali->format('Y-m-d'),
                'total_harga' => $totalHarga,
                'status' => 'pending',
                'catatan' => $validated['catatan'] ?? null,
                'foto_ktp' => $fotoKtpPath,
            ]);

            foreach ($validated['items'] as $item) {
                $barang = $barangList->get($item['id_barang']);

                DetailSewa::create([
                    'id_sewa' => $sewa->id,
                    'id_barang' => $barang->id,
                    'qty' => $item['qty'],
                    'harga' => $barang->harga_sewa,
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json($sewa->load('detailSewa.barang'), 201);
    }

    public function show(Request $request, Sewa $sewa)
    {
        $user = $request->user();

        if ($user->role !== 'admin' && $user->pelanggan?->id !== $sewa->id_pelanggan) {
            abort(403);
        }

        $sewa->load(['pelanggan.user', 'detailSewa.barang', 'pengembalian.detailPengembalian']);

        return response()->json($sewa);
    }

    public function updateStatus(Request $request, Sewa $sewa)
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,dibayar,dipinjam,dikembalikan,batal'],
        ]);

        $sewa->update(['status' => $validated['status']]);

        return response()->json($sewa);
    }

    protected function authorizeAdmin(Request $request): void
    {
        if (! $request->user() || $request->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang boleh melakukan aksi ini');
        }
    }
}


