<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $barangs = [
            // Tenda (id_kategori: 1)
            [
                'id_kategori' => 1,
                'nama_barang' => 'Tenda Kapasitas 2 Orang',
                'deskripsi' => 'Tenda dome untuk 2 orang, waterproof, mudah dipasang',
                'harga_sewa' => 50000,
                'stok' => 10,
            ],
            [
                'id_kategori' => 1,
                'nama_barang' => 'Tenda Kapasitas 4 Orang',
                'deskripsi' => 'Tenda keluarga untuk 4 orang, double layer, anti UV',
                'harga_sewa' => 100000,
                'stok' => 5,
            ],
            [
                'id_kategori' => 1,
                'nama_barang' => 'Tenda Kapasitas 6 Orang',
                'deskripsi' => 'Tenda besar untuk 6 orang, cocok untuk keluarga besar',
                'harga_sewa' => 150000,
                'stok' => 3,
            ],

            // Sleeping Bag (id_kategori: 2)
            [
                'id_kategori' => 2,
                'nama_barang' => 'Sleeping Bag Musim Panas',
                'deskripsi' => 'Sleeping bag ringan untuk cuaca hangat',
                'harga_sewa' => 25000,
                'stok' => 15,
            ],
            [
                'id_kategori' => 2,
                'nama_barang' => 'Sleeping Bag Musim Dingin',
                'deskripsi' => 'Sleeping bag tebal untuk suhu dingin hingga 5°C',
                'harga_sewa' => 40000,
                'stok' => 10,
            ],
            [
                'id_kategori' => 2,
                'nama_barang' => 'Sleeping Bag Premium',
                'deskripsi' => 'Sleeping bag premium dengan bahan berkualitas tinggi',
                'harga_sewa' => 60000,
                'stok' => 8,
            ],

            // Carrier (id_kategori: 3)
            [
                'id_kategori' => 3,
                'nama_barang' => 'Carrier 40L',
                'deskripsi' => 'Carrier kapasitas 40 liter untuk hiking 1-2 hari',
                'harga_sewa' => 35000,
                'stok' => 12,
            ],
            [
                'id_kategori' => 3,
                'nama_barang' => 'Carrier 60L',
                'deskripsi' => 'Carrier kapasitas 60 liter untuk hiking 3-4 hari',
                'harga_sewa' => 50000,
                'stok' => 8,
            ],
            [
                'id_kategori' => 3,
                'nama_barang' => 'Carrier 80L',
                'deskripsi' => 'Carrier besar 80 liter untuk ekspedisi panjang',
                'harga_sewa' => 75000,
                'stok' => 5,
            ],

            // Kompor Portable (id_kategori: 4)
            [
                'id_kategori' => 4,
                'nama_barang' => 'Kompor Gas Mini',
                'deskripsi' => 'Kompor gas portable ukuran mini, hemat dan praktis',
                'harga_sewa' => 20000,
                'stok' => 20,
            ],
            [
                'id_kategori' => 4,
                'nama_barang' => 'Kompor Gas Double Burner',
                'deskripsi' => 'Kompor gas 2 tungku untuk memasak lebih cepat',
                'harga_sewa' => 35000,
                'stok' => 10,
            ],
            [
                'id_kategori' => 4,
                'nama_barang' => 'Kompor Spiritus',
                'deskripsi' => 'Kompor spiritus portable, aman dan mudah digunakan',
                'harga_sewa' => 15000,
                'stok' => 15,
            ],

            // Lampu Camping (id_kategori: 5)
            [
                'id_kategori' => 5,
                'nama_barang' => 'Lampu LED Gantung',
                'deskripsi' => 'Lampu LED gantung dengan 3 mode pencahayaan',
                'harga_sewa' => 15000,
                'stok' => 25,
            ],
            [
                'id_kategori' => 5,
                'nama_barang' => 'Headlamp LED',
                'deskripsi' => 'Headlamp LED untuk aktivitas malam hari',
                'harga_sewa' => 20000,
                'stok' => 20,
            ],
            [
                'id_kategori' => 5,
                'nama_barang' => 'Senter LED Rechargeable',
                'deskripsi' => 'Senter LED bisa dicharge ulang, tahan air',
                'harga_sewa' => 25000,
                'stok' => 15,
            ],
            [
                'id_kategori' => 5,
                'nama_barang' => 'Lampu Emergency Solar',
                'deskripsi' => 'Lampu emergency dengan panel solar dan powerbank',
                'harga_sewa' => 30000,
                'stok' => 10,
            ],
        ];

        foreach ($barangs as $barang) {
            Barang::create($barang);
        }

        echo "✓ Created " . count($barangs) . " items\n";
    }
}
