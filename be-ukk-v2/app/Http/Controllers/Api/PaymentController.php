<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrxRentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private $baseUrl;
    private $secretKey;

    public function __construct()
    {
        $this->baseUrl = 'https://api.xendit.co/v2/invoices';
        $this->secretKey = env('XENDIT_SECRET_KEY');

        if (!$this->secretKey) {
            Log::error("XENDIT_SECRET_KEY tidak ditemukan di .env");
            abort(500, "Konfigurasi Xendit tidak ditemukan.");
        }
    }

    /**
     * Membuat pembayaran menggunakan Xendit Invoice API
     */
    public function createPayment(Request $request)
    {
        try {
            // Validasi request
            $request->validate([
                'trx_id' => 'required|exists:trx_rent_items,trx_rent_items_id'
            ]);

            // Ambil transaksi yang statusnya "Pending" & belum dibayar
            $trx = TrxRentItem::with(['user', 'details.item'])
                ->where('status', 'P')
                ->where('flg_payment', 'N')
                ->findOrFail($request->trx_id);

            // Buat payload untuk Xendit
            $params = [
                'external_id' => $trx->trx_code,
                'amount' => (int) ($trx->total * 0.5),
                'currency' => 'IDR',
                'description' => "Payment for rental {$trx->trx_code}",
                'invoice_duration' => 86400,
                'customer' => [
                    'given_names' => $trx->user->name,
                    'email' => $trx->user->email,
                    'mobile_number' => (string) ('0'. $trx->user->phone ?? '08123456789')
                ],
                'success_redirect_url' => env('APP_FRONTEND_URL') . '/payment/success',
                'failure_redirect_url' => env('APP_FRONTEND_URL') . '/payment/failed'
            ];

            // Kirim request ke API Xendit
            $response = Http::withBasicAuth($this->secretKey, '')
                ->post($this->baseUrl, $params);

            // Jika request gagal, log error
            if (!$response->successful()) {
                Log::error('Xendit API Error', [
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat pembayaran',
                    'error' => $response->json()
                ], $response->status());
            }

            // Simpan data invoice ke database
            $invoice = $response->json();
            $trx->update([
                'payment_id' => $invoice['id'],
                'payment_status' => $invoice['status'],
                'payment_url' => $invoice['invoice_url']
            ]);

            return response()->json([
                'success' => true,
                'data' => ['payment_url' => $invoice['invoice_url']]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengecek status pembayaran dari Xendit
     */
    public function checkPaymentStatus(Request $request)
    {
        try {
            // If no specific invoice_id provided, check all pending payments
            if (!$request->has('invoice_id')) {
                $pendingPayments = TrxRentItem::where('status', 'P')
                    ->where('flg_payment', 'N')
                    ->whereNotNull('payment_id')
                    ->get();
    
                $results = [];
                foreach ($pendingPayments as $trx) {
                    $response = Http::withBasicAuth($this->secretKey, '')
                        ->get("{$this->baseUrl}/{$trx->payment_id}");
    
                    if ($response->successful()) {
                        $invoice = $response->json();
                        if (in_array($invoice['status'], ['SETTLED', 'PAID'])) {
                            $trx->update([
                                'flg_payment' => 'P',
                                'payment_status' => 'PAID',
                                'payment_method' => $invoice['payment_method'],
                            ]);
                            $results[] = [
                                'invoice_id' => $trx->payment_id,
                                'status' => 'PAID'
                            ];
                        }
                    }
                }
    
                return response()->json([
                    'success' => true,
                    'data' => $results
                ]);
            }
    
            // Check specific invoice_id
            $invoiceId = $request->input('invoice_id');
            $response = Http::withBasicAuth($this->secretKey, '')
                ->get("{$this->baseUrl}/{$invoiceId}");
    
            if (!$response->successful()) {
                throw new \Exception('Gagal mendapatkan status invoice');
            }
    
            $invoice = $response->json();
            if (in_array($invoice['status'], ['SETTLED', 'PAID'])) {
                $trx = TrxRentItem::where('payment_id', $invoiceId)
                    ->where('status', 'P')
                    ->where('flg_payment', 'N')
                    ->firstOrFail();
    
                $trx->update([
                    'flg_payment' => 'P',
                    'payment_status' => 'PAID',
                    'payment_method' => $invoice['payment_method'],
                ]);
    
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil'
                ]);
            }
    
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran masih pending'
            ]);
    
        } catch (\Exception $e) {
            Log::error('Payment status check failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
