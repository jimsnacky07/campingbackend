<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sewa', function (Blueprint $blueprint) {
            $blueprint->string('midtrans_order_id')->nullable()->after('catatan');
            $blueprint->string('midtrans_transaction_id')->nullable()->after('midtrans_order_id');
            $blueprint->string('payment_type')->nullable()->after('midtrans_transaction_id');
            $blueprint->timestamp('paid_at')->nullable()->after('payment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sewa', function (Blueprint $blueprint) {
            $blueprint->dropColumn([
                'midtrans_order_id',
                'midtrans_transaction_id',
                'payment_type',
                'paid_at'
            ]);
        });
    }
};
