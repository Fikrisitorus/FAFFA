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
    <title>404 - Halaman Tidak Ditemukan - Bank Sampah</title>
    <link rel="icon" type="image/x-icon" href="data:image/x-icon;base64," />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
        
        <div class="px-4 sm:px-40 flex flex-1 justify-center items-center py-5">
          <div class="layout-content-container flex flex-col max-w-[960px] flex-1 text-center">
            <div class="px-4 py-12">
              <div class="mb-6">
                <h1 class="text-[#0e1a13] text-9xl font-black mb-4">404</h1>
                <h2 class="text-[#0e1a13] text-3xl font-bold mb-4">Halaman Tidak Ditemukan</h2>
                <p class="text-[#51946c] text-lg mb-8">
                  Maaf, halaman yang Anda cari tidak ditemukan atau telah dipindahkan.
                </p>
              </div>
              
              <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/" class="inline-flex items-center justify-center px-6 py-3 bg-[#38e07b] text-[#0e1a13] text-base font-bold rounded-lg hover:bg-[#2ecc71] transition">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                  </svg>
                  Kembali ke Home
                </a>
                <a href="/artikel" class="inline-flex items-center justify-center px-6 py-3 bg-[#e8f2ec] text-[#0e1a13] text-base font-bold rounded-lg hover:bg-[#d1e7dd] transition">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                  Lihat Artikel
                </a>
              </div>
            </div>
          </div>
        </div>

        <footer class="flex justify-center mt-auto">
          <div class="flex max-w-[960px] flex-1 flex-col">
            <footer class="flex flex-col gap-6 px-5 py-10 text-center @container">
              <div class="flex flex-wrap items-center justify-center gap-6 @[480px]:flex-row @[480px]:justify-around">
                <a class="text-[#51946c] text-base font-normal leading-normal min-w-40 hover:text-[#38e07b] transition" href="{{ route('privacy-policy') }}">Privacy Policy</a>
                <a class="text-[#51946c] text-base font-normal leading-normal min-w-40 hover:text-[#38e07b] transition" href="{{ route('terms-of-service') }}">Terms of Service</a>
              </div>
              <p class="text-[#51946c] text-base font-normal leading-normal">© {{ date('Y') }} EcoBank. All rights reserved.</p>
            </footer>
          </div>
        </footer>
      </div>
    </div>
  </body>
</html>

