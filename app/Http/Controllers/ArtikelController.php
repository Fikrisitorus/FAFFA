<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    /**
     * Display a listing of published articles
     */
    public function index()
    {
        // Cache artikel dengan pagination (10 menit)
        $cacheKey = 'artikel_index_page_' . request()->get('page', 1);
        $artikel = \Illuminate\Support\Facades\Cache::remember($cacheKey, 600, function () {
            return Artikel::published()
                ->with('author')
                ->latest('published_at')
                ->paginate(12);
        });

        return view('artikel.index', compact('artikel'));
    }

    /**
     * Display the specified article
     */
    public function show($slug)
    {
        try {
            $artikel = Artikel::published()
                ->where('slug', $slug)
                ->with('author')
                ->firstOrFail();

            // Artikel terkait (dari tag yang sama atau terbaru)
            $artikelTerkait = Artikel::published()
                ->where('id', '!=', $artikel->id)
                ->latest('published_at')
                ->limit(3)
                ->get();

            return view('artikel.show', compact('artikel', 'artikelTerkait'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Artikel tidak ditemukan');
        }
    }
}

