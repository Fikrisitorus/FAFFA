<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\CoreApi;
use Midtrans\Transaction;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = config('midtrans.is_3ds');
        
        Log::info('Midtrans Service initialized', [
            'server_key' => substr(config('midtrans.server_key'), 0, 10) . '...',
            'client_key' => substr(config('midtrans.client_key'), 0, 10) . '...',
            'is_production' => config('midtrans.is_production'),
            'is_3ds' => config('midtrans.is_3ds')
        ]);
    }

    /**
     * Create Snap token for payment
     */
    public function createSnapToken($orderId, $amount, $customerDetails, $itemDetails = [], $penjemputanId = null)
    {
        try {
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $amount,
                ],
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
                // Webhook untuk update otomatis
                'callbacks' => [
                    'finish' => config('app.url') . '/pengepul/payment-success/' . $penjemputanId,
                    'unfinish' => config('app.url') . '/pengepul/payment-pending/' . $penjemputanId,
                    'error' => config('app.url') . '/pengepul/payment-error/' . $penjemputanId,
                ],
                // Webhook URL untuk Midtrans notification
                'notification_url' => config('app.url') . '/payment/webhook',
                // Enable payment methods for QRIS and Virtual Account
                'enabled_payments' => ['qris', 'gopay', 'shopeepay', 'bca_va', 'bni_va', 'bri_va', 'mandiri_va', 'permata_va'],
                'payment_options' => [
                    'qris' => [
                        'acquirer' => ['gopay', 'shopeepay']
                    ],
                    'bca_va' => [
                        'va_number' => '1234567890'
                    ],
                    'bni_va' => [
                        'va_number' => '1234567890'
                    ],
                    'bri_va' => [
                        'va_number' => '1234567890'
                    ],
                    'mandiri_va' => [
                        'va_number' => '1234567890'
                    ],
                    'permata_va' => [
                        'va_number' => '1234567890'
                    ]
                ],
            ];

            $snapToken = Snap::getSnapToken($params);
            
            Log::info('Midtrans Snap Token created', [
                'order_id' => $orderId,
                'amount' => $amount,
                'snap_token' => $snapToken
            ]);

            return $snapToken;

        } catch (\Exception $e) {
            Log::error('Midtrans Snap Token creation failed', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            
            throw new \Exception('Failed to create payment token: ' . $e->getMessage());
        }
    }

    /**
     * Get transaction status
     */
    public function getTransactionStatus($orderId)
    {
        try {
            $status = Transaction::status($orderId);
            
            Log::info('Midtrans transaction status', [
                'order_id' => $orderId,
                'status' => $status
            ]);

            return $status;

        } catch (\Exception $e) {
            Log::error('Midtrans status check failed', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            
            throw new \Exception('Failed to check transaction status: ' . $e->getMessage());
        }
    }

    /**
     * Cancel transaction
     */
    public function cancelTransaction($orderId)
    {
        try {
            $result = Transaction::cancel($orderId);
            
            Log::info('Midtrans transaction cancelled', [
                'order_id' => $orderId,
                'result' => $result
            ]);

            return $result;

        } catch (\Exception $e) {
            Log::error('Midtrans cancel failed', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            
            throw new \Exception('Failed to cancel transaction: ' . $e->getMessage());
        }
    }

    /**
     * Approve transaction
     */
    public function approveTransaction($orderId)
    {
        try {
            $result = Transaction::approve($orderId);
            
            Log::info('Midtrans transaction approved', [
                'order_id' => $orderId,
                'result' => $result
            ]);

            return $result;

        } catch (\Exception $e) {
            Log::error('Midtrans approve failed', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            
            throw new \Exception('Failed to approve transaction: ' . $e->getMessage());
        }
    }

    /**
     * Handle webhook notification
     */
    public function handleWebhook($request)
    {
        try {
            $notification = $request->all();
            
            Log::info('Midtrans webhook received', [
                'notification' => $notification
            ]);

            // Verify signature (optional but recommended)
            $orderId = $notification['order_id'];
            $statusCode = $notification['status_code'];
            $grossAmount = $notification['gross_amount'];

            // Update transaction status based on notification
            $this->updateTransactionStatus($orderId, $statusCode, $grossAmount, $notification);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Midtrans webhook handling failed', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);
            
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Check transaction status from Midtrans
     */
    public function checkTransactionStatus($orderId)
    {
        try {
            // Ensure config is set
            Config::$serverKey = config('midtrans.server_key');
            Config::$clientKey = config('midtrans.client_key');
            Config::$isProduction = config('midtrans.is_production');
            
            $status = Transaction::status($orderId);
            
            Log::info('Transaction status checked', [
                'order_id' => $orderId,
                'status' => $status->transaction_status ?? 'unknown',
                'payment_type' => $status->payment_type ?? 'unknown'
            ]);
            
            return $status;
            
        } catch (\Exception $e) {
            Log::error('Failed to check transaction status', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Update transaction status in database
     */
    private function updateTransactionStatus($orderId, $statusCode, $grossAmount, $notification = [])
    {
        // Find penjemputan by order_id
        $penjemputan = \App\Models\Penjemputan::where('midtrans_order_id', $orderId)->first();
        
        if (!$penjemputan) {
            Log::warning('Penjemputan not found for order_id', ['order_id' => $orderId]);
            return;
        }

        // Update status based on Midtrans status code
        switch ($statusCode) {
            case '200':
                // Payment successful
                $penjemputan->update([
                    'payment_status' => 'paid',
                    'payment_method' => $notification['payment_type'] ?? 'unknown',
                    'payment_time' => now(),
                    'status' => 'completed'
                ]);
                break;
            case '201':
                // Payment pending
                $penjemputan->update([
                    'payment_status' => 'pending'
                ]);
                break;
            case '202':
                // Payment failed
                $penjemputan->update([
                    'payment_status' => 'failed'
                ]);
                break;
            default:
                Log::warning('Unknown Midtrans status code', [
                    'order_id' => $orderId,
                    'status_code' => $statusCode
                ]);
        }
    }
}
