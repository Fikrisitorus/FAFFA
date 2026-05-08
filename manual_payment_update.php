<?php

require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\Penjemputan;

echo "=== Manual Payment Status Update ===\n\n";

// Cari penjemputan yang payment status masih pending
$pendingPayments = Penjemputan::where('payment_status', 'pending')
    ->whereNotNull('midtrans_order_id')
    ->get();

echo "Found " . $pendingPayments->count() . " pending payments:\n\n";

foreach ($pendingPayments as $penjemputan) {
    echo "Order ID: " . $penjemputan->midtrans_order_id . "\n";
    echo "Amount: Rp " . number_format($penjemputan->total_amount) . "\n";
    echo "Status: " . $penjemputan->payment_status . "\n";
    echo "Created: " . $penjemputan->created_at . "\n";
    echo "---\n";
}

echo "\nTo update status manually:\n";
echo "1. Check Midtrans dashboard for transaction status\n";
echo "2. Update payment_status in database\n";
echo "3. Or use: php artisan tinker\n";
echo "4. Then: \$p = Penjemputan::find(ID); \$p->update(['payment_status' => 'paid']);\n";
