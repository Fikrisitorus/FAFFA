@extends('layouts.guest')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-[#0e1a13] mb-2">Daftar Pengepul</h2>
        <p class="text-sm text-[#51946c]">Buat akun baru untuk menjadi pengepul sampah</p>
    </div>

    <form method="POST" action="{{ route('register.pengepul') }}" class="space-y-5">
        @csrf

        <!-- Nama Lengkap -->
        <div>
            <label for="name" class="block text-sm font-medium text-[#0e1a13] mb-2">
                Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <input 
                id="name" 
                type="text" 
                name="name" 
                value="{{ old('name') }}" 
                required 
                autofocus
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#38e07b] focus:border-[#38e07b] outline-none transition text-[#0e1a13] placeholder-gray-400"
                placeholder="Nama lengkap Anda"
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

        <!-- No HP -->
        <div>
            <label for="phone" class="block text-sm font-medium text-[#0e1a13] mb-2">
                No HP <span class="text-red-500">*</span>
            </label>
            <input 
                id="phone" 
                type="text" 
                name="phone" 
                value="{{ old('phone') }}" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#38e07b] focus:border-[#38e07b] outline-none transition text-[#0e1a13] placeholder-gray-400"
                placeholder="081234567890"
            />
            @error('phone')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Alamat -->
        <div>
            <label for="address" class="block text-sm font-medium text-[#0e1a13] mb-2">
                Alamat <span class="text-red-500">*</span>
            </label>
            <input 
                id="address" 
                type="text" 
                name="address" 
                value="{{ old('address') }}" 
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#38e07b] focus:border-[#38e07b] outline-none transition text-[#0e1a13] placeholder-gray-400"
                placeholder="Alamat lengkap Anda"
            />
            @error('address')
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