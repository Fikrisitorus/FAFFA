@extends('layouts.guest')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-[#0e1a13] mb-2">Reset Password</h2>
        <p class="text-sm text-[#51946c]">Masukkan password baru untuk akun Anda</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-[#0e1a13] mb-2">
                Email <span class="text-red-500">*</span>
            </label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email', $request->email) }}" 
                required 
                autofocus 
                autocomplete="username"
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
                Password Baru <span class="text-red-500">*</span>
            </label>
            <input 
                id="password" 
                type="password" 
                name="password" 
                required 
                autocomplete="new-password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#38e07b] focus:border-[#38e07b] outline-none transition text-[#0e1a13] placeholder-gray-400"
                placeholder="••••••••"
            />
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-[#0e1a13] mb-2">
                Konfirmasi Password Baru <span class="text-red-500">*</span>
            </label>
            <input 
                id="password_confirmation" 
                                type="password"
                name="password_confirmation" 
                required 
                autocomplete="new-password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#38e07b] focus:border-[#38e07b] outline-none transition text-[#0e1a13] placeholder-gray-400"
                placeholder="••••••••"
            />
            @error('password_confirmation')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <button 
            type="submit"
            class="w-full py-3 px-4 bg-[#38e07b] text-[#0e1a13] font-bold rounded-lg hover:bg-[#2ecc71] focus:outline-none focus:ring-2 focus:ring-[#38e07b] focus:ring-offset-2 transition shadow-sm"
        >
            Reset Password
        </button>
    </form>

    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="text-sm font-medium text-[#38e07b] hover:text-[#2ecc71] transition">
            ← Kembali ke Login
        </a>
    </div>
@endsection
