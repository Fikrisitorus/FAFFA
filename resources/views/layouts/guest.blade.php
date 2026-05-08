<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login - Bank Sampah</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
        <link
          rel="stylesheet"
          as="style"
          onload="this.rel='stylesheet'"
          href="https://fonts.googleapis.com/css2?display=swap&amp;family=Manrope%3Awght%40400%3B500%3B700%3B800&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900"
        />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
        <style>
            html {
                scroll-behavior: smooth;
            }
            body {
                font-family: Manrope, "Noto Sans", sans-serif;
            }
        </style>
    </head>
    <body class="bg-[#f8fbfa]">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-[#f8fbfa] via-[#e8f2ec] to-[#f8fbfa]">
            <!-- Header -->
            <div class="w-full max-w-md mb-6">
                <a href="/" class="flex items-center justify-center gap-2 mb-4">
                    <div class="size-10">
                        <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_6_535)">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M47.2426 24L24 47.2426L0.757355 24L24 0.757355L47.2426 24ZM12.2426 21H35.7574L24 9.24264L12.2426 21Z" fill="#38e07b"></path>
                            </g>
                            <defs>
                                <clipPath id="clip0_6_535"><rect width="48" height="48" fill="white"></rect></clipPath>
                            </defs>
                        </svg>
                    </div>
                    <span class="font-bold text-2xl text-[#0e1a13] tracking-tight">Bank Sampah</span>
                </a>
            </div>

            <!-- Login Card -->
            <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-[#0e1a13] mb-2">Selamat Datang Kembali</h2>
                    <p class="text-sm text-[#51946c]">Masuk ke akun Anda untuk melanjutkan</p>
                </div>

                @yield('content')
            </div>

            <!-- Footer Links -->
            <div class="w-full max-w-md mt-6 text-center">
                <p class="text-sm text-[#51946c]">
                    Belum punya akun? 
                    <a href="{{ route('register.kelompok-nasabah') }}" class="font-semibold text-[#38e07b] hover:text-[#2ecc71] transition">Daftar sebagai Kelompok</a>
                    atau
                    <a href="{{ route('register.pengepul') }}" class="font-semibold text-[#38e07b] hover:text-[#2ecc71] transition">Daftar sebagai Pengepul</a>
                </p>
                <a href="/" class="inline-block mt-4 text-sm text-[#51946c] hover:text-[#38e07b] transition">
                    ← Kembali ke Homepage
                </a>
            </div>
        </div>
    </body>
</html>
