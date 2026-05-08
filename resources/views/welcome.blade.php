<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
  <link rel="stylesheet" as="style" onload="this.rel='stylesheet'"
    href="https://fonts.googleapis.com/css2?display=swap&amp;family=Manrope%3Awght%40400%3B500%3B700%3B800&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900" />

  <title>Bank Sampah - Platform Pengelolaan Sampah Berkelanjutan</title>
  <meta name="description"
    content="Bank Sampah adalah platform inovatif untuk pengelolaan sampah berkelanjutan. Daftarkan diri Anda dan mulai berkontribusi untuk lingkungan yang lebih bersih.">
  <meta name="keywords"
    content="bank sampah, pengelolaan sampah, daur ulang, lingkungan, sampah organik, sampah anorganik, eco-friendly">
  <meta name="author" content="EcoBank">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ url('/') }}">
  <meta property="og:title" content="Bank Sampah - Platform Pengelolaan Sampah Berkelanjutan">
  <meta property="og:description"
    content="Bank Sampah adalah platform inovatif untuk pengelolaan sampah berkelanjutan. Daftarkan diri Anda dan mulai berkontribusi untuk lingkungan yang lebih bersih.">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:url" content="{{ url('/') }}">
  <meta property="twitter:title" content="Bank Sampah - Platform Pengelolaan Sampah Berkelanjutan">
  <meta property="twitter:description"
    content="Bank Sampah adalah platform inovatif untuk pengelolaan sampah berkelanjutan. Daftarkan diri Anda dan mulai berkontribusi untuk lingkungan yang lebih bersih.">

  <link rel="icon" type="image/x-icon" href="data:image/x-icon;base64," />

  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <style>
    html {
      scroll-behavior: smooth;
    }
  </style>
</head>

