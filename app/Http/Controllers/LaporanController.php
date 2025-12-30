<?php

namespace App\Http\Controllers;

use App\Models\DetailSewa;
use App\Models\Sewa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function penyewaan(Request $request)
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
        ]);

        $query = Sewa::query();

        if (! empty($validated['tanggal_mulai'])) {
            $query->whereDate('tanggal_sewa', '>=', $validated['tanggal_mulai']);
        }

        if (! empty($validated['tanggal_selesai'])) {
            $query->whereDate('tanggal_sewa', '<=', $validated['tanggal_selesai']);
        }

        $data = [
            'total_transaksi' => $query->count(),
            'total_pendapatan' => $query->sum('total_harga'),
            'rincian' => $query->with('pelanggan.user')->orderBy('tanggal_sewa', 'desc')->get(),
        ];

        return response()->json($data);
    }

    public function pendapatanBulanan(Request $request)
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'tahun' => ['nullable', 'integer', 'min:2000', 'max:' . now()->year],
        ]);

        $tahun = $validated['tahun'] ?? now()->year;

        $pendapatan = Sewa::select([
            DB::raw('MONTH(tanggal_sewa) as bulan'),
            DB::raw('SUM(total_harga) as total'),
        ])
            ->whereYear('tanggal_sewa', $tahun)
            ->groupBy(DB::raw('MONTH(tanggal_sewa)'))
            ->orderBy(DB::raw('MONTH(tanggal_sewa)'))
            ->get()
            ->map(function ($row) {
                $row->nama_bulan = Carbon::create()->month($row->bulan)->locale('id')->translatedFormat('F');

                return $row;
            });

        return response()->json([
            'tahun' => $tahun,
            'data' => $pendapatan,
            'total_tahun' => $pendapatan->sum('total'),
        ]);
    }

    public function barangTerlaris(Request $request)
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $limit = $validated['limit'] ?? 10;

        $barang = DetailSewa::select([
            'id_barang',
            DB::raw('SUM(qty) as total_disewa'),
            DB::raw('SUM(qty * harga) as total_pendapatan'),
        ])
            ->with('barang')
            ->groupBy('id_barang')
            ->orderByDesc('total_disewa')
            ->limit($limit)
            ->get();

        return response()->json($barang);
    }

    public function exportInvoice(Request $request, Sewa $sewa)
    {
        $user = $request->user();

        // Authorization: admin atau pemilik transaksi
        if ($user->role !== 'admin' && $user->pelanggan?->id !== $sewa->id_pelanggan) {
            abort(403, 'Anda tidak memiliki akses ke invoice ini');
        }

        $sewa->load(['pelanggan.user', 'detailSewa.barang']);

        $pdf = \PDF::loadView('pdf.invoice', compact('sewa'));
        
        return $pdf->download('invoice-' . $sewa->id . '.pdf');
    }

    public function exportPenyewaan(Request $request)
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $startDate = $validated['start_date'] ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $validated['end_date'] ?? now()->endOfMonth()->format('Y-m-d');

        $sewas = Sewa::with(['pelanggan.user', 'detailSewa.barang'])
            ->whereDate('tanggal_sewa', '>=', $startDate)
            ->whereDate('tanggal_sewa', '<=', $endDate)
            ->orderBy('tanggal_sewa', 'desc')
            ->get();

        $totalPendapatan = $sewas->sum('total_harga');

        $pdf = \PDF::loadView('pdf.laporan_penyewaan', compact('sewas', 'startDate', 'endDate', 'totalPendapatan'));
        
        return $pdf->download('laporan-penyewaan-' . $startDate . '-' . $endDate . '.pdf');
    }

    protected function authorizeAdmin(Request $request): void
    {
        if (! $request->user() || $request->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang boleh melakukan aksi ini');
        }
    }
}


