<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Penjemputan;

class CheckPaymentStatus extends Command
{
    protected $signature = 'payment:check {penjemputan_id}';
    protected $description = 'Check payment status for a penjemputan';

    public function handle()
    {
        $penjemputanId = $this->argument('penjemputan_id');
        $penjemputan = Penjemputan::find($penjemputanId);
        
        if (!$penjemputan) {
            $this->error("Penjemputan with ID {$penjemputanId} not found!");
            return;
        }
        
        $this->info("Payment Status for Penjemputan #{$penjemputanId}:");
        $this->line("Status: {$penjemputan->status}");
        $this->line("Payment Status: {$penjemputan->payment_status}");
        $this->line("Payment Method: {$penjemputan->payment_method}");
        $this->line("Total Amount: Rp " . number_format($penjemputan->total_amount ?? 0, 0, ',', '.'));
        $this->line("Midtrans Order ID: {$penjemputan->midtrans_order_id}");
        $this->line("Payment Time: {$penjemputan->payment_time}");
    }
}