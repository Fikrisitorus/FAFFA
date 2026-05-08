@extends('filament::page')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold mb-4">Profil Admin</h1>
    <div class="bg-white rounded shadow p-4">
        <p>Nama: {{ auth()->user()->name }}</p>
        <p>Email: {{ auth()->user()->email }}</p>
        <p>Role: Admin</p>
    </div>
    <div class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded">
        Fitur edit profil admin bisa ditambahkan di sini.
    </div>
</div>
@endsection
