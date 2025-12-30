<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_sewa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_sewa')->constrained('sewa')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('id_barang')->constrained('barang')->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedInteger('qty');
            $table->unsignedInteger('harga');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_sewa');
    }
};


