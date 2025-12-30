<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

    public function index()
    {
        $pengembalian = Pengembalian::with(['sewa.pelanggan.user'])
            ->orderBy('tanggal_pengembalian', 'desc')
            ->paginate(10);

        return view('admin.pengembalian.index', compact('pengembalian'));
    }

    public function create(Request $request)
    {
        $sewaDipinjam = Sewa::with(['pelanggan.user', 'detailSewa.barang'])
            ->where('status', 'dipinjam')
            ->orderBy('tanggal_sewa', 'asc')
            ->get();

        $selectedId = $request->query('sewa_id');
        $selectedSewa = $selectedId
            ? $sewaDipinjam->firstWhere('id', (int) $selectedId)
            : $sewaDipinjam->first();

        return view('admin.pengembalian.create', [
            'sewaDipinjam' => $sewaDipinjam,
            'selectedSewa' => $selectedSewa,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_sewa' => ['required', 'exists:sewa,id'],
            'tanggal_pengembalian' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id_barang' => ['required', 'exists:barang,id'],
            'items.*.kondisi' => ['required', 'in:baik,rusak ringan,rusak berat,hilang'],
        ]);

        $sewa = Sewa::with('detailSewa.barang')->findOrFail($validated['id_sewa']);

        if ($sewa->status !== 'dipinjam') {
            return back()->with('error', 'Hanya sewa berstatus dipinjam yang bisa dikembalikan.')
                ->withInput();
        }

        if ($sewa->pengembalian) {
            return back()->with('error', 'Pengembalian sudah tercatat untuk sewa ini.')
                ->withInput();
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

            return back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()->route('admin.pengembalian.index')->with('success', 'Pengembalian dicatat.');
    }

    protected function hitungDenda(int $hargaSewa, string $kondisi): int
    {
        $persentase = $this->dendaPersentase[$kondisi] ?? 0;

        return (int) round($hargaSewa * $persentase);
    }
}


