<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $categories = $user->categories;

        $query = $user->documents()->with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('type')) {
            $type = $request->type;
            $query->where(function ($q) use ($type) {
                $q->where('file_type', $type)
                  ->orWhere('file_name', 'like', "%.{$type}")
                  ->orWhere('name', 'like', "%.{$type}");
            });
        }

        $documents = $query->latest()->get();

        return view('documents.index', compact('documents', 'categories'));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'file' => ['nullable', 'file'],
        ]);

        $filePath = null;
        $fileName = null;
        $fileSize = 0;
        $fileType = 'pdf';

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('documents', 'public');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $fileType = strtolower($file->getClientOriginalExtension());
        }

        $user->documents()->create([
            'name' => $data['name'],
            'category_id' => $data['category_id'] ?? null,
            'description' => $data['description'] ?? null,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'file_type' => $fileType,
            'is_favorite' => false,
        ]);

        return redirect()->route('documents.index')->with('success', 'Document ajouté avec succès');
    }

    public function show(Request $request, $id)
    {
        $document = $request->user()->documents()
            ->with('category')
            ->findOrFail($id);

        return view('documents.show', compact('document'));
    }

    public function update(Request $request, $id)
    {
        $document = $request->user()->documents()->findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
        ]);

        $document->update($data);

        return redirect()->route('documents.show', $document)->with('success', 'Document mis à jour');
    }

    public function toggleFavorite(Request $request, $id)
    {
        $document = $request->user()->documents()->findOrFail($id);
        $document->update(['is_favorite' => !$document->is_favorite]);

        return back()->with('success', $document->is_favorite ? 'Ajouté aux favoris' : 'Retiré des favoris');
    }

    public function destroy(Request $request, $id)
    {
        $request->user()->documents()->findOrFail($id)->delete();

        return redirect()->route('documents.index')->with('success', 'Document déplacé vers la corbeille');
    }

    public function download(Request $request, $id)
    {
        $document = $request->user()->documents()->findOrFail($id);

        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            return Storage::disk('public')->download($document->file_path, $document->file_name ?: $document->name);
        }

        return back()->with('error', 'Fichier introuvable.');
    }
}
