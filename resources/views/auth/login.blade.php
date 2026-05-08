@extends('layouts.guest')

@section('content')
    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-800">{{ session('status') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-[#0e1a13] mb-2">
                Email
            </label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
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
                Password
            </label>
            <input 
                id="password" 
                            type="password"
                            name="password"
                required 
                autocomplete="current-password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#38e07b] focus:border-[#38e07b] outline-none transition text-[#0e1a13] placeholder-gray-400"
                placeholder="••••••••"
            />
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    name="remember"
                    class="rounded border-gray-300 text-[#38e07b] shadow-sm focus:ring-[#38e07b] focus:ring-offset-0"
                >
                <span class="ms-2 text-sm text-[#51946c]">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a 
                    href="{{ route('password.request') }}" 
                    class="text-sm font-medium text-[#38e07b] hover:text-[#2ecc71] transition"
                >
                    Lupa password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button 
            type="submit"
            class="w-full py-3 px-4 bg-[#38e07b] text-[#0e1a13] font-bold rounded-lg hover:bg-[#2ecc71] focus:outline-none focus:ring-2 focus:ring-[#38e07b] focus:ring-offset-2 transition shadow-sm"
        >
            Masuk
        </button>
    </form>
@endsection
