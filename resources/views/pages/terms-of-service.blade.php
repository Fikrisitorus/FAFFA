<!DOCTYPE html>
<html>
  <head>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
    <link
      rel="stylesheet"
      as="style"
      onload="this.rel='stylesheet'"
      href="https://fonts.googleapis.com/css2?display=swap&amp;family=Manrope%3Awght%40400%3B500%3B700%3B800&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900"
    />
    <title>Terms of Service - Bank Sampah</title>
    <link rel="icon" type="image/x-icon" href="data:image/x-icon;base64," />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <style>
      html {
        scroll-behavior: smooth;
      }
    </style>
  </head>
  <body>
    <div class="relative flex size-full min-h-screen flex-col bg-[#f8fbfa] group/design-root overflow-x-hidden" style='font-family: Manrope, "Noto Sans", sans-serif;'>
      <div class="layout-container flex h-full grow flex-col">
        <header class="shadow-sm bg-white sticky top-0 z-50">
          <div class="flex items-center justify-between px-4 sm:px-10 py-3">
            <a href="/" class="flex items-center gap-2">
              <div class="size-7">
                <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <g clip-path="url(#clip0_6_535)">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M47.2426 24L24 47.2426L0.757355 24L24 0.757355L47.2426 24ZM12.2426 21H35.7574L24 9.24264L12.2426 21Z" fill="#38e07b"></path>
                  </g>
                  <defs>
                    <clipPath id="clip0_6_535"><rect width="48" height="48" fill="white"></rect></clipPath>
                  </defs>
                </svg>
              </div>
              <span class="font-bold text-lg text-[#0e1a13] tracking-tight">Bank Sampah</span>
            </a>
            <nav class="flex items-center gap-4">
              <a href="/" class="text-[#0e1a13] text-base font-medium leading-normal hover:text-[#38e07b] transition">Home</a>
              <a href="/artikel" class="text-[#0e1a13] text-base font-medium leading-normal hover:text-[#38e07b] transition">Artikel</a>
              <a href="/login" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#38e07b] text-[#0e1a13] text-base font-bold leading-normal tracking-[0.015em] hover:bg-[#2ecc71] transition">
                <span class="truncate">Login</span>
              </a>
            </nav>
          </div>
        </header>
        
        <div class="px-4 sm:px-40 flex flex-1 justify-center py-5">
          <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
            <div class="px-4 py-6">
              <a href="/" class="inline-flex items-center text-sm text-[#51946c] hover:text-[#38e07b] transition mb-4">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Home
              </a>
              
              <h1 class="text-[#0e1a13] text-4xl font-bold leading-tight tracking-[-0.015em] mb-6">Terms of Service</h1>
              
              <div class="prose prose-lg max-w-none text-[#0e1a13]">
                <p class="text-sm text-gray-600 mb-6">Last updated: {{ date('d M Y') }}</p>
                
                <section class="mb-8">
                  <h2 class="text-2xl font-bold mb-4">1. Penerimaan Syarat</h2>
                  <p class="mb-4">
                    Dengan mengakses dan menggunakan platform Bank Sampah, Anda menyetujui untuk terikat oleh Syarat Layanan ini. Jika Anda tidak setuju dengan syarat-syarat ini, mohon untuk tidak menggunakan layanan kami.
                  </p>
                </section>

                <section class="mb-8">
                  <h2 class="text-2xl font-bold mb-4">2. Penggunaan Platform</h2>
                  <p class="mb-4">Anda setuju untuk:</p>
                  <ul class="list-disc pl-6 mb-4 space-y-2">
                    <li>Menyediakan informasi yang akurat dan terkini</li>
                    <li>Menggunakan platform sesuai dengan tujuan yang dimaksudkan</li>
                    <li>Tidak melakukan aktivitas yang merugikan sistem atau pengguna lain</li>
                    <li>Memastikan sampah yang disetor dalam kondisi bersih dan sesuai standar</li>
                  </ul>
                </section>

                <section class="mb-8">
                  <h2 class="text-2xl font-bold mb-4">3. Transaksi dan Pembayaran</h2>
                  <p class="mb-4">
                    Semua transaksi melalui platform Bank Sampah tunduk pada ketentuan berikut:
                  </p>
                  <ul class="list-disc pl-6 mb-4 space-y-2">
                    <li>Harga sampah ditentukan berdasarkan jenis dan berat yang diverifikasi pengepul</li>
                    <li>Pembayaran akan diproses sesuai dengan opsi yang dipilih (ambil semua, donasi, atau split)</li>
                    <li>Saldo akan diperbarui setelah transaksi selesai dan diverifikasi</li>
                  </ul>
                </section>

                <section class="mb-8">
                  <h2 class="text-2xl font-bold mb-4">4. Penjemputan Sampah</h2>
                  <p class="mb-4">
                    Penjemputan sampah dilakukan berdasarkan sistem First Come First Served (FCFS). Pengepul yang mengambil order terlebih dahulu akan mendapatkan penugasan. Kelompok nasabah dapat membatalkan penjemputan sebelum diambil oleh pengepul.
                  </p>
                </section>

                <section class="mb-8">
                  <h2 class="text-2xl font-bold mb-4">5. Transparansi Donasi</h2>
                  <p class="mb-4">
                    Informasi donasi dan pengeluaran ditampilkan secara publik di homepage untuk memastikan transparansi. Dengan menggunakan platform ini, Anda menyetujui bahwa informasi donasi dapat ditampilkan secara publik (tanpa mengungkapkan identitas pribadi).
                  </p>
                </section>

                <section class="mb-8">
                  <h2 class="text-2xl font-bold mb-4">6. Batasan Tanggung Jawab</h2>
                  <p class="mb-4">
                    Bank Sampah tidak bertanggung jawab atas kerugian yang timbul dari penggunaan platform, termasuk namun tidak terbatas pada kesalahan data, gangguan sistem, atau masalah teknis lainnya.
                  </p>
                </section>

                <section class="mb-8">
                  <h2 class="text-2xl font-bold mb-4">7. Perubahan Syarat</h2>
                  <p class="mb-4">
                    Kami berhak mengubah Syarat Layanan ini kapan saja. Perubahan akan diberitahukan melalui platform. Penggunaan berkelanjutan setelah perubahan berarti Anda menerima syarat yang baru.
                  </p>
                </section>

                <section class="mb-8">
                  <h2 class="text-2xl font-bold mb-4">8. Kontak</h2>
                  <p class="mb-4">
                    Untuk pertanyaan tentang Syarat Layanan ini, silakan hubungi:
                  </p>
                  <ul class="list-disc pl-6 mb-4 space-y-2">
                    <li>Email: support@ecobank.com</li>
                    <li>WhatsApp: +62 812 3456 7890</li>
                  </ul>
                </section>
              </div>
            </div>
          </div>
        </div>

        <footer class="flex justify-center mt-auto">
          <div class="flex max-w-[960px] flex-1 flex-col">
            <footer class="flex flex-col gap-6 px-5 py-10 text-center @container">
              <div class="flex flex-wrap items-center justify-center gap-6 @[480px]:flex-row @[480px]:justify-around">
                <a class="text-[#51946c] text-base font-normal leading-normal min-w-40 hover:text-[#38e07b] transition" href="/privacy-policy">Privacy Policy</a>
                <a class="text-[#51946c] text-base font-normal leading-normal min-w-40 hover:text-[#38e07b] transition" href="/terms-of-service">Terms of Service</a>
              </div>
              <p class="text-[#51946c] text-base font-normal leading-normal">© {{ date('Y') }} EcoBank. All rights reserved.</p>
            </footer>
          </div>
        </footer>
      </div>
    </div>
  </body>
</html>

