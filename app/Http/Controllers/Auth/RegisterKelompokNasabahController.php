<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;

class RegisterKelompokNasabahController extends Controller
{
    public function create()
    {
        return view('auth.register-kelompok-nasabah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelompok' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'lokasi' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $kelompok = Kelompok::create([
            'nama' => $request->nama_kelompok,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'is_active' => true,
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'kelompok_id' => $kelompok->id,
            'is_active' => true,
        ]);
        $user->assignRole('kelompok_nasabah');

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('dashboard');
    }
} 