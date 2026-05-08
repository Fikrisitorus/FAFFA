<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Filament\Notifications\Notification;

class ProfilKelompokNasabah extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static string $view = 'filament.pages.profil-kelompok-nasabah';
    protected static ?string $navigationLabel = 'Profil';
    protected static ?string $navigationGroup = 'Kelompok Nasabah';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('kelompok_nasabah');
    }

    public $user;
    public $kelompok;

    public function mount()
    {
        $this->user = Auth::user();
        $this->kelompok = $this->user->kelompok;
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
            'kelompok_nama' => 'required|string|max:255',
            'kelompok_deskripsi' => 'nullable|string',
            'kelompok_lokasi' => 'nullable|string',
        ]);
        try {
            $user = Auth::user();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->phone = $data['phone'];
            $user->address = $data['address'];
            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }
            $user->save();
            
            $kelompok = $user->kelompok;
            $kelompok->nama = $data['kelompok_nama'];
            $kelompok->deskripsi = $data['kelompok_deskripsi'];
            $kelompok->lokasi = $data['kelompok_lokasi'];
            $kelompok->save();
            
            Notification::make()
                ->title('Berhasil')
                ->body('Profil berhasil diperbarui.')
                ->success()
                ->send();
            
            $this->mount();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal')
                ->body('Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
} 