<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sewa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pelanggan')->constrained('pelanggan')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('tanggal_sewa');
            $table->date('tanggal_kembali');
            $table->unsignedInteger('total_harga');
            $table->enum('status', ['pending', 'dibayar', 'dipinjam', 'dikembalikan', 'batal'])->default('pending');
            $table->string('bukti_bayar')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sewa');
    }
};


