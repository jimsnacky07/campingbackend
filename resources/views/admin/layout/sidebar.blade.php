<div class="list-group">
    <div class="list-group-item fw-bold bg-secondary text-white">
        Navigasi
    </div>
    <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action">
        Dashboard
    </a>
    <div class="list-group-item fw-bold">
        Data Master
    </div>
<a href="{{ route('admin.barang.index') }}" class="list-group-item list-group-item-action">Barang</a>
<a href="{{ route('admin.kategori.index') }}" class="list-group-item list-group-item-action">Kategori Barang</a>
<a href="{{ route('admin.pelanggan.index') }}" class="list-group-item list-group-item-action">Pelanggan</a>
    <div class="list-group-item fw-bold">
        Transaksi
    </div>
<a href="{{ route('admin.transaksi.index') }}" class="list-group-item list-group-item-action">Penyewaan</a>
<a href="{{ route('admin.pembayaran.index') }}" class="list-group-item list-group-item-action">Pembayaran</a>
<a href="{{ route('admin.pengembalian.index') }}" class="list-group-item list-group-item-action">Pengembalian</a>
    <div class="list-group-item fw-bold">
        Laporan
    </div>
<a href="{{ route('admin.laporan.penyewaan') }}" class="list-group-item list-group-item-action">Penyewaan</a>
<a href="{{ route('admin.laporan.pendapatan') }}" class="list-group-item list-group-item-action">Pendapatan</a>
<a href="{{ route('admin.laporan.barang-terlaris') }}" class="list-group-item list-group-item-action">Barang Terlaris</a>
</div>


