(function(){
	console.log('[payment.js] loaded');
	
	// Flag to prevent multiple popup calls
	let isPopupOpen = false;
	
	function handler(e){
		try {
			if (e) { e.preventDefault(); e.stopPropagation(); }
			
			// Prevent multiple calls
			if (isPopupOpen) {
				console.log('[payment.js] Popup already open, ignoring click');
				return;
			}
			
			const payButton = document.getElementById('pay-button');
			if (!payButton) { console.error('[payment.js] pay-button not found'); return; }

			if (typeof window.snap === 'undefined') {
				console.error('[payment.js] Midtrans Snap is not loaded');
				console.log('Gagal memuat Midtrans. Mohon refresh halaman dan coba lagi.');
				
				// Try to reload snap script
				console.log('Attempting to reload snap script...');
				const script = document.createElement('script');
				script.src = 'https://app.sandbox.midtrans.com/snap/snap.js';
				script.setAttribute('data-client-key', document.querySelector('script[data-client-key]').getAttribute('data-client-key'));
				script.onload = function() {
					console.log('Snap script reloaded successfully');
				};
				script.onerror = function() {
					console.error('Failed to reload snap script');
				};
				document.head.appendChild(script);
				return;
			}

			const snapToken = payButton.getAttribute('data-token');
			const successUrl = payButton.getAttribute('data-success');
			const pendingUrl = payButton.getAttribute('data-pending');
			const errorUrl = payButton.getAttribute('data-error');

			console.log('[payment.js] Snap token from button:', snapToken);

			if (!snapToken) {
				console.error('[payment.js] Snap token missing from button');
				console.log('Token pembayaran tidak tersedia. Mohon kembali dan masuk lagi ke halaman pembayaran.');
				return;
			}

			// Ambil data opsi pembayaran
			const paymentOption = document.querySelector('input[name="payment_option"]:checked')?.value;
			const donationAmount = document.getElementById('donation_amount')?.value || 0;
			
			console.log('[payment.js] Payment options:', {
				paymentOption: paymentOption,
				donationAmount: donationAmount
			});

			// Set flag to prevent multiple calls
			isPopupOpen = true;
			
			const originalText = payButton.innerHTML;
			payButton.disabled = true;
			payButton.classList.add('opacity-70', 'cursor-not-allowed');
			payButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';

			console.log('[payment.js] Opening Snap popup...');
			
					snap.pay(snapToken, {
						onSuccess: function (result) {
							console.log('[payment.js] success', result);
							isPopupOpen = false;
							
							// Submit form dengan data opsi pembayaran
							const form = document.getElementById('payment-form');
							if (form) {
								// Sync data terakhir ke hidden fields
								const paymentOption = document.querySelector('input[name="payment_option"]:checked')?.value;
								const donationAmount = document.getElementById('donation_amount')?.value || 0;
								
								document.getElementById('hidden_payment_option').value = paymentOption;
								document.getElementById('hidden_donation_amount').value = donationAmount;
								
								// Submit form
								form.submit();
							} else {
								window.location.href = successUrl;
							}
						},
				onPending: function (result) {
					console.log('[payment.js] pending', result);
					isPopupOpen = false;
					window.location.href = pendingUrl;
				},
				onError: function (result) {
					console.log('[payment.js] error', result);
					isPopupOpen = false;
					window.location.href = errorUrl;
				},
				onClose: function () {
					console.log('[payment.js] popup closed');
					isPopupOpen = false;
					payButton.disabled = false;
					payButton.classList.remove('opacity-70', 'cursor-not-allowed');
					payButton.innerHTML = originalText;
				}
			});
		} catch (err) {
			console.error('[payment.js] exception', err);
			isPopupOpen = false;
			console.log('Terjadi kesalahan saat membuka pembayaran: ' + err.message);
			
			// Reset button state
			const payButton = document.getElementById('pay-button');
			if (payButton) {
				payButton.disabled = false;
				payButton.classList.remove('opacity-70', 'cursor-not-allowed');
				payButton.innerHTML = '<i class="fas fa-credit-card mr-2"></i> Bayar Sekarang';
			}
		}
	}

	window.startPayment = handler;

	// Disabled - using direct implementation in blade
	// document.addEventListener('DOMContentLoaded', function(){
	//     const payButton = document.getElementById('pay-button');
	//     if (payButton) {
	//         payButton.addEventListener('click', handler);
	//         console.log('[payment.js] listener attached');
	//     }
	// });
})();
