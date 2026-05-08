@extends('layouts.guest')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-[#0e1a13] mb-2">Lupa Password</h2>
        <p class="text-sm text-[#51946c]">Masukkan email Anda dan kami akan mengirimkan link reset password</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-800">{{ session('status') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
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
                autofocus
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#38e07b] focus:border-[#38e07b] outline-none transition text-[#0e1a13] placeholder-gray-400"
                placeholder="nama@email.com"
            />
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <button 
            type="submit"
            class="w-full py-3 px-4 bg-[#38e07b] text-[#0e1a13] font-bold rounded-lg hover:bg-[#2ecc71] focus:outline-none focus:ring-2 focus:ring-[#38e07b] focus:ring-offset-2 transition shadow-sm"
        >
            Kirim Link Reset Password
        </button>
    </form>

    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="text-sm font-medium text-[#38e07b] hover:text-[#2ecc71] transition">
            ← Kembali ke Login
        </a>
    </div>
@endsection
