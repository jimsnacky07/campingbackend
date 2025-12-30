<?php

namespace App\Http\Controllers;

use App\Models\DetailPengembalian;
use App\Models\Pengembalian;
use App\Models\Sewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    protected array $dendaPersentase = [
        'baik' => 0,
        'rusak ringan' => 0.2,
        'rusak berat' => 0.5,
        'hilang' => 1,
    ];

    public function index(Request $request)
    {
        $this->authorizeAdmin($request);

        $query = Pengembalian::with(['sewa.pelanggan.user', 'detailPengembalian.barang'])
            ->orderBy('created_at', 'desc');

        if ($tanggalAwal = $request->query('tanggal_mulai')) {
            $query->whereDate('tanggal_pengembalian', '>=', $tanggalAwal);
        }

        if ($tanggalAkhir = $request->query('tanggal_selesai')) {
            $query->whereDate('tanggal_pengembalian', '<=', $tanggalAkhir);
        }

        $data = $query->paginate(10);

        return response()->json($data);
    }

    public function show(Request $request, Pengembalian $pengembalian)
    {
        $this->authorizeAdmin($request);

        $pengembalian->load(['sewa.pelanggan.user', 'detailPengembalian.barang']);

        return response()->json($pengembalian);
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'id_sewa' => ['required', 'exists:sewa,id'],
            'tanggal_pengembalian' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id_barang' => ['required', 'exists:barang,id'],
            'items.*.kondisi' => ['required', 'in:baik,rusak ringan,rusak berat,hilang'],
        ]);

        $sewa = Sewa::with('detailSewa.barang')->findOrFail($validated['id_sewa']);

        if ($sewa->status !== 'dipinjam') {
            return response()->json(['message' => 'Pengembalian hanya bisa dibuat untuk sewa berstatus dipinjam'], 422);
        }

        if ($sewa->pengembalian) {
            return response()->json(['message' => 'Pengembalian sudah pernah dicatat'], 422);
        }

        $itemsByBarang = collect($validated['items'])->keyBy('id_barang');

        DB::beginTransaction();

        try {
            $pengembalian = Pengembalian::create([
                'id_sewa' => $sewa->id,
                'tanggal_pengembalian' => $validated['tanggal_pengembalian'],
                'total_denda' => 0,
            ]);

            $totalDenda = 0;

            foreach ($sewa->detailSewa as $detail) {
                $itemInput = $itemsByBarang->get($detail->id_barang);

                if (! $itemInput) {
                    continue;
                }

                $dendaPerItem = $this->hitungDenda($detail->harga, $itemInput['kondisi']);
                $dendaTotalDetail = $dendaPerItem * $detail->qty;

                DetailPengembalian::create([
                    'id_pengembalian' => $pengembalian->id,
                    'id_barang' => $detail->id_barang,
                    'kondisi' => $itemInput['kondisi'],
                    'denda' => $dendaTotalDetail,
                ]);

                // Barang kembali ke stok bila tidak hilang
                if ($itemInput['kondisi'] !== 'hilang') {
                    $detail->barang->increment('stok', $detail->qty);
                }

                $totalDenda += $dendaTotalDetail;
            }

            $pengembalian->update(['total_denda' => $totalDenda]);
            $sewa->update(['status' => 'dikembalikan']);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 500);
        }

        return response()->json($pengembalian->load('detailPengembalian.barang'), 201);
    }

    public function update(Request $request, Pengembalian $pengembalian)
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'tanggal_pengembalian' => ['sometimes', 'date'],
        ]);

        $pengembalian->update($validated);

        return response()->json($pengembalian->fresh()->load('detailPengembalian.barang'));
    }

    protected function hitungDenda(int $hargaSewa, string $kondisi): int
    {
        $persentase = $this->dendaPersentase[$kondisi] ?? 0;

        return (int) round($hargaSewa * $persentase);
    }

    protected function authorizeAdmin(Request $request): void
    {
        if (! $request->user() || $request->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang boleh melakukan aksi ini');
        }
    }
}


