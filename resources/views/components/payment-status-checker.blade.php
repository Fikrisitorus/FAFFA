{{-- Payment Status Checker Component --}}
<script>
// Auto-check payment status every 30 seconds
setInterval(function() {
    // Check if there are pending payments
    fetch('/api/check-pending-payments')
        .then(response => response.json())
        .then(data => {
            if (data.has_pending) {
                // Show notification to admin
                if (data.is_admin) {
                    showNotification('Ada pembayaran pending yang perlu di-sync!', 'warning');
                }
            }
        })
        .catch(error => console.log('Payment check error:', error));
}, 30000); // Check every 30 seconds

function showNotification(message, type) {
    // Show notification using Filament notification system
    if (window.filament) {
        window.filament.notifications.create({
            title: 'Payment Status',
            body: message,
            type: type
        });
    }
}
</script>
