<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Sewa;
use App\Models\Barang;
use App\Models\Keranjang;

echo "--- LAST 5 SEWA ---\n";
print_r(Sewa::orderBy('id', 'desc')->take(5)->get(['id', 'total_harga', 'status', 'created_at'])->toArray());

echo "\n--- BARANG PRICES ---\n";
print_r(Barang::all(['id', 'nama_barang', 'harga_sewa'])->toArray());

echo "\n--- CURRENT CART ITEMS ---\n";
print_r(Keranjang::with('barang')->get()->toArray());
