<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Sewa;
use App\Models\DetailSewa;

$sewa = Sewa::latest()->first();
echo "ID: " . $sewa->id . "\n";
echo "Total Harga: " . $sewa->total_harga . "\n";
echo "Items count: " . $sewa->detailSewa()->count() . "\n";

foreach($sewa->detailSewa as $detail) {
    echo "  - Barang: " . $detail->id_barang . ", Qty: " . $detail->qty . ", Harga detail: " . $detail->harga . "\n";
}
