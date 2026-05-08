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
    <title>Artikel Edukasi - Bank Sampah</title>
    <meta name="description" content="Baca artikel edukasi tentang pengelolaan sampah, daur ulang, dan tips menjaga lingkungan. Pelajari cara berkontribusi untuk lingkungan yang lebih bersih.">
    <meta name="keywords" content="artikel bank sampah, edukasi sampah, daur ulang, tips lingkungan, pengelolaan sampah">
    <meta name="author" content="EcoBank">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/artikel') }}">
    <meta property="og:title" content="Artikel Edukasi - Bank Sampah">
    <meta property="og:description" content="Baca artikel edukasi tentang pengelolaan sampah, daur ulang, dan tips menjaga lingkungan.">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/artikel') }}">
    <meta property="twitter:title" content="Artikel Edukasi - Bank Sampah">
    <meta property="twitter:description" content="Baca artikel edukasi tentang pengelolaan sampah, daur ulang, dan tips menjaga lingkungan.">
    
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
            <!-- Header -->
            <div class="px-4 py-6">
              <h1 class="text-[#0e1a13] text-3xl font-bold leading-tight tracking-[-0.015em] mb-2">Educational Articles</h1>
              <p class="text-[#51946c] text-base">Artikel edukasi tentang pengelolaan sampah dan lingkungan</p>
            </div>

            <!-- Artikel Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-4 py-4">
              @forelse($artikel as $item)
                <a href="{{ route('artikel.show', $item->slug) }}" class="flex flex-col gap-3 group cursor-pointer hover:opacity-80 transition-opacity">
                  <div class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg transition-transform group-hover:scale-105" style="background-image: url('{{ $item->featured_image ? asset('storage/' . $item->featured_image) : 'https://via.placeholder.com/400x300?text=Artikel' }}');"></div>
                  <div>
                    <p class="text-[#0e1a13] text-base font-medium leading-normal group-hover:text-[#38e07b] transition-colors">{{ Str::limit($item->title, 60) }}</p>
                    <p class="text-[#51946c] text-sm font-normal leading-normal mt-1">{{ Str::limit($item->excerpt ?? strip_tags($item->content), 100) }}</p>
                    <div class="flex items-center gap-2 mt-2 text-xs text-gray-500">
                      @if($item->author)
                        <span>{{ $item->author->name }}</span>
                      @endif
                      <span>•</span>
                      <span>{{ $item->published_at ? $item->published_at->format('d M Y') : $item->created_at->format('d M Y') }}</span>
                    </div>
                  </div>
                </a>
              @empty
                <div class="col-span-full text-center py-12">
                  <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                  <p class="text-gray-500 text-lg">Belum ada artikel tersedia</p>
                  <a href="/" class="inline-block mt-4 text-[#38e07b] hover:text-[#2ecc71] transition">Kembali ke Home</a>
                </div>
              @endforelse
            </div>

            <!-- Pagination -->
            @if($artikel->hasPages())
              <div class="px-4 py-6">
                <div class="flex justify-center gap-2">
                  @if($artikel->onFirstPage())
                    <span class="px-4 py-2 text-gray-400 cursor-not-allowed">Previous</span>
                  @else
                    <a href="{{ $artikel->previousPageUrl() }}" class="px-4 py-2 bg-[#e8f2ec] text-[#0e1a13] rounded-lg hover:bg-[#d1e7dd] transition">Previous</a>
                  @endif
                  
                  @foreach($artikel->getUrlRange(1, $artikel->lastPage()) as $page => $url)
                    @if($page == $artikel->currentPage())
                      <span class="px-4 py-2 bg-[#38e07b] text-white rounded-lg">{{ $page }}</span>
                    @else
                      <a href="{{ $url }}" class="px-4 py-2 bg-[#e8f2ec] text-[#0e1a13] rounded-lg hover:bg-[#d1e7dd] transition">{{ $page }}</a>
                    @endif
                  @endforeach
                  
                  @if($artikel->hasMorePages())
                    <a href="{{ $artikel->nextPageUrl() }}" class="px-4 py-2 bg-[#e8f2ec] text-[#0e1a13] rounded-lg hover:bg-[#d1e7dd] transition">Next</a>
                  @else
                    <span class="px-4 py-2 text-gray-400 cursor-not-allowed">Next</span>
                  @endif
                </div>
              </div>
            @endif

            <!-- Back to Home -->
            <div class="px-4 py-6 text-center">
              <a href="/" class="inline-flex items-center px-4 py-2 text-sm font-medium text-[#0e1a13] bg-[#e8f2ec] rounded-lg hover:bg-[#d1e7dd] transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Home
              </a>
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

