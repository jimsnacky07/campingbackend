<?php

namespace App\Http\Controllers;

use App\Models\Sewa;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use App\Helpers\DebugHelper;

class MidtransController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createTransaction(Request $request)
    {
        $request->validate([
            'sewa_id' => 'required|exists:sewa,id',
        ]);

        $sewa = Sewa::with(['pelanggan', 'detailSewa.barang'])->findOrFail($request->sewa_id);

        // Check if total price is valid for Midtrans
        if ($sewa->total_harga <= 0) {
            return response()->json([
                'message' => 'Total harga sewa adalah 0. Midtrans tidak mendukung transaksi dengan nominal 0. Harap cek data sewa.'
            ], 400);
        }

        // Check if already paid
        if ($sewa->status === 'dibayar') {
            return response()->json(['message' => 'Transaksi sudah dibayar'], 400);
        }

        $orderId = 'RENT-' . $sewa->id . '-' . time();
        $sewa->update(['midtrans_order_id' => $orderId]);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $sewa->total_harga,
            ],
            'customer_details' => [
                'first_name' => $sewa->pelanggan->nama,
                'email' => $sewa->pelanggan->email ?? 'customer@example.com',
                'phone' => $sewa->pelanggan->telp,
            ],
            'item_details' => $sewa->detailSewa->map(function ($detail) {
                return [
                    'id' => $detail->id_barang,
                    'price' => (int) ($detail->harga ?? $detail->barang->harga_sewa),
                    'quantity' => $detail->qty,
                    'name' => $detail->barang->nama_barang,
                ];
            })->toArray(),
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            
            DebugHelper::info('Midtrans Snap Token Created', [
                'sewa_id' => $sewa->id,
                'order_id' => $orderId,
                'snap_token' => $snapToken
            ]);

            return response()->json([
                'snap_token' => $snapToken,
                'redirect_url' => "https://app.sandbox.midtrans.com/snap/v2/vtweb/" . $snapToken,
            ]);
        } catch (\Exception $e) {
            DebugHelper::error('Midtrans Create Transaction Error', [
                'error' => $e->getMessage()
            ]);
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function handleNotification(Request $request)
    {
        DebugHelper::info('Midtrans Notification Received', $request->all());

        $notif = new Notification();

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;

        $sewa = Sewa::where('midtrans_order_id', $orderId)->firstOrFail();

        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $sewa->update(['status' => 'pending']);
                } else {
                    $sewa->update([
                        'status' => 'dibayar',
                        'paid_at' => now(),
                        'payment_type' => $type,
                        'midtrans_transaction_id' => $notif->transaction_id
                    ]);
                }
            }
        } else if ($transaction == 'settlement') {
            $sewa->update([
                'status' => 'dibayar',
                'paid_at' => now(),
                'payment_type' => $type,
                'midtrans_transaction_id' => $notif->transaction_id
            ]);
        } else if ($transaction == 'pending') {
            $sewa->update(['status' => 'pending']);
        } else if ($transaction == 'deny') {
            $sewa->update(['status' => 'batal']);
        } else if ($transaction == 'expire') {
            $sewa->update(['status' => 'batal']);
        } else if ($transaction == 'cancel') {
            $sewa->update(['status' => 'batal']);
        }

        DebugHelper::info('Midtrans Notification Handled', [
            'order_id' => $orderId,
            'status' => $sewa->status
        ]);

        return response()->json(['message' => 'Notification Handled']);
    }

    public function checkStatus($orderId)
    {
        try {
            $status = \Midtrans\Transaction::status($orderId);
            
            // Sync status to database
            $sewa = Sewa::where('midtrans_order_id', $orderId)->first();
            if ($sewa) {
                $transaction = $status->transaction_status;
                if ($transaction == 'settlement' || $transaction == 'capture') {
                    $sewa->update([
                        'status' => 'dibayar',
                        'paid_at' => now(),
                        'payment_type' => $status->payment_type,
                        'midtrans_transaction_id' => $status->transaction_id
                    ]);
                } else if (in_array($transaction, ['deny', 'expire', 'cancel'])) {
                    $sewa->update(['status' => 'batal']);
                }
            }

            return response()->json([
                'data' => $status,
                'local_status' => $sewa ? $sewa->status : null
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
