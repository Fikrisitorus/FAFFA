@extends('layouts.guest')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-[#0e1a13] mb-2">Daftar Kelompok Nasabah</h2>
        <p class="text-sm text-[#51946c]">Buat akun baru untuk kelompok nasabah Anda</p>
    </div>

    <form method="POST" action="{{ route('register.kelompok-nasabah') }}" class="space-y-5">
        @csrf

        <!-- Nama Kelompok -->
        <div>
            <label for="nama_kelompok" class="block text-sm font-medium text-[#0e1a13] mb-2">
                Nama Kelompok <span class="text-red-500">*</span>
            </label>
            <input 
                id="nama_kelompok" 
                type="text" 
                name="nama_kelompok" 
                value="{{ old('nama_kelompok') }}" 
                required 
                autofocus
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#38e07b] focus:border-[#38e07b] outline-none transition text-[#0e1a13] placeholder-gray-400"
                placeholder="Contoh: Kelompok Sampah Hijau"
            />
            @error('nama_kelompok')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Deskripsi -->
        <div>
            <label for="deskripsi" class="block text-sm font-medium text-[#0e1a13] mb-2">
                Deskripsi
            </label>
            <textarea 
                id="deskripsi" 
                name="deskripsi" 
                rows="3"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#38e07b] focus:border-[#38e07b] outline-none transition text-[#0e1a13] placeholder-gray-400"
                placeholder="Deskripsi singkat tentang kelompok Anda"
            >{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Lokasi -->
        <div>
            <label for="lokasi" class="block text-sm font-medium text-[#0e1a13] mb-2">
                Lokasi <span class="text-red-500">*</span>
            </label>
            <input 
                id="lokasi" 
                type="text" 
                name="lokasi" 
                value="{{ old('lokasi') }}" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#38e07b] focus:border-[#38e07b] outline-none transition text-[#0e1a13] placeholder-gray-400"
                placeholder="Alamat lengkap lokasi kelompok"
            />
            @error('lokasi')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Nama Ketua/Perwakilan -->
        <div>
            <label for="name" class="block text-sm font-medium text-[#0e1a13] mb-2">
                Nama Ketua/Perwakilan <span class="text-red-500">*</span>
            </label>
            <input 
                id="name" 
                type="text" 
                name="name" 
                value="{{ old('name') }}" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#38e07b] focus:border-[#38e07b] outline-none transition text-[#0e1a13] placeholder-gray-400"
                placeholder="Nama lengkap ketua atau perwakilan"
            />
            @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-[#0e1a13] mb-2">
                Email <span class="text-red-500">*</span>
            </label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#38e07b] focus:border-[#38e07b] outline-none transition text-[#0e1a13] placeholder-gray-400"
                placeholder="nama@email.com"
            />
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-[#0e1a13] mb-2">
                Password <span class="text-red-500">*</span>
            </label>
            <input 
                id="password" 
                type="password" 
                name="password" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#38e07b] focus:border-[#38e07b] outline-none transition text-[#0e1a13] placeholder-gray-400"
                placeholder="••••••••"
            />
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Konfirmasi Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-[#0e1a13] mb-2">
                Konfirmasi Password <span class="text-red-500">*</span>
            </label>
            <input 
                id="password_confirmation" 
                type="password" 
                name="password_confirmation" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#38e07b] focus:border-[#38e07b] outline-none transition text-[#0e1a13] placeholder-gray-400"
                placeholder="••••••••"
            />
        </div>

        <!-- Submit Button -->
        <button 
            type="submit"
            class="w-full py-3 px-4 bg-[#38e07b] text-[#0e1a13] font-bold rounded-lg hover:bg-[#2ecc71] focus:outline-none focus:ring-2 focus:ring-[#38e07b] focus:ring-offset-2 transition shadow-sm"
        >
            Daftar
        </button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm text-[#51946c]">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="font-semibold text-[#38e07b] hover:text-[#2ecc71] transition">Masuk</a>
        </p>
    </div>
@endsection 