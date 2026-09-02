<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Document;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $documents = $user->documents()->with('category')->get();
        $categories = $user->categories()->withCount('documents')->get();

        $stats = [
            'documents' => $user->documents()->count(),
            'categories' => $user->categories()->count(),
            'storage_used' => $user->documents()->sum('file_size'),
            'favorites' => $user->documents()->where('is_favorite', true)->count(),
        ];

        $recent = $user->documents()
            ->with('category')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact('documents', 'categories', 'stats', 'recent'));
    }
}
