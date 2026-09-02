<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favorites = $request->user()->documents()
            ->where('is_favorite', true)
            ->with('category')
            ->latest()
            ->get();

        return view('favorites.index', compact('favorites'));
    }
}
