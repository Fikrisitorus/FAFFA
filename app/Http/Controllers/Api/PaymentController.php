<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penjemputan;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Check if there are pending payments
     */
    public function checkPendingPayments(Request $request)
    {
        $pendingCount = Penjemputan::where('payment_status', 'pending')
            ->whereNotNull('midtrans_order_id')
            ->count();
            
        return response()->json([
            'has_pending' => $pendingCount > 0,
            'pending_count' => $pendingCount,
            'is_admin' => auth()->user()?->hasRole('admin') ?? false
        ]);
    }
    
    /**
     * Trigger manual sync
     */
    public function triggerSync(Request $request)
    {
        if (!auth()->user()?->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Run sync command
        \Artisan::call('payment:sync-status');
        $output = \Artisan::output();
        
        return response()->json([
            'success' => true,
            'message' => 'Payment sync triggered',
            'output' => $output
        ]);
    }
}
