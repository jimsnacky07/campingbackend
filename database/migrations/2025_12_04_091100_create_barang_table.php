<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kategori')->constrained('kategori_barang')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('nama_barang');
            $table->text('deskripsi')->nullable();
            $table->unsignedInteger('harga_sewa');
            $table->unsignedInteger('stok')->default(0);
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};


