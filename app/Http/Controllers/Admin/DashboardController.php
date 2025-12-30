<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Pengembalian;
use App\Models\Sewa;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang = Barang::count();
        $totalPenyewaan = Sewa::count();
        $totalPengembalian = Pengembalian::count();
        $totalPelanggan = User::where('role', 'user')->count();

        $penyewaanHariIni = Sewa::whereDate('tanggal_sewa', now()->toDateString())->count();

        $latestSewa = Sewa::with('pelanggan.user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalBarang',
            'totalPenyewaan',
            'totalPengembalian',
            'totalPelanggan',
            'penyewaanHariIni',
            'latestSewa',
        ));
    }
}


