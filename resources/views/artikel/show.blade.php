<!DOCTYPE html>
<html>

<head>
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
  <link rel="stylesheet" as="style" onload="this.rel='stylesheet'"
    href="https://fonts.googleapis.com/css2?display=swap&amp;family=Manrope%3Awght%40400%3B500%3B700%3B800&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900" />
  <title>{{ $artikel->title }} - Bank Sampah</title>
  <meta name="description" content="{{ $artikel->excerpt ?? Str::limit(strip_tags($artikel->content), 160) }}">
  <meta name="keywords"
    content="{{ is_array($artikel->tags) ? implode(', ', $artikel->tags) : 'bank sampah, pengelolaan sampah, daur ulang' }}">
  <meta name="author" content="{{ $artikel->author->name ?? 'EcoBank' }}">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="article">
  <meta property="og:url" content="{{ url('/artikel/' . $artikel->slug) }}">
  <meta property="og:title" content="{{ $artikel->title }}">
  <meta property="og:description" content="{{ $artikel->excerpt ?? Str::limit(strip_tags($artikel->content), 160) }}">
  @if($artikel->featured_image)
    <meta property="og:image" content="{{ asset('storage/' . $artikel->featured_image) }}">
  @endif
  <meta property="article:published_time" content="{{ $artikel->published_at?->toIso8601String() }}">
  <meta property="article:author" content="{{ $artikel->author->name ?? 'EcoBank' }}">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:url" content="{{ url('/artikel/' . $artikel->slug) }}">
  <meta property="twitter:title" content="{{ $artikel->title }}">
  <meta property="twitter:description"
    content="{{ $artikel->excerpt ?? Str::limit(strip_tags($artikel->content), 160) }}">
  @if($artikel->featured_image)
    <meta property="twitter:image" content="{{ asset('storage/' . $artikel->featured_image) }}">
  @endif

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
          <nav class="flex items-center gap-4">
            <a href="/"
              class="text-[#0e1a13] text-base font-medium leading-normal hover:text-[#38e07b] transition">Home</a>
            <a href="/artikel"
              class="text-[#0e1a13] text-base font-medium leading-normal hover:text-[#38e07b] transition">Artikel</a>
            <a href="/login"
              class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#38e07b] text-[#0e1a13] text-base font-bold leading-normal tracking-[0.015em] hover:bg-[#2ecc71] transition">
              <span class="truncate">Login</span>
            </a>
          </nav>
        </div>
      </header>

      <div class="px-4 sm:px-40 flex flex-1 justify-center py-5">
        <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
          <!-- Back Button -->
          <div class="px-4 py-4">
            <a href="{{ route('artikel.index') }}"
              class="inline-flex items-center text-sm text-[#51946c] hover:text-[#38e07b] transition">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
              </svg>
              Kembali ke Daftar Artikel
            </a>
          </div>

          <!-- Artikel Content -->
          <article class="px-4 py-4">
            <!-- Header -->
            <div class="mb-6">
              <h1 class="text-[#0e1a13] text-4xl font-bold leading-tight tracking-[-0.015em] mb-4">{{ $artikel->title }}
              </h1>
              <div class="flex items-center gap-4 text-sm text-gray-600">
                @if($artikel->author)
                  <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>{{ $artikel->author->name }}</span>
                  </div>
                @endif
                <div class="flex items-center gap-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                  <span>{{ $artikel->published_at ? $artikel->published_at->format('d M Y') : $artikel->created_at->format('d M Y') }}</span>
                </div>
              </div>
            </div>

            <!-- Featured Image -->
            @if($artikel->featured_image)
              <div class="mb-6">
                <img src="{{ asset('storage/' . $artikel->featured_image) }}" alt="{{ $artikel->title }}"
                  class="w-full rounded-lg object-cover">
              </div>
            @endif

            <!-- Excerpt -->
            @if($artikel->excerpt)
              <div class="mb-6 p-4 bg-[#e8f2ec] rounded-lg border-l-4 border-[#38e07b]">
                <p class="text-[#0e1a13] text-lg font-medium italic">{{ $artikel->excerpt }}</p>
              </div>
            @endif

            <!-- Content -->
            <div class="mb-8">
              <div class="article-content prose prose-lg max-w-none">
                {!! $artikel->content !!}
              </div>
            </div>

            <style>
              .article-content {
                line-height: 1.8;
                color: #0e1a13;
              }

              .article-content h2 {
                font-size: 1.75rem;
                font-weight: 700;
                margin-top: 2rem;
                margin-bottom: 1rem;
                color: #0e1a13;
              }

              .article-content h3 {
                font-size: 1.5rem;
                font-weight: 700;
                margin-top: 1.5rem;
                margin-bottom: 0.75rem;
                color: #0e1a13;
              }

              .article-content h4 {
                font-size: 1.25rem;
                font-weight: 600;
                margin-top: 1.25rem;
                margin-bottom: 0.5rem;
                color: #0e1a13;
              }

              .article-content p {
                margin-bottom: 1rem;
                line-height: 1.8;
              }

              .article-content ul,
              .article-content ol {
                margin-left: 1.5rem;
                margin-bottom: 1rem;
                line-height: 1.8;
              }

              .article-content ul {
                list-style-type: disc;
              }

              .article-content ol {
                list-style-type: decimal;
              }

              .article-content li {
                margin-bottom: 0.5rem;
                padding-left: 0.25rem;
              }

              .article-content strong {
                font-weight: 700;
                color: #0e1a13;
              }

              .article-content em {
                font-style: italic;
              }

              .article-content a {
                color: #38e07b;
                text-decoration: underline;
              }

              .article-content a:hover {
                color: #2ecc71;
              }

              .article-content blockquote {
                border-left: 4px solid #38e07b;
                background-color: #e8f2ec;
                padding: 1rem 1.5rem;
                margin: 1.5rem 0;
                font-style: italic;
                border-radius: 0.25rem;
              }

              .article-content code {
                background-color: #f3f4f6;
                padding: 0.2rem 0.4rem;
                border-radius: 0.25rem;
                font-family: monospace;
                font-size: 0.9em;
              }

              .article-content pre {
                background-color: #1f2937;
                color: #f9fafb;
                padding: 1rem;
                border-radius: 0.5rem;
                overflow-x: auto;
                margin: 1rem 0;
              }

              .article-content pre code {
                background-color: transparent;
                padding: 0;
                color: inherit;
              }

              .article-content img {
                max-width: 100%;
                height: auto;
                border-radius: 0.5rem;
                margin: 1.5rem 0;
              }

              .article-content hr {
                border: 0;
                height: 1px;
                background-color: #e5e7eb;
                margin: 2rem 0;
              }
            </style>

            <!-- Tags -->
            @if($artikel->tags && count($artikel->tags) > 0)
              <div class="mb-8">
                <div class="flex flex-wrap gap-2">
                  @foreach($artikel->tags as $tag)
                    <span class="px-3 py-1 text-sm bg-[#e8f2ec] text-[#51946c] rounded-full">{{ $tag }}</span>
                  @endforeach
                </div>
              </div>
            @endif
          </article>

          <!-- Artikel Terkait -->
          @if($artikelTerkait->count() > 0)
            <div class="px-4 py-6 border-t border-gray-200">
              <h2 class="text-[#0e1a13] text-2xl font-bold mb-4">Artikel Terkait</h2>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($artikelTerkait as $item)
                  <a href="{{ route('artikel.show', $item->slug) }}"
                    class="flex flex-col gap-3 group cursor-pointer hover:opacity-80 transition-opacity">
                    <div
                      class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg transition-transform group-hover:scale-105"
                      style="background-image: url('{{ $item->featured_image ? asset('storage/' . $item->featured_image) : 'https://via.placeholder.com/400x300?text=Artikel' }}');">
                    </div>
                    <div>
                      <p
                        class="text-[#0e1a13] text-sm font-medium leading-normal group-hover:text-[#38e07b] transition-colors">
                        {{ Str::limit($item->title, 50) }}</p>
                      <p class="text-[#51946c] text-xs font-normal leading-normal mt-1">
                        {{ Str::limit($item->excerpt ?? strip_tags($item->content), 60) }}</p>
                    </div>
                  </a>
                @endforeach
              </div>
            </div>
          @endif

          <!-- Back to Articles -->
          <div class="px-4 py-6 text-center">
            <a href="{{ route('artikel.index') }}"
              class="inline-flex items-center px-4 py-2 text-sm font-medium text-[#0e1a13] bg-[#e8f2ec] rounded-lg hover:bg-[#d1e7dd] transition">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
              </svg>
              Lihat Semua Artikel
            </a>
          </div>
        </div>
      </div>

      <footer class="flex justify-center mt-auto">
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