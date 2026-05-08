<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification as FilamentNotification;

class NotificationWidget extends Widget
{
    protected static string $view = 'filament.widgets.notification-widget';
    
    protected int | string | array $columnSpan = 'full';
    
    public $unreadCount = 0;
    public $recentNotifications = [];
    public $lastCheck = null;

    public function mount(): void
    {
        $this->loadNotifications();
    }

    public function loadNotifications(): void
    {
        $user = Auth::user();
        
        $this->unreadCount = Notification::where('user_id', $user->id)
            ->unread()
            ->count();
        
        $this->recentNotifications = Notification::where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get()
            ->toArray();
        
        $this->lastCheck = now()->toDateTimeString();
    }

    public function markAsRead($id): void
    {
        $user = Auth::user();
        
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
        
        if ($notification) {
            $notification->markAsRead();
            $this->loadNotifications();
        }
    }

    public function markAllAsRead(): void
    {
        $user = Auth::user();
        
        Notification::where('user_id', $user->id)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        
        FilamentNotification::make()
            ->title('Semua notifikasi ditandai sudah dibaca')
            ->success()
            ->send();
        
        $this->loadNotifications();
    }

    protected function getListeners(): array
    {
        return [
            'echo-private:notifications.' . Auth::id() . ',NotificationCreated' => 'loadNotifications',
        ];
    }

    // Polling untuk check new notifications setiap 30 detik
    protected static ?string $pollingInterval = '30s';
}


