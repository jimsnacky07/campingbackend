<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailSewa;
use App\Models\Sewa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function penyewaan(Request $request)
    {
        $validated = $request->validate([
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
        ]);

        $query = Sewa::with('pelanggan.user');

        if (! empty($validated['tanggal_mulai'])) {
            $query->whereDate('tanggal_sewa', '>=', $validated['tanggal_mulai']);
        }

        if (! empty($validated['tanggal_selesai'])) {
            $query->whereDate('tanggal_sewa', '<=', $validated['tanggal_selesai']);
        }

        $data = $query->orderBy('tanggal_sewa', 'desc')->get();

        return view('admin.laporan.penyewaan', [
            'filter' => $validated,
            'data' => $data,
            'totalTransaksi' => $data->count(),
            'totalPendapatan' => $data->sum('total_harga'),
        ]);
    }

    public function pendapatan(Request $request)
    {
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

        return view('admin.laporan.pendapatan', [
            'tahun' => $tahun,
            'data' => $pendapatan,
            'totalTahun' => $pendapatan->sum('total'),
        ]);
    }

    public function barangTerlaris(Request $request)
    {
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

        return view('admin.laporan.barang_terlaris', [
            'limit' => $limit,
            'barang' => $barang,
        ]);
    }
}


