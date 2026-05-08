<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span>Notifikasi</span>
                    @if($this->unreadCount > 0)
                        <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-danger-600 rounded-full">
                            {{ $this->unreadCount }}
                        </span>
                    @endif
                </div>
                @if($this->unreadCount > 0)
                    <x-filament::button 
                        wire:click="markAllAsRead" 
                        size="sm" 
                        color="gray"
                    >
                        Tandai Semua Dibaca
                    </x-filament::button>
                @endif
            </div>
        </x-slot>

        <div class="space-y-2">
            @forelse($this->recentNotifications as $notification)
                <div 
                    wire:click="markAsRead({{ $notification['id'] }})"
                    class="p-3 rounded-lg border cursor-pointer transition-colors {{ $notification['is_read'] ? 'bg-gray-50 border-gray-200' : 'bg-primary-50 border-primary-200' }}"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <h4 class="text-sm font-semibold {{ $notification['is_read'] ? 'text-gray-700' : 'text-primary-700' }}">
                                    {{ $notification['title'] }}
                                </h4>
                                @if(!$notification['is_read'])
                                    <span class="w-2 h-2 bg-primary-600 rounded-full"></span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $notification['message'] }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">
                                {{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <p>Tidak ada notifikasi</p>
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>


