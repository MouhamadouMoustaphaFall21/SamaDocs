<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Document;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $categories = $user->categories()->withCount('documents')->get();

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:20'],
            'icon' => ['nullable', 'string', 'max:50'],
        ]);

        $user->categories()->create([
            'name' => $data['name'],
            'color' => $data['color'] ?? '#3b82f6',
            'icon' => $data['icon'] ?? 'fa-folder',
        ]);

        return redirect()->route('categories.index')->with('success', 'Catégorie créée avec succès');
    }

    public function update(Request $request, $id)
    {
        $category = $request->user()->categories()->findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:20'],
            'icon' => ['nullable', 'string', 'max:50'],
        ]);

        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'Catégorie mise à jour');
    }

    public function destroy(Request $request, $id)
    {
        $category = $request->user()->categories()->findOrFail($id);

        // Move documents to no category before deleting
        Document::where('category_id', $category->id)->update(['category_id' => null]);

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Catégorie supprimée');
    }

    public function show(Request $request, $id)
    {
        $category = $request->user()->categories()->findOrFail($id);
        $documents = $category->documents()->with('category')->latest()->get();

        return view('categories.show', compact('category', 'documents'));
    }
}