<body>
  <div class="relative flex size-full min-h-screen flex-col bg-[#f8fbfa] group/design-root overflow-x-hidden"
    style='font-family: Manrope, "Noto Sans", sans-serif;'>
    <div class="layout-container flex h-full grow flex-col">
      <header class="shadow-sm bg-white sticky top-0 z-50">
        <div class="flex items-center justify-between px-4 sm:px-10 py-3">
          <a href="/" class="flex items-center gap-2">
            <div class="size-7">
              <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_6_535)">
                  <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M47.2426 24L24 47.2426L0.757355 24L24 0.757355L47.2426 24ZM12.2426 21H35.7574L24 9.24264L12.2426 21Z"
                    fill="#38e07b"></path>
                </g>
                <defs>
                  <clipPath id="clip0_6_535">
                    <rect width="48" height="48" fill="white"></rect>
                  </clipPath>
                </defs>
              </svg>
            </div>
            <span class="font-bold text-lg text-[#0e1a13] tracking-tight">Bank Sampah</span>
          </a>
          <!-- Hamburger for mobile -->
          <button id="menu-toggle"
            class="sm:hidden p-2 rounded focus:outline-none focus:ring-2 focus:ring-green-400 transition z-[101] relative"
            aria-label="Toggle menu">
            <svg id="hamburger-icon" class="w-7 h-7" fill="none" stroke="#38e07b" viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            <svg id="close-icon" class="w-7 h-7 hidden" fill="none" stroke="#38e07b" viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
          <nav id="main-menu"
            class="fixed sm:static top-0 left-0 w-full sm:w-auto h-full sm:h-auto bg-white/95 sm:bg-transparent shadow-lg sm:shadow-none flex flex-col sm:flex-row items-center justify-start sm:justify-center gap-4 sm:gap-9 px-6 sm:px-0 py-16 sm:py-0 pb-8 sm:pb-0 hidden sm:flex transition-all duration-300 z-[100] overflow-y-auto sm:overflow-visible">
            <a class="text-[#0e1a13] text-lg sm:text-base font-medium leading-normal hover:text-[#38e07b] transition w-full sm:w-auto text-center py-2"
              href="#home">Home</a>
            <a class="text-[#0e1a13] text-lg sm:text-base font-medium leading-normal hover:text-[#38e07b] transition w-full sm:w-auto text-center py-2"
              href="#about">About</a>
            <a class="text-[#0e1a13] text-lg sm:text-base font-medium leading-normal hover:text-[#38e07b] transition w-full sm:w-auto text-center py-2"
              href="#articles">Articles</a>
            <a class="text-[#0e1a13] text-lg sm:text-base font-medium leading-normal hover:text-[#38e07b] transition w-full sm:w-auto text-center py-2"
              href="#regulations">Regulation</a>
            <a class="text-[#0e1a13] text-lg sm:text-base font-medium leading-normal hover:text-[#38e07b] transition w-full sm:w-auto text-center py-2"
              href="#location">Location</a>
            <a class="text-[#0e1a13] text-lg sm:text-base font-medium leading-normal hover:text-[#38e07b] transition w-full sm:w-auto text-center py-2"
              href="#transparansi">Transparansi</a>
            <a class="text-[#0e1a13] text-lg sm:text-base font-medium leading-normal hover:text-[#38e07b] transition w-full sm:w-auto text-center py-2"
              href="#help-desk">Help Desk</a>
            <a href="/login"
              class="flex w-auto min-w-[160px] max-w-[240px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-[#38e07b] text-[#0e1a13] text-base font-bold leading-normal tracking-[0.015em] hover:bg-[#2ecc71] transition mt-6 sm:mt-0">
              <span class="truncate">Login</span>
            </a>
          </nav>
        </div>
        <!-- Mobile Menu Backdrop -->
        <div id="menu-backdrop" class="hidden fixed inset-0 bg-black/50 z-[99] sm:hidden"></div>
        <script>
          const menuToggle = document.getElementById('menu-toggle');
          const mainMenu = document.getElementById('main-menu');
          const menuBackdrop = document.getElementById('menu-backdrop');
          const hamburgerIcon = document.getElementById('hamburger-icon');
          const closeIcon = document.getElementById('close-icon');

          function openMenu() {
            mainMenu.classList.remove('hidden');
            menuBackdrop.classList.remove('hidden');
            hamburgerIcon.classList.add('hidden');
            closeIcon.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
          }

          function closeMenu() {
            mainMenu.classList.add('hidden');
            menuBackdrop.classList.add('hidden');
            hamburgerIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
            document.body.style.overflow = '';
          }

          menuToggle.addEventListener('click', () => {
            if (mainMenu.classList.contains('hidden')) {
              openMenu();
            } else {
              closeMenu();
            }
          });

          // Close menu when clicking backdrop
          menuBackdrop.addEventListener('click', closeMenu);

          // Close menu on nav link click (mobile)
          mainMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
              if (window.innerWidth < 640) {
                closeMenu();
              }
            });
          });

          // Handle window resize
          window.addEventListener('resize', () => {
            if (window.innerWidth >= 640) {
              closeMenu();
              mainMenu.classList.add('sm:flex');
            } else {
              mainMenu.classList.remove('sm:flex');
            }
          });
        </script>
      </header>
      <div class="px-4 sm:px-40 flex flex-1 justify-center py-5">
        <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
          <div class="@container" id="home">
            <div class="flex flex-col gap-6 px-4 py-10 @[480px]:gap-8 @[864px]:flex-row">
              <div
                class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg @[480px]:h-auto @[480px]:min-w-[400px] @[864px]:w-full"
                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDkOEPwFwLzVbFw0lM6vqNz6QyO0RPxmMgl5E1Bh9C7bxfvGsM6QXxn5rBMn-E7JYNrjxxjciHc3TL8fl9PCCj_pfMFcDpeH2sTuDg7gyGhyQyuPFxcqobnPDb0hRPR1b4SSzwtXePSwYpLl2ph5qv4rqtGVz6CndtbScECHIwG3hFfpNu2EXHezGqRL5kZWZteOGgobpM-hCmTWph58LU7hOAKVw99xUp8aLV4kTSD98_t9uK-ZIHIIvJ863Di0_k-_ko2vF8yKeop");'>
              </div>
              <div class="flex flex-col gap-6 @[480px]:min-w-[400px] @[480px]:gap-8 @[864px]:justify-center">
                <div class="flex flex-col gap-2 text-left">
                  <h1
                    class="text-[#0e1a13] text-4xl font-black leading-tight tracking-[-0.033em] @[480px]:text-5xl @[480px]:font-black @[480px]:leading-tight @[480px]:tracking-[-0.033em]">
                    Manage Waste, Save the Environment
                  </h1>
                  <h2
                    class="text-[#0e1a13] text-sm font-normal leading-normal @[480px]:text-base @[480px]:font-normal @[480px]:leading-normal">
                    Discover the importance of waste management and how you can contribute to a healthier planet.
                  </h2>
                </div>
                <div class="flex flex-col gap-2 w-full">
                  <a href="/register/kelompok-nasabah"
                    class="flex w-full min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#38e07b] text-[#0e1a13] text-sm font-bold leading-normal tracking-[0.015em] hover:bg-[#2ecc71]">Daftar
                    Kelompok Nasabah</a>
                  <a href="/register/pengepul"
                    class="flex w-full min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#38b6e0] text-[#0e1a13] text-sm font-bold leading-normal tracking-[0.015em] hover:bg-[#3498db]">Daftar
                    Pengepul</a>
                </div>
              </div>
            </div>
          </div>
          <h2 class="text-[#0e1a13] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5" id="about">
            About Us</h2>
          <p class="text-[#0e1a13] text-base font-normal leading-normal pb-3 pt-1 px-4">
            EcoBank is dedicated to promoting sustainable waste management practices. Our application provides a
            platform for individuals and communities to actively participate
            in recycling and reducing waste. We believe that by working together, we can create a cleaner and more
            sustainable future for generations to come. Our system not only
            helps the environment by reducing landfill waste but also benefits society by creating economic
            opportunities through recycling initiatives.
          </p>
          <h2 class="text-[#0e1a13] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5"
            id="articles">Educational Articles</h2>
          <div class="grid grid-cols-[repeat(auto-fit,minmax(158px,1fr))] gap-3 p-4">
            @if(isset($artikelTerbaru) && $artikelTerbaru->count() > 0)
              @foreach($artikelTerbaru as $artikel)
                <a href="{{ route('artikel.show', $artikel->slug) }}"
                  class="flex flex-col gap-3 pb-3 group cursor-pointer hover:opacity-80 transition-opacity">
                  <div
                    class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg transition-transform group-hover:scale-105"
                    style="background-image: url('{{ $artikel->featured_image ? asset('storage/' . $artikel->featured_image) : 'https://via.placeholder.com/400x300?text=Artikel' }}');">
                  </div>
                  <div>
                    <p
                      class="text-[#0e1a13] text-base font-medium leading-normal group-hover:text-[#38e07b] transition-colors">
                      {{ Str::limit($artikel->title, 50) }}
                    </p>
                    <p class="text-[#51946c] text-sm font-normal leading-normal">
                      {{ Str::limit($artikel->excerpt ?? strip_tags($artikel->content), 80) }}
                    </p>
                  </div>
                </a>
              @endforeach
            @else
              <div class="col-span-full text-center py-8">
                <p class="text-gray-500 dark:text-gray-400">Belum ada artikel tersedia</p>
              </div>
            @endif
          </div>
          @if(isset($artikelTerbaru) && $artikelTerbaru->count() > 0)
            <div class="flex px-4 py-3 justify-center">
              <a href="{{ route('artikel.index') }}"
                class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#e8f2ec] text-[#0e1a13] text-sm font-bold leading-normal tracking-[0.015em] hover:bg-[#d1e7dd] transition-colors">
                <span class="truncate">See All Articles</span>
              </a>
            </div>
          @endif
          <h2 class="text-[#0e1a13] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5"
            id="regulations">Regulations and Publications</h2>
          <p class="text-[#0e1a13] text-base font-normal leading-normal pb-3 pt-1 px-4">
            Explore the regulations and guidelines governing waste management in our region. Download our publications
            for detailed information on best practices and initiatives.
          </p>
          <div
            class="flex items-center gap-4 bg-[#f8fbfa] px-4 min-h-14 justify-between hover:bg-[#e8f2ec] transition-colors cursor-pointer">
            <p class="text-[#0e1a13] text-base font-normal leading-normal flex-1 truncate">Download Waste Management
              Regulations (PDF)</p>
            <div class="shrink-0">
              <div class="text-[#0e1a13] flex size-7 items-center justify-center" data-icon="DownloadSimple"
                data-size="24px" data-weight="regular">
                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor"
                  viewBox="0 0 256 256">
                  <path
                    d="M224,152v56a16,16,0,0,1-16,16H48a16,16,0,0,1-16-16V152a8,8,0,0,1,16,0v56H208V152a8,8,0,0,1,16,0Zm-101.66,5.66a8,8,0,0,0,11.32,0l40-40a8,8,0,0,0-11.32-11.32L136,132.69V40a8,8,0,0,0-16,0v92.69L93.66,106.34a8,8,0,0,0-11.32,11.32Z">
                  </path>
                </svg>
              </div>
            </div>
          </div>
          <div
            class="flex items-center gap-4 bg-[#f8fbfa] px-4 min-h-14 justify-between hover:bg-[#e8f2ec] transition-colors cursor-pointer">
            <p class="text-[#0e1a13] text-base font-normal leading-normal flex-1 truncate">View Our Latest Publication:
              Sustainable Living Guide</p>
            <div class="shrink-0">
              <div class="text-[#0e1a13] flex size-7 items-center justify-center" data-icon="ArrowRight"
                data-size="24px" data-weight="regular">
                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor"
                  viewBox="0 0 256 256">
                  <path
                    d="M221.66,133.66l-72,72a8,8,0,0,1-11.32-11.32L196.69,136H40a8,8,0,0,1,0-16H196.69L138.34,61.66a8,8,0,0,1,11.32-11.32l72,72A8,8,0,0,1,221.66,133.66Z">
                  </path>
                </svg>
              </div>
            </div>
          </div>
          <h2 class="text-[#0e1a13] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5"
            id="location">Location</h2>
          <div class="flex px-4 py-3">
            <div class="w-full rounded-lg overflow-hidden shadow-md">
              <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126748.56347862248!2d110.36060755!3d-7.797068449999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a5787bd5b6bc5%3A0x21723fd4d3684f71!2sYogyakarta%2C%20Yogyakarta%20City%2C%20Special%20Region%20of%20Yogyakarta!5e0!3m2!1sen!2sid!4v1704556800000!5m2!1sen!2sid"
                width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade" class="rounded-lg">
              </iframe>
            </div>
          </div>
          <p class="text-[#0e1a13] text-base font-normal leading-normal pb-3 pt-1 px-4">Yogyakarta, Special Region of
            Yogyakarta, Indonesia</p>
          <div class="flex px-4 py-3 justify-center">
            <a href="https://www.google.com/maps/search/?api=1&query=Yogyakarta,+Indonesia" target="_blank"
              class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#e8f2ec] text-[#0e1a13] text-sm font-bold leading-normal tracking-[0.015em] hover:bg-[#d1e7dd] transition-colors">
              <span class="truncate">See Full Location</span>
            </a>
          </div>
          <!-- Transparansi Donasi Section -->
          <div class="px-4 py-6" id="transparansi">
            <h2 class="text-[#0e1a13] text-[22px] font-bold leading-tight tracking-[-0.015em] pb-4">Transparansi Donasi
            </h2>
            <div class="bg-[#f8fbfa] dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
              <!-- Stats Cards -->
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Total Donasi Masuk -->
                <div
                  class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Donasi Masuk</p>
                      <p class="text-2xl font-bold text-[#0e1a13] dark:text-white">
                        Rp {{ number_format($totalDonasiMasuk ?? 0, 0, ',', '.') }}
                      </p>
                    </div>
                    <div class="p-3 bg-[#e8f2ec] dark:bg-gray-700 rounded-full">
                      <svg class="w-6 h-6 text-[#51946c] dark:text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                      </svg>
                    </div>
                  </div>
                </div>

                <!-- Total Pengeluaran -->
                <div
                  class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Pengeluaran</p>
                      <p class="text-2xl font-bold text-[#0e1a13] dark:text-white">
                        Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}
                      </p>
                    </div>
                    <div class="p-3 bg-[#e8f2ec] dark:bg-gray-700 rounded-full">
                      <svg class="w-6 h-6 text-[#51946c] dark:text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                      </svg>
                    </div>
                  </div>
                </div>

                <!-- Saldo Donasi -->
                <div
                  class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Saldo Donasi Saat Ini</p>
                      <p class="text-2xl font-bold text-[#0e1a13] dark:text-white">
                        Rp {{ number_format($saldoDonasi ?? 0, 0, ',', '.') }}
                      </p>
                    </div>
                    <div class="p-3 bg-[#e8f2ec] dark:bg-gray-700 rounded-full">
                      <svg class="w-6 h-6 text-[#51946c] dark:text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Info Transparansi -->
              <div
                class="bg-[#e8f2ec] dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 mb-6">
                <div class="flex items-start gap-3">
                  <svg class="w-5 h-5 text-[#51946c] dark:text-gray-300 flex-shrink-0 mt-0.5" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                      clip-rule="evenodd"></path>
                  </svg>
                  <div>
                    <p class="text-sm font-medium text-[#0e1a13] dark:text-white mb-1">Transparansi Keuangan</p>
                    <p class="text-sm text-[#51946c] dark:text-gray-300">
                      Kami berkomitmen untuk transparansi penuh dalam pengelolaan donasi. Semua donasi yang masuk dan
                      pengeluaran yang dilakukan dapat dilihat oleh publik untuk memastikan akuntabilitas.
                    </p>
                  </div>
                </div>
              </div>

              <!-- Riwayat Pengeluaran Terbaru -->
              @if(isset($riwayatPengeluaran) && $riwayatPengeluaran->count() > 0)
                <div>
                  <h3 class="text-lg font-semibold text-[#0e1a13] dark:text-white mb-4">Riwayat Pengeluaran Terbaru</h3>
                  <div class="space-y-3">
                    @foreach($riwayatPengeluaran as $pengeluaran)
                      <div
                        class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700 shadow-sm">
                        <div class="flex items-center justify-between">
                          <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                              <span
                                class="px-2 py-1 text-xs font-medium rounded-full bg-[#e8f2ec] text-[#51946c] dark:bg-gray-700 dark:text-gray-300">
                                {{ $pengeluaran->kategori_label }}
                              </span>
                              <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($pengeluaran->tanggal_pengeluaran)->format('d M Y') }}
                              </span>
                            </div>
                            <p class="font-medium text-[#0e1a13] dark:text-white">{{ $pengeluaran->nama_pengeluaran }}</p>
                            @if($pengeluaran->deskripsi)
                              <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                {{ Str::limit($pengeluaran->deskripsi, 100) }}
                              </p>
                            @endif
                            @if($pengeluaran->penerima_pengeluaran)
                              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Penerima: {{ $pengeluaran->penerima_pengeluaran }}
                              </p>
                            @endif
                          </div>
                          <div class="text-right ml-4">
                            <p class="text-lg font-bold text-[#0e1a13] dark:text-white">
                              Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}
                            </p>
                            @if($pengeluaran->approvedBy)
                              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Disetujui oleh: {{ $pengeluaran->approvedBy->name }}
                              </p>
                            @endif
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              @else
                <div
                  class="text-center py-8 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                  <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                  </svg>
                  <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Belum ada riwayat pengeluaran</p>
                </div>
              @endif

              <div class="mt-6 flex justify-center">
                <a href="{{ route('transparansi.index') }}"
                  class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-semibold bg-[#e8f2ec] text-[#0e1a13] hover:bg-[#d1e7dd] transition-colors border border-gray-200">
                  Lihat Semua Transparansi Donasi
                </a>
              </div>
            </div>
          </div>

          <h2 class="text-[#0e1a13] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5"
            id="help-desk">Help Desk</h2>
          <p class="text-[#0e1a13] text-base font-normal leading-normal pb-3 pt-1 px-4">
            Contact us for any inquiries or assistance. We're here to help you on your journey to sustainable waste
            management.
          </p>
          <a href="https://wa.me/621122334455" target="_blank"
            class="flex items-center gap-4 bg-[#f8fbfa] px-4 min-h-14 hover:bg-[#e8f2ec] transition-colors cursor-pointer">
            <div class="text-[#0e1a13] flex items-center justify-center rounded-lg bg-[#e8f2ec] shrink-0 size-10"
              data-icon="WhatsappLogo" data-size="24px" data-weight="regular">
              <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor"
                viewBox="0 0 256 256">
                <path
                  d="M187.58,144.84l-32-16a8,8,0,0,0-8,.5l-14.69,9.8a40.55,40.55,0,0,1-16-16l9.8-14.69a8,8,0,0,0,.5-8l-16-32A8,8,0,0,0,104,64a40,40,0,0,0-40,40,88.1,88.1,0,0,0,88,88,40,40,0,0,0,40-40A8,8,0,0,0,187.58,144.84ZM152,176a72.08,72.08,0,0,1-72-72A24,24,0,0,1,99.29,80.46l11.48,23L101,118a8,8,0,0,0-.73,7.51,56.47,56.47,0,0,0,30.15,30.15A8,8,0,0,0,138,155l14.61-9.74,23,11.48A24,24,0,0,1,152,176ZM128,24A104,104,0,0,0,36.18,176.88L24.83,210.93a16,16,0,0,0,20.24,20.24l34.05-11.35A104,104,0,1,0,128,24Zm0,192a87.87,87.87,0,0,1-44.06-11.81,8,8,0,0,0-6.54-.67L40,216,52.47,178.6a8,8,0,0,0-.66-6.54A88,88,0,1,1,128,216Z">
                </path>
              </svg>
            </div>
            <p class="text-[#0e1a13] text-base font-normal leading-normal flex-1 truncate">WhatsApp: 1122334455
            </p>
          </a>
          <a href="mailto:info@banksampah.demo"
            class="flex items-center gap-4 bg-[#f8fbfa] px-4 min-h-14 hover:bg-[#e8f2ec] transition-colors cursor-pointer">
            <div class="text-[#0e1a13] flex items-center justify-center rounded-lg bg-[#e8f2ec] shrink-0 size-10"
              data-icon="Envelope" data-size="24px" data-weight="regular">
              <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor"
                viewBox="0 0 256 256">
                <path
                  d="M224,48H32a8,8,0,0,0-8,8V192a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A8,8,0,0,0,224,48Zm-96,85.15L52.57,64H203.43ZM98.71,128,40,181.81V74.19Zm11.84,10.85,12,11.05a8,8,0,0,0,10.82,0l12-11.05,58,53.15H52.57ZM157.29,128,216,74.18V181.82Z">
                </path>
              </svg>
            </div>
            <p class="text-[#0e1a13] text-base font-normal leading-normal flex-1 truncate">Email: info@banksampah.demo
            </p>
          </a>
          <div class="@container">
            <div class="gap-2 px-4 flex flex-wrap justify-start">
              <a href="https://twitter.com" target="_blank"
                class="flex flex-col items-center gap-2 bg-[#f8fbfa] py-2.5 text-center w-20 hover:bg-[#e8f2ec] transition-colors cursor-pointer">
                <div class="rounded-full bg-[#e8f2ec] p-2.5">
                  <div class="text-[#0e1a13]" data-icon="TwitterLogo" data-size="20px" data-weight="regular">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor"
                      viewBox="0 0 256 256">
                      <path
                        d="M247.39,68.94A8,8,0,0,0,240,64H209.57A48.66,48.66,0,0,0,168.1,40a46.91,46.91,0,0,0-33.75,13.7A47.9,47.9,0,0,0,120,88v6.09C79.74,83.47,46.81,50.72,46.46,50.37a8,8,0,0,0-13.65,4.92c-4.31,47.79,9.57,79.77,22,98.18a110.93,110.93,0,0,0,21.88,24.2c-15.23,17.53-39.21,26.74-39.47,26.84a8,8,0,0,0-3.85,11.93c.75,1.12,3.75,5.05,11.08,8.72C53.51,229.7,65.48,232,80,232c70.67,0,129.72-54.42,135.75-124.44l29.91-29.9A8,8,0,0,0,247.39,68.94Zm-45,29.41a8,8,0,0,0-2.32,5.14C196,166.58,143.28,216,80,216c-10.56,0-18-1.4-23.22-3.08,11.51-6.25,27.56-17,37.88-32.48A8,8,0,0,0,92,169.08c-.47-.27-43.91-26.34-44-96,16,13,45.25,33.17,78.67,38.79A8,8,0,0,0,136,104V88a32,32,0,0,1,9.6-22.92A30.94,30.94,0,0,1,167.9,56c12.66.16,24.49,7.88,29.44,19.21A8,8,0,0,0,204.67,80h16Z">
                      </path>
                    </svg>
                  </div>
                </div>
                <p class="text-[#0e1a13] text-sm font-medium leading-normal">Follow us on Twitter</p>
              </a>
              <a href="https://facebook.com" target="_blank"
                class="flex flex-col items-center gap-2 bg-[#f8fbfa] py-2.5 text-center w-20 hover:bg-[#e8f2ec] transition-colors cursor-pointer">
                <div class="rounded-full bg-[#e8f2ec] p-2.5">
                  <div class="text-[#0e1a13]" data-icon="FacebookLogo" data-size="20px" data-weight="regular">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor"
                      viewBox="0 0 256 256">
                      <path
                        d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm8,191.63V152h24a8,8,0,0,0,0-16H136V112a16,16,0,0,1,16-16h16a8,8,0,0,0,0-16H152a32,32,0,0,0-32,32v24H96a8,8,0,0,0,0,16h24v63.63a88,88,0,1,1,16,0Z">
                      </path>
                    </svg>
                  </div>
                </div>
                <p class="text-[#0e1a13] text-sm font-medium leading-normal">Like us on Facebook</p>
              </a>
              <a href="https://instagram.com" target="_blank"
                class="flex flex-col items-center gap-2 bg-[#f8fbfa] py-2.5 text-center w-20 hover:bg-[#e8f2ec] transition-colors cursor-pointer">
                <div class="rounded-full bg-[#e8f2ec] p-2.5">
                  <div class="text-[#0e1a13]" data-icon="InstagramLogo" data-size="20px" data-weight="regular">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor"
                      viewBox="0 0 256 256">
                      <path
                        d="M128,80a48,48,0,1,0,48,48A48.05,48.05,0,0,0,128,80Zm0,80a32,32,0,1,1,32-32A32,32,0,0,1,128,160ZM176,24H80A56.06,56.06,0,0,0,24,80v96a56.06,56.06,0,0,0,56,56h96a56.06,56.06,0,0,0,56-56V80A56.06,56.06,0,0,0,176,24Zm40,152a40,40,0,0,1-40,40H80a40,40,0,0,1-40-40V80A40,40,0,0,1,80,40h96a40,40,0,0,1,40,40ZM192,76a12,12,0,1,1-12-12A12,12,0,0,1,192,76Z">
                      </path>
                    </svg>
                  </div>
                </div>
                <p class="text-[#0e1a13] text-sm font-medium leading-normal">Follow us on Instagram</p>
              </a>
            </div>
          </div>
        </div>
      </div>
      <footer class="flex justify-center">
        <div class="flex max-w-[960px] flex-1 flex-col">
          <footer class="flex flex-col gap-6 px-5 py-10 text-center @container">
            <div class="flex flex-wrap items-center justify-center gap-6 @[480px]:flex-row @[480px]:justify-around">
              <a class="text-[#51946c] text-base font-normal leading-normal min-w-40 hover:text-[#38e07b] transition"
                href="{{ route('privacy-policy') }}">Privacy Policy</a>
              <a class="text-[#51946c] text-base font-normal leading-normal min-w-40 hover:text-[#38e07b] transition"
                href="{{ route('terms-of-service') }}">Terms of Service</a>
            </div>
            <p class="text-[#51946c] text-base font-normal leading-normal">© {{ date('Y') }} EcoBank. All rights
              reserved.</p>
          </footer>
        </div>
      </footer>
    </div>
  </div>
</body>

</html>