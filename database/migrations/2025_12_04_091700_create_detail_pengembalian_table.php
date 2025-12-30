<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pengembalian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengembalian')->constrained('pengembalian')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('id_barang')->constrained('barang')->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('kondisi', ['baik', 'rusak ringan', 'rusak berat', 'hilang']);
            $table->unsignedInteger('denda')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pengembalian');
    }
};


