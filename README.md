## Camping Gear Rental API (Laravel 12)

Backend REST API untuk aplikasi penyewaan alat camping berbasis Laravel 12 + Sanctum. Proyek ini meliputi autentikasi, master data, manajemen sewa, pembayaran, pengembalian, dan laporan.

### 1. Cara Menjalankan

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve
```

Gunakan Postman/Thunder Client dan sertakan header `Authorization: Bearer <token>` untuk endpoint yang memerlukan login.

### 2. Autentikasi

| Method | Endpoint | Deskripsi |
| ------ | -------- | --------- |
| POST   | `/api/auth/register` | Daftar user baru (opsional langsung isi data pelanggan dengan `nik`, `alamat`, `telp`) |
| POST   | `/api/auth/login` | Login dan mendapatkan Sanctum token |
| GET    | `/api/auth/me` | Profil user + relasi pelanggan |
| POST   | `/api/auth/logout` | Hapus token aktif |

### 3. Master Data

Semua route berada dalam middleware `auth:sanctum`. List data bisa diakses user, sedangkan create/update/delete otomatis dicek role admin di controller.

- `GET/POST/PUT/DELETE /api/kategori`
- `GET/POST/PUT/DELETE /api/barang`
  - mendukung query `q` dan `kategori_id`
- `GET/POST/PUT/DELETE /api/metode-pembayaran`
- `GET/PUT/DELETE /api/pelanggan` (khusus admin)

### 4. Flow Penyewaan

- `GET /api/sewa/me` → riwayat sewa user login
- `POST /api/sewa`
  ```json
  {
    "tanggal_sewa": "2025-01-10",
    "tanggal_kembali": "2025-01-12",
    "catatan": "Opsional",
    "items": [
      { "id_barang": 1, "qty": 2 },
      { "id_barang": 5, "qty": 1 }
    ]
  }
  ```
  Sistem otomatis:
  - Menghitung lama sewa (minimal 1 hari)
  - Mengalikan harga sewa per barang * qty * hari
  - Mengurangi stok sementara
  - Membuat detail sewa

- `GET /api/sewa` + `GET /api/sewa/{id}` → admin monitoring
- `PUT /api/sewa/{id}/status` → admin update status (`pending`, `dibayar`, `dipinjam`, `dikembalikan`, `batal`)

### 5. Pembayaran

- User upload bukti:
  - `POST /api/sewa/{id}/upload-bukti` (file `bukti_bayar` jpg/png/pdf)
  - Status otomatis berubah ke `dibayar`
- Admin validasi:
  - `PUT /api/sewa/{id}/validasi` body `{ "status": "dipinjam", "catatan": "opsional" }`

### 6. Pengembalian & Denda

- Admin catat pengembalian:
  ```json
  POST /api/pengembalian
  {
    "id_sewa": 10,
    "tanggal_pengembalian": "2025-01-15",
    "items": [
      { "id_barang": 1, "kondisi": "baik" },
      { "id_barang": 5, "kondisi": "rusak ringan" }
    ]
  }
  ```
- Sistem menghitung denda otomatis per kondisi (baik/rusak ringan/rusak berat/hilang) berbasis harga sewa harian dan qty.
- Barang dengan kondisi selain “hilang” otomatis menambah stok.
- API tambahan:
  - `GET /api/pengembalian`
  - `GET /api/pengembalian/{id}`
  - `PUT /api/pengembalian/{id}` (ubah tanggal jika diperlukan)

### 7. Laporan

Semua hanya untuk admin:

| Endpoint | Fungsi |
| -------- | ------ |
| `GET /api/laporan/penyewaan?tanggal_mulai=2025-01-01&tanggal_selesai=2025-01-31` | Rekap jumlah transaksi + total pendapatan + daftar sewa |
| `GET /api/laporan/pendapatan-bulanan?tahun=2025` | Total pendapatan per bulan |
| `GET /api/laporan/barang-terlaris?limit=10` | Daftar barang dengan jumlah penyewaan terbanyak |

### 8. Role & Middleware

- Semua endpoint utama berada dalam grup `Route::middleware('auth:sanctum')`.
- Controller yang hanya boleh diakses admin memakai helper `authorizeAdmin`.
- User biasa hanya bisa:
  - Melihat master data
  - Mengelola sewa miliknya (create + list)
  - Upload bukti pembayaran miliknya

### 9. Catatan Lain

- Jalankan `php artisan storage:link` agar bukti pembayaran bisa diakses publik.
- Format denda dapat disesuaikan di `PengembalianController::$dendaPersentase`.
- Endpoint siap dipakai mobile app React Native maupun Admin Panel berbasis web.
