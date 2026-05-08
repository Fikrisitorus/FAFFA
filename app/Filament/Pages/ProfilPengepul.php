<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Filament\Notifications\Notification;

class ProfilPengepul extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static string $view = 'filament.pages.profil-pengepul';
    protected static ?string $navigationLabel = 'Profil';
    protected static ?string $navigationGroup = 'Pengepul';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->hasRole('pengepul');
    }

    public $user;

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        $user = Auth::user();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];
        $user->address = $data['address'];
        try {
            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }
            $user->save();
            
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