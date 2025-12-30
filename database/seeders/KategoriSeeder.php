<?php

namespace Database\Seeders;

use App\Models\KategoriBarang;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $kategoris = [
            ['nama_kategori' => 'Tenda'],
            ['nama_kategori' => 'Sleeping Bag'],
            ['nama_kategori' => 'Carrier'],
            ['nama_kategori' => 'Kompor Portable'],
            ['nama_kategori' => 'Lampu Camping'],
        ];

        foreach ($kategoris as $kategori) {
            KategoriBarang::create($kategori);
        }

        echo "✓ Created " . count($kategoris) . " categories\n";
    }
}
