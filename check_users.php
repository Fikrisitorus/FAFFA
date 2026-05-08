<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "Users dengan role kelompok_nasabah:\n";
$users = User::role('kelompok_nasabah')->get(['name', 'email', 'is_active']);

foreach ($users as $user) {
    echo "- {$user->name} ({$user->email}) - Active: " . ($user->is_active ? 'Yes' : 'No') . "\n";
}

echo "\nSemua users:\n";
$allUsers = User::with('roles')->get(['name', 'email', 'is_active']);

foreach ($allUsers as $user) {
    $roles = $user->roles->pluck('name')->implode(', ');
    echo "- {$user->name} ({$user->email}) - Roles: {$roles} - Active: " . ($user->is_active ? 'Yes' : 'No') . "\n";
} 