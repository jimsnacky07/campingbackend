<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_sewa')->constrained('sewa')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('tanggal_pengembalian');
            $table->unsignedInteger('total_denda')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};


