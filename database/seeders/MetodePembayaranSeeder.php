<?php

namespace Database\Seeders;

use App\Models\MetodePembayaran;
use Illuminate\Database\Seeder;

class MetodePembayaranSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $metodes = [
            [
                'nama_bank' => 'BCA Virtual Account',
                'no_rek' => '8808012345678901',
                'nama_pemilik' => 'PT Camping Rental Indonesia',
            ],
            [
                'nama_bank' => 'Mandiri Virtual Account',
                'no_rek' => '8808098765432109',
                'nama_pemilik' => 'PT Camping Rental Indonesia',
            ],
            [
                'nama_bank' => 'BRI Virtual Account',
                'no_rek' => '8808055556666777',
                'nama_pemilik' => 'PT Camping Rental Indonesia',
            ],
            [
                'nama_bank' => 'QRIS',
                'no_rek' => 'ID1234567890QRIS',
                'nama_pemilik' => 'PT Camping Rental Indonesia',
            ],
        ];

        foreach ($metodes as $metode) {
            MetodePembayaran::create($metode);
        }

        echo "✓ Created " . count($metodes) . " payment methods (Virtual Account & QRIS)\n";
    }
}
