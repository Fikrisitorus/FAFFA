<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Dashboard Admin - Monitoring Sistem
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        Pantau seluruh aktivitas sistem dan performa aplikasi
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Terakhir diperbarui</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ now()->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Widgets akan ditampilkan di sini -->
        <x-filament-widgets::widgets
            :widgets="$this->getWidgets()"
        />
    </div>
</x-filament-panels::page>
